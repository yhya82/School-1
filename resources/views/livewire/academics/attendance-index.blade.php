<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Attendance') }}
            </h2>
            @can('create', \App\Models\Attendance::class)
                <a href="{{ route('academics.attendance.mark') }}" wire:navigate>
                    <x-primary-button>{{ __('Mark Attendance') }}</x-primary-button>
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

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Student') }}</th>
                        <th class="px-6 py-3">{{ __('Section') }}</th>
                        <th class="px-6 py-3">{{ __('Date') }}</th>
                        <th class="px-6 py-3">{{ __('Status') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="attendance-{{ $attendance->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->student->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $attendance->section->schoolClass->name }} - {{ $attendance->section->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $attendance->date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($attendance->status) }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('delete', $attendance)
                                    <button wire:click="delete({{ $attendance->id }})" wire:confirm="{{ __('Delete this attendance record?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No attendance records yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
