<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Payment') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="invoice_id" value="{{ __('Invoice') }}" />
                    <select wire:model="invoice_id" id="invoice_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select invoice') }}</option>
                        @foreach ($invoices as $invoice)
                            <option value="{{ $invoice->id }}">{{ $invoice->student->user->name }} — {{ $invoice->feeStructure->name }} ({{ number_format($invoice->amount_due, 2) }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('invoice_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="amount_paid" value="{{ __('Amount Paid') }}" />
                    <x-text-input wire:model="amount_paid" id="amount_paid" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="payment_date" value="{{ __('Payment Date') }}" />
                    <x-text-input wire:model="payment_date" id="payment_date" type="date" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="payment_method" value="{{ __('Payment Method') }}" />
                    <select wire:model="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="card">{{ __('Card') }}</option>
                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                        <option value="online">{{ __('Online') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="transaction_ref" value="{{ __('Transaction Ref (optional)') }}" />
                    <x-text-input wire:model="transaction_ref" id="transaction_ref" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('transaction_ref')" class="mt-2" />
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Record Payment') }}</x-primary-button>
                    <a href="{{ route('finance.invoices.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
