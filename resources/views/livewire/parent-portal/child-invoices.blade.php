<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoices') }} — {{ $student->user->name }}
            </h2>
            <a href="{{ route('portal.children.index') }}" wire:navigate class="text-sm text-navy-600 dark:text-navy-400 hover:underline">{{ __('Back to My Children') }}</a>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Fee') }}</th>
                        <th class="px-6 py-3">{{ __('Amount Due') }}</th>
                        <th class="px-6 py-3">{{ __('Paid') }}</th>
                        <th class="px-6 py-3">{{ __('Due Date') }}</th>
                        <th class="px-6 py-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="invoice-{{ $invoice->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $invoice->feeStructure->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($invoice->amount_due, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($invoice->payments->sum('amount_paid'), 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $invoice->due_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($invoice->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No invoices yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
