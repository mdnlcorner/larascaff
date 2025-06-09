import type { Instance as PopperInstance } from '@popperjs/core';
import { DropdownOptions, DropdownTriggerEventTypes, DropdownTriggerType } from './types';

export declare interface DropdownInterface {
    _targetEl: HTMLElement;
    _triggerEl: HTMLElement;
    _options: DropdownOptions;
    _visible: boolean;
    _popperInstance: PopperInstance | null;
    _initialized: boolean;
    _clickOutsideEventListener: EventListenerOrEventListenerObject;

    init(): void;
    _createPopperInstance(): PopperInstance;
    _setupEventListeners(): void;
    _setupClickOutsideListener(): void;
    _removeClickOutsideListener(): void;
    _handleClickOutside(ev: Event, targetEl: HTMLElement): void;
    _getTriggerEvents(triggerType: DropdownTriggerType): DropdownTriggerEventTypes;
    isVisible(): boolean;
    toggle(): void;
    show(): void;
    hide(): void;

    destroy(): void;
    removeInstance(): void;
    destroyAndRemoveInstance(): void;
}
