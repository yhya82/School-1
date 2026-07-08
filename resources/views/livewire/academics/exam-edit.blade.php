<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Exam') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                <div>
                    <x-input-label for="academic_year_id" value="{{ __('Academic Year') }}" />
                    <select wire:model="academic_year_id" id="academic_year_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="name" value="{{ __('Name') }}" />
                    <x-text-input wire:model="name" id="name" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
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
                </div>
                <div class="mt-6 flex gap-3">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('academics.exams.index') }}" wire:navigate>
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="font-medium text-lg text-gray-800 dark:text-gray-200 mb-4">{{ __('Exam Subjects') }}</h3>

            @can('create', \App\Models\ExamSubject::class)
                <form wire:submit="saveExamSubject" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end mb-4">
                    <div>
                        <x-input-label for="class_subject_id" value="{{ __('Class Subject') }}" />
                        <select wire:model="class_subject_id" id="class_subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">{{ __('Select') }}</option>
                            @foreach ($classSubjects as $classSubject)
                                <option value="{{ $classSubject->id }}">{{ $classSubject->schoolClass->name }} - {{ $classSubject->subject->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('class_subject_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="exam_date" value="{{ __('Exam Date') }}" />
                        <x-text-input wire:model="exam_date" id="exam_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('exam_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="max_marks" value="{{ __('Max Marks') }}" />
                        <x-text-input wire:model="max_marks" id="max_marks" type="number" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('max_marks')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="pass_marks" value="{{ __('Pass Marks') }}" />
                        <x-text-input wire:model="pass_marks" id="pass_marks" type="number" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('pass_marks')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-4 flex gap-3">
                        <x-primary-button>{{ $editingExamSubjectId ? __('Update') : __('Add Subject') }}</x-primary-button>
                        @if ($editingExamSubjectId)
                            <x-secondary-button type="button" wire:click="resetExamSubjectForm">{{ __('Cancel') }}</x-secondary-button>
                        @endif
                    </div>
                </form>
            @endcan

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="py-2">{{ __('Subject') }}</th>
                        <th class="py-2">{{ __('Exam Date') }}</th>
                        <th class="py-2">{{ __('Max/Pass') }}</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($examSubjects as $examSubject)
                        <tr wire:key="exam-subject-{{ $examSubject->id }}">
                            <td class="py-2 text-sm text-gray-900 dark:text-gray-100">{{ $examSubject->classSubject->schoolClass->name }} - {{ $examSubject->classSubject->subject->name }}</td>
                            <td class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ $examSubject->exam_date?->format('Y-m-d') ?? '—' }}</td>
                            <td class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ $examSubject->max_marks }} / {{ $examSubject->pass_marks }}</td>
                            <td class="py-2 text-sm text-right space-x-2">
                                <a href="{{ route('academics.exam-results.enter', $examSubject) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Enter Results') }}</a>
                                @can('update', $examSubject)
                                    <button wire:click="editExamSubject({{ $examSubject->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</button>
                                @endcan
                                @can('delete', $examSubject)
                                    <button wire:click="deleteExamSubject({{ $examSubject->id }})" wire:confirm="{{ __('Remove this subject from the exam?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No subjects added to this exam yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
