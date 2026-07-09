<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Exam Results') }} — {{ $student->user->name }}
            </h2>
            <a href="{{ route('portal.children.index') }}" wire:navigate class="text-sm text-navy-600 dark:text-navy-400 hover:underline">{{ __('Back to My Children') }}</a>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Exam') }}</th>
                        <th class="px-6 py-3">{{ __('Subject') }}</th>
                        <th class="px-6 py-3">{{ __('Marks') }}</th>
                        <th class="px-6 py-3">{{ __('Grade') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($results as $result)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="result-{{ $result->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $result->examSubject->exam->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $result->examSubject->classSubject->subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $result->marks_obtained }} / {{ $result->examSubject->max_marks }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $result->grade }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No exam results yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
