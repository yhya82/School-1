<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enter Results') }} — {{ $examSubject->classSubject->schoolClass->name }} / {{ $examSubject->classSubject->subject->name }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Max marks') }}: {{ $examSubject->max_marks }}, {{ __('Pass marks') }}: {{ $examSubject->pass_marks }}</p>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="py-2">{{ __('Admission No.') }}</th>
                            <th class="py-2">{{ __('Student') }}</th>
                            <th class="py-2">{{ __('Marks') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="student-{{ $student->id }}">
                                <td class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ $student->admission_no }}</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-gray-100">{{ $student->user->name }}</td>
                                <td class="py-2">
                                    <x-text-input wire:model="marks.{{ $student->id }}" type="number" step="0.01" class="w-24" />
                                    <x-input-error :messages="$errors->get('marks.'.$student->id)" class="mt-1" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No active students in this class.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Save Results') }}</x-primary-button>
                    <a href="{{ route('academics.exams.edit', $examSubject->exam_id) }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
