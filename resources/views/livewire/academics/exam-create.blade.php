<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Exam') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="academic_year_id" value="{{ __('Academic Year') }}" />
                    <select wire:model="academic_year_id" id="academic_year_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select year') }}</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="name" value="{{ __('Name') }}" />
                    <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="Midterm Exam" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="start_date" value="{{ __('Start Date') }}" />
                        <x-text-input wire:model="start_date" id="start_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="{{ __('End Date') }}" />
                        <x-text-input wire:model="end_date" id="end_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Add Exam') }}</x-primary-button>
                    <a href="{{ route('academics.exams.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
