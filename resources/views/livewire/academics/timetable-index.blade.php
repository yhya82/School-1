<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Timetable') }}
            </h2>
            @can('create', \App\Models\TimetableSlot::class)
                <a href="{{ route('academics.timetable.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Slot') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
            <x-input-label for="section_id" value="{{ __('Filter by section') }}" />
            <select wire:model.live="section_id" id="section_id" class="mt-1 block w-full sm:w-64 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="">{{ __('All sections') }}</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->schoolClass->name }} - {{ $section->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Section') }}</th>
                        <th class="px-6 py-3">{{ __('Subject') }}</th>
                        <th class="px-6 py-3">{{ __('Teacher') }}</th>
                        <th class="px-6 py-3">{{ __('Day') }}</th>
                        <th class="px-6 py-3">{{ __('Time') }}</th>
                        <th class="px-6 py-3">{{ __('Room') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($slots as $slot)
                        <tr wire:key="slot-{{ $slot->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $slot->section->schoolClass->name }} - {{ $slot->section->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $slot->classSubject->subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $slot->classSubject->teacher?->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $slot->day_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $slot->room }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $slot)
                                    <a href="{{ route('academics.timetable.edit', $slot) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $slot)
                                    <button wire:click="delete({{ $slot->id }})" wire:confirm="{{ __('Delete this timetable slot?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No timetable slots yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
