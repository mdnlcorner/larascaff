import type { Placement } from '@popperjs/core';

export declare type DropdownTriggerType = 'click' | 'hover' | 'none';

export declare type DropdownTriggerEventTypes = {
    showEvents: string[];
    hideEvents: string[];
};

export declare type DropdownOptions = {
    placement?: Placement;
    triggerType?: DropdownTriggerType;
    offsetSkidding?: number;
    offsetDistance?: number;
    ignoreClickOutsideClass?: string | boolean;
    delay?: number;
    onShow?: (tooltip: any) => void;
    onHide?: (tooltip: any) => void;
    onToggle?: (tooltip: any) => void;
};
