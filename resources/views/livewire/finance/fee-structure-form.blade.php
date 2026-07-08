<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="class_id" value="{{ __('Class') }}" />
        <select wire:model="class_id" id="class_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select class') }}</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
    </div>
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
    <div>
        <x-input-label for="name" value="{{ __('Name') }}" />
        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="Tuition Fee" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="amount" value="{{ __('Amount') }}" />
        <x-text-input wire:model="amount" id="amount" type="number" step="0.01" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="frequency" value="{{ __('Frequency') }}" />
        <select wire:model="frequency" id="frequency" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="monthly">{{ __('Monthly') }}</option>
            <option value="term">{{ __('Term') }}</option>
            <option value="annual">{{ __('Annual') }}</option>
        </select>
        <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
    </div>

    <div class="sm:col-span-2 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('finance.fee-structures.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
