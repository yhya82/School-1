<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
        <x-input-label for="employee_no" value="{{ __('Employee No.') }}" />
        <x-text-input wire:model="employee_no" id="employee_no" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('employee_no')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="joining_date" value="{{ __('Joining Date') }}" />
        <x-text-input wire:model="joining_date" id="joining_date" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('joining_date')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="designation" value="{{ __('Designation') }}" />
        <x-text-input wire:model="designation" id="designation" class="mt-1 block w-full" placeholder="Teacher" />
        <x-input-error :messages="$errors->get('designation')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="department" value="{{ __('Department') }}" />
        <x-text-input wire:model="department" id="department" class="mt-1 block w-full" placeholder="Science" />
        <x-input-error :messages="$errors->get('department')" class="mt-2" />
    </div>

    @if ($showRole ?? false)
        <div>
            <x-input-label for="role" value="{{ __('Role') }}" />
            <select wire:model="role" id="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="teacher">{{ __('Teacher') }}</option>
                <option value="staff">{{ __('Staff') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
    @endif

    <div class="sm:col-span-2 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('staff.staff.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
