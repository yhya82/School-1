<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Exams') }}
            </h2>
            @can('create', \App\Models\Exam::class)
                <a href="{{ route('academics.exams.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Exam') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Academic Year') }}</th>
                        <th class="px-6 py-3">{{ __('Dates') }}</th>
                        <th class="px-6 py-3">{{ __('Subjects') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($exams as $exam)
                        <tr wire:key="exam-{{ $exam->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $exam->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $exam->academicYear->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $exam->start_date->format('Y-m-d') }} – {{ $exam->end_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $exam->exam_subjects_count }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $exam)
                                    <a href="{{ route('academics.exams.edit', $exam) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $exam)
                                    <button wire:click="delete({{ $exam->id }})" wire:confirm="{{ __('Delete this exam? All exam subjects and results under it will also be deleted.') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No exams yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
