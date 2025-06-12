<x-larascaff::modal-content 
    size="{{ $form->getModalSize() }}" 
    title="{{ $form->getTitle() }}" 
    action="{{ $action }}"
    actionLabel="{{ $form->getActionLabel() }}"
    center="{{ $center ?? false }}"
    method="POST"
>
    <div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-{{ $form->getColumns() ?? '2' }}">
        {!! $form->render() !!}
    </div>
</x-larascaff::modal-content>
