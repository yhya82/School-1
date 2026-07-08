<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mark Attendance') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="section_id" value="{{ __('Section') }}" />
                        <select wire:model.live="section_id" id="section_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Select section') }}</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->schoolClass->name }} - {{ $section->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('section_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date" value="{{ __('Date') }}" />
                        <x-text-input wire:model.live="date" id="date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                </div>

                @if ($students->isNotEmpty())
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-6">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="py-2">{{ __('Student') }}</th>
                                <th class="py-2">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                <tr wire:key="student-{{ $student->id }}">
                                    <td class="py-2 text-sm text-gray-900 dark:text-gray-100">{{ $student->user->name }}</td>
                                    <td class="py-2">
                                        <select wire:model="statuses.{{ $student->id }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                            <option value="present">{{ __('Present') }}</option>
                                            <option value="absent">{{ __('Absent') }}</option>
                                            <option value="late">{{ __('Late') }}</option>
                                            <option value="excused">{{ __('Excused') }}</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif ($section_id)
                    <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">{{ __('No active students in this section.') }}</p>
                @endif

                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Save Attendance') }}</x-primary-button>
                    <a href="{{ route('academics.attendance.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
