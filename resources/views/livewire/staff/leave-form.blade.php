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
        <x-input-label for="leave_type" value="{{ __('Leave Type') }}" />
        <select wire:model="leave_type" id="leave_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select type') }}</option>
            <option value="sick">{{ __('Sick') }}</option>
            <option value="casual">{{ __('Casual') }}</option>
            <option value="annual">{{ __('Annual') }}</option>
        </select>
        <x-input-error :messages="$errors->get('leave_type')" class="mt-2" />
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

    @if ($showStatus ?? false)
        <div class="mt-4">
            <x-input-label for="status" value="{{ __('Status') }}" />
            <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="pending">{{ __('Pending') }}</option>
                <option value="approved">{{ __('Approved') }}</option>
                <option value="rejected">{{ __('Rejected') }}</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    @endif

    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('staff.leaves.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
