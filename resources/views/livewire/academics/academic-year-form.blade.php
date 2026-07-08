<div>
    <div>
        <x-input-label for="name" value="{{ __('Name') }}" />
        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="2026/2027" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="start_date" value="{{ __('Start Date') }}" />
        <x-text-input wire:model="start_date" id="start_date" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="end_date" value="{{ __('End Date') }}" />
        <x-text-input wire:model="end_date" id="end_date" type="date" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('academics.years.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
