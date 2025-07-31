@props([
    'center' => false,
    'actions',
    'form'
])
<div {{ $attributes->twMerge('flex px-2 md:px-0 mx-auto my-6 w-full', $actions['modalSize'] == 'full' ? 'my-0' : ($actions['modalSize'] == 'sm' ? 'max-w-sm' : ($actions['modalSize'] == 'lg' ? 'max-w-5xl' : ($actions['modalSize'] == 'xl' ? 'max-w-7xl' : 'max-w-2xl'))), $center ? 'items-center min-h-screen pointer-events-none my-0' : '') }}>
    <div data-modal-content class="relative transition-all duration-500 w-full scale-90 bg-white {{ $actions['modalSize'] == 'full' ? 'rounded-none min-h-screen flex flex-col' : 'rounded-lg' }} shadow opacity-0 pointer-events-auto dark:bg-dark-900">
        @if ($actions['action'])
            <form id="formAction" action="{{ $actions['action'] ? url('handler') : null }}" method="POST" enctype="multipart/form-data">
        @endif
        @csrf
        @method($actions['method'])
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-dark-800">
            <h3 class="text-xl font-semibold text-dark-900 dark:text-white">
                {{ $actions['modalTitle'] }}
            </h3>
            <button type="button" class="inline-flex items-center justify-center text-sm bg-transparent rounded-lg w-7 h-7 text-dark-400 hover:bg-dark-200 hover:text-dark-900 ms-auto dark:hover:bg-dark-800 dark:hover:text-white" data-modal-hide>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <div data-modal-body class="flex-grow p-4">
            {{ $slot }}
        </div>
        <div class="flex items-center justify-end gap-3 p-4 border-t rounded-b border-dark-200 dark:border-dark-800">
            @if($actions['action'])
                <x-larascaff::button type="submit" class="btn-save">{{ $actions['modalSubmitActionLabel'] }}</x-larascaff::button>
            @endif
            <x-larascaff::button data-modal-hide type="button" variant="secondary">{{ $actions['modalCancelActionLabel'] }}</x-larascaff::button>
        </div>
        @if($actions['action'])
            </form>
        @endif
    </div>
</div>
