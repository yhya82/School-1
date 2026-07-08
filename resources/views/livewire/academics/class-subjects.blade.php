<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Class Subject & Teacher Assignment') }}
        </h2>

        @can('create', \App\Models\ClassSubject::class)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="save" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <x-input-label for="class_id" value="{{ __('Class') }}" />
                        <select wire:model="class_id" id="class_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Select class') }}</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="subject_id" value="{{ __('Subject') }}" />
                        <select wire:model="subject_id" id="subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Select subject') }}</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="teacher_id" value="{{ __('Teacher') }}" />
                        <select wire:model="teacher_id" id="teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-3 flex gap-3">
                        <x-primary-button>{{ $editingId ? __('Update') : __('Assign') }}</x-primary-button>
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
                        <th class="px-6 py-3">{{ __('Class') }}</th>
                        <th class="px-6 py-3">{{ __('Subject') }}</th>
                        <th class="px-6 py-3">{{ __('Teacher') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($assignments as $assignment)
                        <tr wire:key="assignment-{{ $assignment->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $assignment->schoolClass->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $assignment->subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $assignment->teacher?->user->name ?? __('Unassigned') }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $assignment)
                                    <button wire:click="edit({{ $assignment->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                @endcan
                                @can('delete', $assignment)
                                    <button wire:click="delete({{ $assignment->id }})" wire:confirm="{{ __('Remove this assignment?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No assignments yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
