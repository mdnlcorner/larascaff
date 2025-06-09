/* eslint-disable @typescript-eslint/no-empty-function */
import { twMerge } from 'tailwind-merge';
import instances from '../../dom/instances';
import type { EventListenerInstance, InstanceOptions } from '../../dom/types';
import { ModalInterface } from './interface';
import type { ModalOptions } from './types';

const Default: ModalOptions = {
    placement: 'top-center',
    backdrop: 'dynamic',
    closable: true,
    onHide: () => {},
    onShow: () => {},
    onToggle: () => {},
};

const DefaultInstanceOptions: InstanceOptions = {
    id: undefined,
    override: true,
};

class Modal implements ModalInterface {
    _instanceId: string = '';
    _targetEl: HTMLElement;
    _options: ModalOptions;
    _isHidden: boolean;
    _backdropEl: HTMLElement | undefined;
    _clickOutsideEventListener: (e: MouseEvent) => void = () => {};
    _keydownEventListener: (e: KeyboardEvent) => void = () => {};
    _eventListenerInstances: EventListenerInstance[] = [];
    _initialized: boolean;
    _content: HTMLElement | undefined;
    _shownModal: Event | undefined;
    _hiddenModal: Event | undefined;

    constructor(targetEl: HTMLElement, options: ModalOptions = Default, instanceOptions: InstanceOptions = DefaultInstanceOptions) {
        this._instanceId = instanceOptions.id ? instanceOptions.id : targetEl.id;
        this._targetEl = targetEl;
        this._options = { ...Default, ...options };
        this._isHidden = true;
        this._initialized = false;

        if (this._targetEl.dataset.modalBackdrop == 'static') {
            this._options.backdrop = 'static';
        }

        this.init();
        instances.addInstance('Modal', this, this._instanceId, instanceOptions.override);
    }

    init() {
        if (this._targetEl && !this._initialized) {
            this._getPlacementClasses().map((c) => {
                this._targetEl?.classList.add(c);
            });
            this._initialized = true;
            // this._content = this._targetEl.querySelector('[data-modal-content]')

            // // handle close
            // this._content?.querySelectorAll('[data-modal-hide]').forEach(elem => {
            //     elem.addEventListener('click', e => {
            //         this.hide()
            //     })
            // })

            this._shownModal = new Event('shownModal', {
                bubbles: true,
                cancelable: false,
            });
            this._hiddenModal = new Event('hiddenModal', {
                bubbles: true,
                cancelable: false,
            });
        }
    }

    destroy() {
        if (this._initialized) {
            this.removeAllEventListenerInstances();
            this._destroyBackdropEl();
            this._initialized = false;
        }
    }

    removeInstance() {
        instances.removeInstance('Modal', this._instanceId);
    }

    destroyAndRemoveInstance() {
        this.destroy();
        this.removeInstance();
    }

    _destroyBackdropEl() {
        if (!this._isHidden) {
            const backdrop = document.querySelector('[modal-backdrop]');
            backdrop?.setAttribute('class', twMerge(backdrop.classList.value, 'opacity-0'));
            setTimeout(() => {
                backdrop?.remove();
            }, 200);
        }
    }

    _setupModalCloseEventListeners() {
        if (this._options.backdrop === 'dynamic') {
            this._clickOutsideEventListener = (ev: MouseEvent) => {
                ev.target && this._handleOutsideClick(ev.target);
            };

            this._targetEl?.addEventListener('click', this._clickOutsideEventListener, true);
        } else {
            // static
            this._targetEl?.addEventListener('click', (e) => {
                if (e.target === this._targetEl) {
                    this._content?.setAttribute('class', twMerge(this._content.classList.value, 'scale-90'));
                    setTimeout(() => {
                        this._content?.setAttribute('class', twMerge(this._content.classList.value, 'duration-500 scale-100'));
                    }, 300);
                }
            });
        }

        this._keydownEventListener = (ev: KeyboardEvent) => {
            if (ev.key === 'Escape') {
                if (this._options.backdrop == 'dynamic') {
                    this.hide();
                } else {
                    this._content?.setAttribute('class', twMerge(this._content.classList.value, 'scale-90'));
                    setTimeout(() => {
                        this._content?.setAttribute('class', twMerge(this._content.classList.value, 'duration-500 scale-100'));
                    }, 300);
                }
            }
        };
        document.body.addEventListener('keydown', this._keydownEventListener, true);
    }

    _removeModalCloseEventListeners() {
        if (this._options.backdrop === 'dynamic') {
            this._targetEl?.removeEventListener('click', this._clickOutsideEventListener, true);
        }
        document.body.removeEventListener('keydown', this._keydownEventListener, true);
    }

    _handleOutsideClick(target: EventTarget) {
        if (target === this._targetEl || (target === this._backdropEl && this.isVisible())) {
            this.hide();
        }
    }

    _getPlacementClasses() {
        switch (this._options.placement) {
            // top
            case 'top-left':
                return ['justify-start', 'items-start'];
            case 'top-center':
                return ['justify-center', 'items-start'];
            case 'top-right':
                return ['justify-end', 'items-start'];

            // center
            case 'center-left':
                return ['justify-start', 'items-center'];
            case 'center':
                return ['justify-center', 'items-center'];
            case 'center-right':
                return ['justify-end', 'items-center'];

            // bottom
            case 'bottom-left':
                return ['justify-start', 'items-end'];
            case 'bottom-center':
                return ['justify-center', 'items-end'];
            case 'bottom-right':
                return ['justify-end', 'items-end'];

            default:
                return ['justify-center', 'items-start'];
        }
    }

    toggle() {
        if (this._isHidden) {
            this.show();
        } else {
            this.hide();
        }
    }

    show() {
        if (this._isHidden) {
            this._targetEl?.setAttribute('class', twMerge(this._targetEl.classList.value, 'block'));
            this._content = this._targetEl?.querySelector('[data-modal-content]') as HTMLElement;

            // handle close
            this._content?.querySelectorAll('[data-modal-hide]').forEach((elem) => {
                elem.addEventListener('click', (e) => {
                    this.hide();
                });
            });

            setTimeout(() => {
                this._targetEl?.setAttribute('class', twMerge(this._targetEl.classList.value, 'opacity-100'));
                this._content?.setAttribute('class', twMerge(this._content.classList.value, 'scale-100 opacity-100'));
            }, 100);

            this._targetEl?.setAttribute('aria-modal', 'true');
            this._targetEl?.setAttribute('role', 'dialog');
            this._targetEl?.removeAttribute('aria-hidden');
            // this._createBackdrop();
            this._isHidden = false;

            // Add keyboard event listener to the document
            if (this._options.closable) {
                this._setupModalCloseEventListeners();
            }

            // prevent body scroll
            document.body.classList.add('overflow-hidden');
            // this._options?.onShow(this)
            if (this._options.onShow) {
                this._options?.onShow(this);
            }

            this._shownModal && this._targetEl?.dispatchEvent(this._shownModal);
        }
    }

    hide() {
        if (this.isVisible()) {
            this._content?.setAttribute('class', twMerge(this._content.classList.value, 'scale-90 opacity-0'));
            // this._destroyBackdropEl();
            setTimeout(() => {
                this._targetEl?.setAttribute('class', twMerge(this._targetEl.classList.value, 'hidden'));
                this._isHidden = true;
                this._targetEl?.setAttribute('aria-hidden', 'true');
                this._targetEl?.removeAttribute('aria-modal');
                this._targetEl?.removeAttribute('role');
                // re-apply body scroll
                document.body.classList.remove('overflow-hidden');

                this._options.closable && this._removeModalCloseEventListeners();
                this._options.onHide && this._options.onHide(this);
                this._hiddenModal && this._targetEl?.dispatchEvent(this._hiddenModal);
            }, 200);
        }
    }

    isVisible() {
        return !this._isHidden;
    }

    isHidden() {
        return this._isHidden;
    }

    addEventListenerInstance(element: HTMLElement, type: string, handler: EventListenerOrEventListenerObject) {
        this._eventListenerInstances.push({
            element: element,
            type: type,
            handler: handler,
        });
    }

    removeAllEventListenerInstances() {
        this._eventListenerInstances.map((eventListenerInstance) => {
            eventListenerInstance.element.removeEventListener(eventListenerInstance.type, eventListenerInstance.handler);
        });
        this._eventListenerInstances = [];
    }

    getAllEventListenerInstances() {
        return this._eventListenerInstances;
    }

    updateOnShow(callback: (e: ModalInterface) => void) {
        this._options.onShow = callback;
    }

    updateOnHide(callback: (e: ModalInterface) => void) {
        this._options.onHide = callback;
    }

    updateOnToggle(callback: () => void) {
        this._options.onToggle = callback;
    }
}

export function initModals() {
    // toggle modal visibility
    document.querySelectorAll('[data-modal-toggle]').forEach(($triggerEl) => {
        const modalId = ($triggerEl as HTMLDivElement).dataset['modalTarget'];
        const $modalEl = modalId && document.getElementById(modalId);

        // init modal
        if ($modalEl) {
            const placement = $modalEl.getAttribute('data-modal-placement');
            const backdrop = $modalEl.getAttribute('data-modal-backdrop');
            new Modal(
                $modalEl as HTMLElement,
                {
                    placement: placement ? placement : Default.placement,
                    backdrop: backdrop ? backdrop : Default.backdrop,
                } as ModalOptions,
            );
        } else {
            console.error(
                `Modal with id ${modalId} does not exist. Are you sure that the data-modal-target attribute points to the correct modal id?.`,
            );
        }

        // add event
        if (modalId) {
            const modal: ModalInterface = instances.getInstance('Modal', modalId);

            if (modal) {
                const toggleModal = () => {
                    modal.toggle();
                };
                $triggerEl.addEventListener('click', toggleModal);
                modal.addEventListenerInstance($triggerEl as HTMLElement, 'click', toggleModal);
            } else {
                console.error(`Modal with id ${modalId} has not been initialized. Please initialize it using the data-modal-target attribute.`);
            }
        } else {
            console.error(
                `Modal with id ${modalId} does not exist. Are you sure that the data-modal-toggle attribute points to the correct modal id?`,
            );
        }
    });
}

if (typeof window !== 'undefined') {
    window['Modal'] = Modal;
    window['initModals'] = initModals;
}

export default Modal;
