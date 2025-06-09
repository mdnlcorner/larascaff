/* eslint-disable @typescript-eslint/no-empty-function */
import instances from '../../dom/instances';
import type { InstanceOptions } from '../../dom/types';
import { AccordionInterface } from './interface';
import type { AccordionItem, AccordionOptions } from './types';

const Default: AccordionOptions = {
    alwaysOpen: false,
    onOpen: () => {},
    onClose: () => {},
    onToggle: () => {},
};

const DefaultInstanceOptions: InstanceOptions = {
    id: undefined,
    override: true,
};

const initAccordion = (config: AccordionOptions) => {
    return {
        init: function () {
            const items = [] as AccordionItem[];

            this.$refs.accordionWrapper.querySelectorAll('[data-accordion-target]').forEach(($triggerEl) => {
                if ($triggerEl.closest('[data-accordion]') === this.$refs.accordionWrapper) {
                    const item = {
                        id: $triggerEl.getAttribute('data-accordion-target'),
                        triggerEl: $triggerEl,
                        targetEl: document.querySelector($triggerEl.getAttribute('data-accordion-target') ?? ''),
                        iconEl: $triggerEl.querySelector('[data-accordion-icon]'),
                        active: $triggerEl.getAttribute('aria-expanded') === 'true' ? true : false,
                    } as AccordionItem;
                    items.push(item);
                }
            });

            new Accordion(this.$refs.accordionWrapper, items, {
                ...config,
            });
        },
    };
};

class Accordion implements AccordionInterface {
    _instanceId: string;
    _accordionEl: HTMLElement;
    _items: AccordionItem[];
    _options: AccordionOptions;
    _clickHandler: EventListenerOrEventListenerObject;
    _initialized: boolean;

    constructor(
        accordionEl: HTMLElement | null = null,
        items: AccordionItem[] = [],
        options: AccordionOptions = Default,
        instanceOptions: InstanceOptions = DefaultInstanceOptions,
    ) {
        this._instanceId = instanceOptions.id ? instanceOptions.id : (accordionEl?.id ?? '');
        this._accordionEl = accordionEl as HTMLElement;
        this._items = items;
        this._options = { ...Default, ...options };
        this._initialized = false;
        this._clickHandler = () => {};
        this.init();
        instances.addInstance('Accordion', this, this._instanceId, instanceOptions.override);
    }

    init() {
        if (this._items.length && !this._initialized) {
            // show accordion item based on click
            this._items.forEach((item) => {
                if (item.active) {
                    this.open(item.id);
                }

                const clickHandler = () => {
                    this.toggle(item.id);
                };

                item.triggerEl.addEventListener('click', clickHandler);

                // Store the clickHandler in a property of the item for removal later
                item.clickHandler = clickHandler;
            });
            this._initialized = true;
        }
    }

    destroy() {
        if (this._items.length && this._initialized) {
            this._items.forEach((item) => {
                item.triggerEl.removeEventListener('click', item.clickHandler ?? function () {});

                // Clean up by deleting the clickHandler property from the item
                delete item.clickHandler;
            });
            this._initialized = false;
        }
    }

    removeInstance() {
        instances.removeInstance('Accordion', this._instanceId);
    }

    destroyAndRemoveInstance() {
        this.destroy();
        this.removeInstance();
    }

    getItem(id: string) {
        return this._items.filter((item) => item.id === id)[0];
    }

    open(id: string) {
        const item = this.getItem(id);

        // hide other accordions if always open
        if (!this._options.alwaysOpen) {
            this._items.map((i) => {
                if (i !== item) {
                    i.triggerEl.classList.remove('border-b');
                    i.targetEl.classList.add('hidden');
                    i.iconEl?.classList.remove('rotate-90');

                    i.triggerEl.setAttribute('aria-expanded', 'false');
                    i.active = false;
                }
            });
        }

        // show active item
        item.triggerEl.classList.add('border-b');
        item.triggerEl.setAttribute('aria-expanded', 'true');
        item.targetEl.classList.add('grid');
        item.targetEl.classList.remove('hidden');

        item.active = true;

        // rotate icon if set
        if (item.iconEl) {
            item.iconEl.classList.add('rotate-90');
        }

        // callback function
        this._options.onOpen && this._options.onOpen(this, item);
    }

    toggle(id: string) {
        const item = this.getItem(id);

        if (item.active) {
            this.close(id);
        } else {
            this.open(id);
        }

        // callback function
        this._options.onToggle && this._options.onToggle(this, item);
    }

    close(id: string) {
        const item = this.getItem(id);

        item.triggerEl.classList.remove('border-b');
        item.targetEl.classList.toggle('hidden');
        item.triggerEl.setAttribute('aria-expanded', 'false');
        item.active = false;

        // rotate icon if set
        if (item.iconEl) {
            item.iconEl.classList.remove('rotate-90');
        }

        // callback function
        this._options.onClose && this._options.onClose(this, item);
    }

    updateOnOpen(callback: () => void) {
        this._options.onOpen = callback;
    }

    updateOnClose(callback: () => void) {
        this._options.onClose = callback;
    }

    updateOnToggle(callback: () => void) {
        this._options.onToggle = callback;
    }
}

if (typeof window !== 'undefined') {
    window['Accordion'] = Accordion;
    window['initAccordion'] = initAccordion;
}
export default initAccordion;
