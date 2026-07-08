<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Class') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="name" value="{{ __('Class Name') }}" />
                    <x-text-input wire:model="name" id="name" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('academics.classes.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="font-medium text-lg text-gray-800 dark:text-gray-200 mb-4">{{ __('Sections') }}</h3>

            @can('create', \App\Models\Section::class)
                <form wire:submit="saveSection" class="flex gap-4 items-end mb-4">
                    <div>
                        <x-input-label for="sectionName" value="{{ __('Section Name') }}" />
                        <x-text-input wire:model="sectionName" id="sectionName" class="mt-1 block w-full" placeholder="A" />
                        <x-input-error :messages="$errors->get('sectionName')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="sectionCapacity" value="{{ __('Capacity') }}" />
                        <x-text-input wire:model="sectionCapacity" id="sectionCapacity" type="number" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('sectionCapacity')" class="mt-2" />
                    </div>
                    <x-primary-button>{{ $editingSectionId ? __('Update') : __('Add Section') }}</x-primary-button>
                    @if ($editingSectionId)
                        <x-secondary-button type="button" wire:click="cancelSectionEdit">{{ __('Cancel') }}</x-secondary-button>
                    @endif
                </form>
            @endcan

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($sections as $section)
                        <tr wire:key="section-{{ $section->id }}">
                            <td class="py-2 text-sm text-gray-900 dark:text-gray-100">{{ $section->name }}</td>
                            <td class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Capacity') }}: {{ $section->capacity }}</td>
                            <td class="py-2 text-sm text-right space-x-2">
                                @can('update', $section)
                                    <button wire:click="editSection({{ $section->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                @endcan
                                @can('delete', $section)
                                    <button wire:click="deleteSection({{ $section->id }})" wire:confirm="{{ __('Delete this section?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No sections yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
