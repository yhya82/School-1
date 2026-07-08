<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Expense') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="category" value="{{ __('Category') }}" />
                    <x-text-input wire:model="category" id="category" class="mt-1 block w-full" placeholder="Utilities" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="amount" value="{{ __('Amount') }}" />
                    <x-text-input wire:model="amount" id="amount" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="expense_date" value="{{ __('Date') }}" />
                    <x-text-input wire:model="expense_date" id="expense_date" type="date" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="description" value="{{ __('Description') }}" />
                    <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Add Expense') }}</x-primary-button>
                    <a href="{{ route('finance.expenses.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
