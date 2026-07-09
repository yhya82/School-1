<div>
    <div>
        <x-input-label for="name" value="{{ __('Name') }}" />
        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="phone" value="{{ __('Phone') }}" />
        <x-text-input wire:model="phone" id="phone" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="relationship" value="{{ __('Relationship') }}" />
        <select wire:model="relationship" id="relationship" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select') }}</option>
            <option value="Father">{{ __('Father') }}</option>
            <option value="Mother">{{ __('Mother') }}</option>
            <option value="Guardian">{{ __('Guardian') }}</option>
        </select>
        <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="email" value="{{ __('Portal Login Email (optional)') }}" />
        <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" placeholder="parent@example.com" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Set an email to give this guardian access to the parent portal.') }}</p>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('students.guardians.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
