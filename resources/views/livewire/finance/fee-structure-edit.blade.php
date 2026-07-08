<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Fee Structure') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                @include('livewire.finance.fee-structure-form', ['submitLabel' => __('Update')])
            </form>
        </div>

        @can('create', \App\Models\Invoice::class)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-lg text-gray-800 dark:text-gray-200 mb-4">{{ __('Generate Invoices') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    {{ __('Creates an invoice for every active student in this fee structure\'s class who does not already have one.') }}
                </p>

                @if ($generatedCount !== null)
                    <div class="mb-4 bg-green-50 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg p-4 text-sm text-green-800 dark:text-green-200">
                        {{ __(':count invoice(s) generated.', ['count' => $generatedCount]) }}
                    </div>
                @endif

                <form wire:submit="generateInvoices" class="flex gap-4 items-end">
                    <div>
                        <x-input-label for="due_date" value="{{ __('Due Date') }}" />
                        <x-text-input wire:model="due_date" id="due_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                    <x-primary-button>{{ __('Generate Invoices') }}</x-primary-button>
                </form>
            </div>
        @endcan
    </div>
</div>
