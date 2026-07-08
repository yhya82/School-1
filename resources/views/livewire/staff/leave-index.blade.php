<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leave Requests') }}
            </h2>
            @can('create', \App\Models\Leave::class)
                <a href="{{ route('staff.leaves.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Leave') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Staff Member') }}</th>
                        <th class="px-6 py-3">{{ __('Type') }}</th>
                        <th class="px-6 py-3">{{ __('Dates') }}</th>
                        <th class="px-6 py-3">{{ __('Status') }}</th>
                        <th class="px-6 py-3">{{ __('Approved By') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($leaves as $leave)
                        <tr wire:key="leave-{{ $leave->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $leave->staff->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($leave->leave_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->start_date->format('Y-m-d') }} – {{ $leave->end_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($leave->status) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->approver?->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $leave)
                                    <a href="{{ route('staff.leaves.edit', $leave) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $leave)
                                    <button wire:click="delete({{ $leave->id }})" wire:confirm="{{ __('Delete this leave record?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No leave records yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
