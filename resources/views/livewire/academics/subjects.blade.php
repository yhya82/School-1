<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subjects') }}
        </h2>

        @can('create', \App\Models\Subject::class)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="save" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <x-input-label for="name" value="{{ __('Name') }}" />
                        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="Mathematics" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="code" value="{{ __('Code') }}" />
                        <x-text-input wire:model="code" id="code" class="mt-1 block w-full" placeholder="MAT101" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2 flex gap-3">
                        <x-primary-button>{{ $editingId ? __('Update') : __('Add Subject') }}</x-primary-button>
                        @if ($editingId)
                            <x-secondary-button wire:click="resetForm">{{ __('Cancel') }}</x-secondary-button>
                        @endif
                    </div>
                </form>
            </div>
        @endcan

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Code') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($subjects as $subject)
                        <tr wire:key="subject-{{ $subject->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $subject->code }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $subject)
                                    <button wire:click="edit({{ $subject->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                @endcan
                                @can('delete', $subject)
                                    <button wire:click="delete({{ $subject->id }})" wire:confirm="{{ __('Delete this subject?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No subjects yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
