<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Student') }}
        </h2>

        @if (session('generated_password'))
            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded-lg p-4 text-sm text-yellow-800 dark:text-yellow-200">
                {{ __('Generated password for the new account (shown only once): ') }}
                <span class="font-mono font-semibold">{{ session('generated_password') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                @include('livewire.students.student-form', ['submitLabel' => __('Update'), 'showStatus' => true])
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="font-medium text-lg text-gray-800 dark:text-gray-200 mb-4">{{ __('Documents') }}</h3>

            @can('create', \App\Models\StudentDocument::class)
                <form wire:submit="uploadDocument" class="flex gap-4 items-end mb-4">
                    <div>
                        <x-input-label for="documentType" value="{{ __('Document Type') }}" />
                        <x-text-input wire:model="documentType" id="documentType" class="mt-1 block w-full" placeholder="birth_certificate" />
                        <x-input-error :messages="$errors->get('documentType')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="documentFile" value="{{ __('File') }}" />
                        <input type="file" wire:model="documentFile" id="documentFile" class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300">
                        <x-input-error :messages="$errors->get('documentFile')" class="mt-2" />
                    </div>
                    <x-primary-button>{{ __('Upload') }}</x-primary-button>
                </form>
            @endcan

            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($documents as $document)
                    <li class="py-2 flex justify-between items-center text-sm">
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $document->type }} —
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($document->file_path) }}" target="_blank" class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('View') }}</a>
                        </span>
                        @can('delete', $document)
                            <button wire:click="deleteDocument({{ $document->id }})" wire:confirm="{{ __('Delete this document?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                        @endcan
                    </li>
                @empty
                    <li class="py-8 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No documents yet.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
