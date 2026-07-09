<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Guardians') }}
            </h2>
            @can('create', \App\Models\Guardian::class)
                <a href="{{ route('students.guardians.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Guardian') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Phone') }}</th>
                        <th class="px-6 py-3">{{ __('Relationship') }}</th>
                        <th class="px-6 py-3">{{ __('Children') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($guardians as $guardian)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="guardian-{{ $guardian->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $guardian->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $guardian->phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $guardian->relationship }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $guardian->students_count }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $guardian)
                                    <a href="{{ route('students.guardians.edit', $guardian) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $guardian)
                                    <button wire:click="delete({{ $guardian->id }})" wire:confirm="{{ __('Delete this guardian?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No guardians yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
