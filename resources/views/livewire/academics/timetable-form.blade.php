<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="section_id" value="{{ __('Section') }}" />
            <select wire:model="section_id" id="section_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="">{{ __('Select section') }}</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->schoolClass->name }} - {{ $section->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('section_id')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="class_subject_id" value="{{ __('Class Subject') }}" />
            <select wire:model="class_subject_id" id="class_subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="">{{ __('Select class subject') }}</option>
                @foreach ($classSubjects as $classSubject)
                    <option value="{{ $classSubject->id }}">{{ $classSubject->schoolClass->name }} - {{ $classSubject->subject->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('class_subject_id')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="day_of_week" value="{{ __('Day') }}" />
            <select wire:model="day_of_week" id="day_of_week" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="1">{{ __('Monday') }}</option>
                <option value="2">{{ __('Tuesday') }}</option>
                <option value="3">{{ __('Wednesday') }}</option>
                <option value="4">{{ __('Thursday') }}</option>
                <option value="5">{{ __('Friday') }}</option>
                <option value="6">{{ __('Saturday') }}</option>
                <option value="7">{{ __('Sunday') }}</option>
            </select>
            <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="room" value="{{ __('Room') }}" />
            <x-text-input wire:model="room" id="room" class="mt-1 block w-full" placeholder="Room 12" />
            <x-input-error :messages="$errors->get('room')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="start_time" value="{{ __('Start Time') }}" />
            <x-text-input wire:model="start_time" id="start_time" type="time" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="end_time" value="{{ __('End Time') }}" />
            <x-text-input wire:model="end_time" id="end_time" type="time" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('academics.timetable.index') }}" wire:navigate>
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
        </a>
    </div>
</div>
