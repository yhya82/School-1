<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreInvoiceRequest;
use App\Http\Requests\Api\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    protected function relations(): array
    {
        return ['student.user', 'feeStructure', 'payments'];
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Invoice::class);

        $invoices = Invoice::with($this->relations())
            ->when($request->query('student_id'), fn ($query, $value) => $query->where('student_id', $value))
            ->when($request->query('status'), fn ($query, $value) => $query->where('status', $value))
            ->orderByDesc('due_date')
            ->paginate(20);

        return InvoiceResource::collection($invoices);
    }

    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);

        return new InvoiceResource($invoice->load($this->relations()));
    }

    public function store(StoreInvoiceRequest $request)
    {
        Gate::authorize('create', Invoice::class);

        $data = $request->validated();

        $exists = Invoice::where('student_id', $data['student_id'])
            ->where('fee_structure_id', $data['fee_structure_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => __('This student already has an invoice for this fee structure.'),
            ], 422);
        }

        $invoice = Invoice::create([
            ...$data,
            'status' => 'unpaid',
        ]);

        return (new InvoiceResource($invoice->load($this->relations())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);

        $invoice->update($request->validated());

        return new InvoiceResource($invoice->fresh($this->relations()));
    }
}
