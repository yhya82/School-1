<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Class') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="name" value="{{ __('Class Name') }}" />
                    <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="Grade 5" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Add Class') }}</x-primary-button>
                    <a href="{{ route('academics.classes.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
