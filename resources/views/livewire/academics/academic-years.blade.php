<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Academic Years') }}
        </h2>
            @can('create', \App\Models\AcademicYear::class)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form wire:submit="save" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label for="name" value="{{ __('Name') }}" />
                            <x-text-input wire:model="name" id="name" class="mt-1 block w-full" placeholder="2026/2027" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
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
                        <div class="sm:col-span-3 flex gap-3">
                            <x-primary-button>{{ $editingId ? __('Update') : __('Add Year') }}</x-primary-button>
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
                            <th class="px-6 py-3">{{ __('Start') }}</th>
                            <th class="px-6 py-3">{{ __('End') }}</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($years as $year)
                            <tr wire:key="year-{{ $year->id }}">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $year->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $year->start_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $year->end_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-right space-x-2">
                                    @can('update', $year)
                                        <button wire:click="edit({{ $year->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                    @endcan
                                    @can('delete', $year)
                                        <button wire:click="delete({{ $year->id }})" wire:confirm="{{ __('Delete this academic year?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No academic years yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
