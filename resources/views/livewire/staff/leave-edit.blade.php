<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Leave') }}
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save">
                @include('livewire.staff.leave-form', ['submitLabel' => __('Update'), 'showStatus' => true])
            </form>
        </div>
    </div>
</div>
