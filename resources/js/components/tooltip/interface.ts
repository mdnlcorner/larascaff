import type { Instance as PopperInstance } from '@popperjs/core';
import { TooltipOptions, TooltipTriggerEventTypes, TooltipTriggerType } from './types';

export declare interface TooltipInterface {
    _targetEl: HTMLElement | null;
    _triggerEl: HTMLElement | null;
    _options: TooltipOptions;
    _popperInstance: PopperInstance;
    _clickOutsideEventListener: EventListenerOrEventListenerObject;
    _keydownEventListener: EventListenerOrEventListenerObject;

    init(): void;
    _setupEventListeners(): void;
    _setupClickOutsideListener(): void;
    _removeClickOutsideListener(): void;
    _setupKeydownListener(): void;
    _removeKeydownListener(): void;
    _handleClickOutside(ev: Event, targetEl: HTMLElement): void;
    _getTriggerEvents(triggerType: TooltipTriggerType): TooltipTriggerEventTypes;
    isVisible(): boolean;
    show(): void;
    hide(): void;
    toggle(): void;

    destroy(): void;
    removeInstance(): void;
    destroyAndRemoveInstance(): void;
}
