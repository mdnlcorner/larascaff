@props(['title' => 'Modal Title', 'method' => 'POST', 'action' => null, 'actionLabel' => 'Save', 'size' => 'md', 'center' => false])

<div {{ $attributes->twMerge('flex px-2 md:px-0 mx-auto my-6 w-full', $size == 'full' ? 'my-0' : ($size == 'sm' ? 'max-w-sm' : ($size == 'lg' ? 'max-w-5xl' : ($size == 'xl' ? 'max-w-7xl' : 'max-w-2xl'))), $center ? 'items-center min-h-screen pointer-events-none my-0' : '') }}>
    <div data-modal-content class="relative transition-all duration-500 w-full scale-90 bg-white {{ $size == 'full' ? 'rounded-none min-h-screen flex flex-col' : 'rounded-lg' }} shadow opacity-0 pointer-events-auto dark:bg-dark-900">
        @if ($action)
            <form id="formAction" action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method($method)
        @endif
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-dark-800">
            <h3 class="text-xl font-semibold text-dark-900 dark:text-white">
                {{ $title }}
            </h3>
            <button type="button" class="inline-flex items-center justify-center text-sm bg-transparent rounded-lg w-7 h-7 text-dark-400 hover:bg-dark-200 hover:text-dark-900 ms-auto dark:hover:bg-dark-800 dark:hover:text-white" data-modal-hide>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <!-- Modal body -->
        <div data-modal-body class="flex-grow p-4">
            {{ $slot }}
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end gap-3 p-4 border-t rounded-b border-dark-200 dark:border-dark-800">
            @if ($action)
                <x-larascaff::button type="submit" class="btn-save">{{ $actionLabel }}</x-larascaff::button>
            @endif
            <x-larascaff::button data-modal-hide type="button" variant="secondary">Close</x-larascaff::button>
        </div>
        @if ($action)
            </form>
        @endif
    </div>
</div>
