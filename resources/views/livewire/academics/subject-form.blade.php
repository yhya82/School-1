<div>
    <div>
        <x-input-label for="name" value="{{ __('Name') }}" />
        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="Mathematics" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="code" value="{{ __('Code') }}" />
        <x-text-input wire:model="code" id="code" class="mt-1 block w-full" placeholder="MAT101" />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('academics.subjects.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
