<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-1">{{ __('Students') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('All active students with class, section, and admission details.') }}</p>
            <button wire:click="exportStudents" class="inline-flex items-center px-4 py-2 bg-navy-700 dark:bg-navy-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-navy-600 dark:hover:bg-navy-500">
                {{ __('Export Excel') }}
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-1">{{ __('Fee Collection') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Payments recorded within a date range.') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
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
            <div class="flex gap-3">
                <button wire:click="exportFeeCollectionExcel" class="inline-flex items-center px-4 py-2 bg-navy-700 dark:bg-navy-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-navy-600 dark:hover:bg-navy-500">
                    {{ __('Export Excel') }}
                </button>
                <button wire:click="exportFeeCollectionPdf" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700">
                    {{ __('Export PDF') }}
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-1">{{ __('Attendance Summary') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Per-section attendance percentage for the date range above.') }}</p>
            <button wire:click="exportAttendanceSummary" class="inline-flex items-center px-4 py-2 bg-navy-700 dark:bg-navy-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-navy-600 dark:hover:bg-navy-500">
                {{ __('Export Excel') }}
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-1">{{ __('Report Card') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Printable exam result summary for a single student.') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <x-input-label for="student_id" value="{{ __('Student') }}" />
                    <select wire:model="student_id" id="student_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select student') }}</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->admission_no }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="exam_id" value="{{ __('Exam') }}" />
                    <select wire:model="exam_id" id="exam_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select exam') }}</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('exam_id')" class="mt-2" />
                </div>
            </div>
            <button wire:click="downloadReportCard" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700">
                {{ __('Download PDF') }}
            </button>
        </div>
    </div>
</div>
