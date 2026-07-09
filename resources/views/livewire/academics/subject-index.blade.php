<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Subjects') }}
            </h2>
            @can('create', \App\Models\Subject::class)
                <a href="{{ route('academics.subjects.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Subject') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Code') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($subjects as $subject)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="subject-{{ $subject->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $subject->code }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $subject)
                                    <a href="{{ route('academics.subjects.edit', $subject) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $subject)
                                    <button wire:click="delete({{ $subject->id }})" wire:confirm="{{ __('Delete this subject?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No subjects yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
