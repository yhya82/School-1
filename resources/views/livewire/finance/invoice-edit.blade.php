<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Invoice') }} — {{ $invoice->student->user->name }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Fee') }}: {{ $invoice->feeStructure->name }}</p>

            <form wire:submit="save">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="amount_due" value="{{ __('Amount Due') }}" />
                        <x-text-input wire:model="amount_due" id="amount_due" type="number" step="0.01" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('amount_due')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="due_date" value="{{ __('Due Date') }}" />
                        <x-text-input wire:model="due_date" id="due_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="status" value="{{ __('Status') }}" />
                        <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="unpaid">{{ __('Unpaid') }}</option>
                            <option value="partial">{{ __('Partial') }}</option>
                            <option value="paid">{{ __('Paid') }}</option>
                            <option value="overdue">{{ __('Overdue') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('finance.invoices.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-medium text-lg text-gray-800 dark:text-gray-200">{{ __('Payments') }}</h3>
                @can('create', \App\Models\Payment::class)
                    <a href="{{ route('finance.payments.create', ['invoice' => $invoice->id]) }}" wire:navigate>
                        <x-primary-button>{{ __('Record Payment') }}</x-primary-button>
                    </a>
                @endcan
            </div>

            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($invoice->payments as $payment)
                    <li class="py-2 text-sm text-gray-900 dark:text-gray-100">
                        {{ number_format($payment->amount_paid, 2) }} — {{ ucfirst($payment->payment_method) }} — {{ $payment->payment_date->format('Y-m-d') }}
                    </li>
                @empty
                    <li class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No payments recorded yet.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
