<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Invoice') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="student_id" value="{{ __('Student') }}" />
                    <select wire:model="student_id" id="student_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select student') }}</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->admission_no }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="fee_structure_id" value="{{ __('Fee Structure') }}" />
                    <select wire:model.live="fee_structure_id" id="fee_structure_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select fee structure') }}</option>
                        @foreach ($feeStructures as $feeStructure)
                            <option value="{{ $feeStructure->id }}">{{ $feeStructure->name }} ({{ $feeStructure->schoolClass->name }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('fee_structure_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="amount_due" value="{{ __('Amount Due') }}" />
                    <x-text-input wire:model="amount_due" id="amount_due" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('amount_due')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="due_date" value="{{ __('Due Date') }}" />
                    <x-text-input wire:model="due_date" id="due_date" type="date" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Add Invoice') }}</x-primary-button>
                    <a href="{{ route('finance.invoices.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
