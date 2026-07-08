<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <x-input-label for="name" value="{{ __('Full Name') }}" />
        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="email" value="{{ __('Email') }}" />
        <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="admission_no" value="{{ __('Admission No.') }}" />
        <x-text-input wire:model="admission_no" id="admission_no" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('admission_no')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="class_id" value="{{ __('Class') }}" />
        <select wire:model.live="class_id" id="class_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select class') }}</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="section_id" value="{{ __('Section') }}" />
        <select wire:model="section_id" id="section_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select section') }}</option>
            @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('section_id')" class="mt-2" />
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
        <x-input-label for="dob" value="{{ __('Date of Birth') }}" />
        <x-text-input wire:model="dob" id="dob" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('dob')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="gender" value="{{ __('Gender') }}" />
        <select wire:model="gender" id="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select') }}</option>
            <option value="M">{{ __('Male') }}</option>
            <option value="F">{{ __('Female') }}</option>
            <option value="O">{{ __('Other') }}</option>
        </select>
        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="admission_date" value="{{ __('Admission Date') }}" />
        <x-text-input wire:model="admission_date" id="admission_date" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
    </div>

    @if ($showStatus ?? false)
        <div>
            <x-input-label for="status" value="{{ __('Status') }}" />
            <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="active">{{ __('Active') }}</option>
                <option value="graduated">{{ __('Graduated') }}</option>
                <option value="withdrawn">{{ __('Withdrawn') }}</option>
            </select>
        </div>
    @endif

    <div class="sm:col-span-3">
        <x-input-label value="{{ __('Guardians') }}" />
        <div class="mt-1 flex flex-wrap gap-4">
            @foreach ($guardians as $guardian)
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" wire:model="selectedGuardianIds" value="{{ $guardian->id }}" class="rounded border-gray-300 dark:border-gray-700">
                    {{ $guardian->name }} ({{ $guardian->relationship }})
                </label>
            @endforeach
            @if ($guardians->isEmpty())
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('No guardians created yet.') }}</span>
            @endif
        </div>
    </div>

    <div class="sm:col-span-3 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('students.students.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
