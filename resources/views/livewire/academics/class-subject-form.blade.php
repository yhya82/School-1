<div>
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
    <div class="mt-4">
        <x-input-label for="subject_id" value="{{ __('Subject') }}" />
        <select wire:model="subject_id" id="subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Select subject') }}</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
    </div>
    <div class="mt-4">
        <x-input-label for="teacher_id" value="{{ __('Teacher') }}" />
        <select wire:model="teacher_id" id="teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Unassigned') }}</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('academics.class-subjects.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
