<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>

        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg p-4 text-sm text-green-800 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6">
            <form wire:submit="save">
                <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('School Information') }}</h3>

                <div>
                    <x-input-label for="school_name" value="{{ __('School Name') }}" />
                    <x-text-input wire:model="school_name" id="school_name" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="school_address" value="{{ __('Address') }}" />
                    <x-text-input wire:model="school_address" id="school_address" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
                </div>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="school_phone" value="{{ __('Phone') }}" />
                        <x-text-input wire:model="school_phone" id="school_phone" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('school_phone')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="school_email" value="{{ __('Email') }}" />
                        <x-text-input wire:model="school_email" id="school_email" type="email" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('school_email')" class="mt-2" />
                    </div>
                </div>

                <h3 class="font-medium text-gray-800 dark:text-gray-200 mt-8 mb-4">{{ __('Academic Year') }}</h3>
                <div>
                    <x-input-label for="current_academic_year_id" value="{{ __('Current Academic Year') }}" />
                    <select wire:model="current_academic_year_id" id="current_academic_year_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('None selected') }}</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Used as the default academic year when creating students, exams, and fee structures.') }}</p>
                    <x-input-error :messages="$errors->get('current_academic_year_id')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
