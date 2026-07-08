<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = ['student_id', 'fee_structure_id', 'amount_due', 'due_date', 'status'];

    protected function casts(): array
    {
        return [
            'amount_due' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Recompute and persist status from the sum of recorded payments.
     */
    public function recalculateStatus(): void
    {
        $totalPaid = $this->payments()->sum('amount_paid');

        $status = match (true) {
            $totalPaid <= 0 => 'unpaid',
            $totalPaid < $this->amount_due => 'partial',
            default => 'paid',
        };

        $this->update(['status' => $status]);
    }
}
