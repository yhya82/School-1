<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Classes & Sections') }}
        </h2>

        @can('create', \App\Models\SchoolClass::class)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="saveClass" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <x-input-label for="className" value="{{ __('Class Name') }}" />
                        <x-text-input wire:model="className" id="className" class="mt-1 block w-full" placeholder="Grade 5" />
                        <x-input-error :messages="$errors->get('className')" class="mt-2" />
                    </div>
                    <x-primary-button>{{ $editingClassId ? __('Update') : __('Add Class') }}</x-primary-button>
                    @if ($editingClassId)
                        <x-secondary-button wire:click="$set('editingClassId', null)">{{ __('Cancel') }}</x-secondary-button>
                    @endif
                </form>
            </div>
        @endcan

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Sections') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($classes as $class)
                        <tr wire:key="class-{{ $class->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $class->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $class->sections_count }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <button wire:click="manageSections({{ $class->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $sectionClassId === $class->id ? __('Hide Sections') : __('Sections') }}
                                </button>
                                @can('update', $class)
                                    <button wire:click="editClass({{ $class->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                @endcan
                                @can('delete', $class)
                                    <button wire:click="deleteClass({{ $class->id }})" wire:confirm="{{ __('Delete this class? Sections under it will also be deleted.') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                        @if ($sectionClassId === $class->id)
                            <tr wire:key="class-{{ $class->id }}-sections">
                                <td colspan="3" class="px-6 py-4 bg-gray-50 dark:bg-gray-900">
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
                                        </form>
                                    @endcan

                                    <table class="min-w-full">
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
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No classes yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
