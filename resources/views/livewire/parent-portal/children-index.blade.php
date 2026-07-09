<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Children') }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse ($children as $child)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-5">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $child->user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->schoolClass->name }} - {{ $child->section->name }} ({{ $child->admission_no }})</p>

                    <div class="mt-4 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('portal.children.attendance', $child) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Attendance') }}</a>
                        <a href="{{ route('portal.children.results', $child) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Results') }}</a>
                        <a href="{{ route('portal.children.invoices', $child) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Invoices') }}</a>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 p-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No children linked to your account yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
