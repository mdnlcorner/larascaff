import type { Placement } from '@popperjs/core';
import { PopoverInterface } from './interface';

export declare type PopoverTriggerType = 'click' | 'hover' | 'none';

export declare type PopoverTriggerEventTypes = {
    showEvents: string[];
    hideEvents: string[];
};

export declare type PopoverOptions = {
    placement?: Placement;
    offset?: number;
    triggerType?: PopoverTriggerType;
    onShow?: (tooltip: PopoverInterface) => void;
    onHide?: (tooltip: PopoverInterface) => void;
    onToggle?: (tooltip: PopoverInterface) => void;
};
