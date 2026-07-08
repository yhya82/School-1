<div>
    <div>
        <x-input-label for="staff_id" value="{{ __('Staff Member') }}" />
        <select wire:model="staff_id" id="staff_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select staff member') }}</option>
            @foreach ($staffMembers as $staff)
                <option value="{{ $staff->id }}">{{ $staff->user->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('staff_id')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="date" value="{{ __('Date') }}" />
        <x-text-input wire:model="date" id="date" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="status" value="{{ __('Status') }}" />
        <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="present">{{ __('Present') }}</option>
            <option value="absent">{{ __('Absent') }}</option>
            <option value="late">{{ __('Late') }}</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('staff.attendance.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
