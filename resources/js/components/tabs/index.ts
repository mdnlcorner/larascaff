/* eslint-disable @typescript-eslint/no-empty-function */
import { twMerge } from 'tailwind-merge';
import instances from '../../dom/instances';
import type { InstanceOptions } from '../../dom/types';
import { TabsInterface } from './interface';
import type { TabItem, TabsOptions } from './types';

const Default: TabsOptions = {
    defaultTabId: undefined,
    activeClasses: 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500',
    inactiveClasses:
        'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
    onShow: () => {},
};

const DefaultInstanceOptions: InstanceOptions = {
    id: undefined,
    override: true,
};

class Tabs implements TabsInterface {
    _instanceId: string;
    _tabsEl: HTMLElement;
    _items: TabItem[];
    _activeTab: TabItem;
    _options: TabsOptions;
    _initialized: boolean;

    constructor(
        tabsEl: HTMLElement | null = null,
        items: TabItem[] = [],
        options: TabsOptions = Default,
        instanceOptions: InstanceOptions = DefaultInstanceOptions,
    ) {
        this._instanceId = instanceOptions.id ? instanceOptions.id : (tabsEl?.id ?? '');
        this._tabsEl = tabsEl as HTMLElement;
        this._items = items;
        this._activeTab = options ? this.getTab(options.defaultTabId ?? '') : options;
        this._options = { ...Default, ...options };
        this._initialized = false;
        this.init();
        instances.addInstance('Tabs', this, this._tabsEl.id, true);
        instances.addInstance('Tabs', this, this._instanceId, instanceOptions.override);
    }

    init() {
        if (this._items.length && !this._initialized) {
            // set the first tab as active if not set by explicitly
            if (!this._activeTab) {
                this.setActiveTab(this._items[0]);
            }

            // force show the first default tab
            this.show(this._activeTab.id, true);

            // show tab content based on click
            this._items.map((tab) => {
                tab.triggerEl.addEventListener('click', (event) => {
                    event.preventDefault();
                    this.show(tab.id);
                });
            });
        }
    }

    destroy() {
        if (this._initialized) {
            this._initialized = false;
        }
    }

    removeInstance() {
        this.destroy();
        instances.removeInstance('Tabs', this._instanceId);
    }

    destroyAndRemoveInstance() {
        this.destroy();
        this.removeInstance();
    }

    getActiveTab() {
        return this._activeTab;
    }

    setActiveTab(tab: TabItem) {
        this._activeTab = tab;
    }

    getTab(id: string) {
        return this._items.filter((t) => t.id === id)[0];
    }

    show(id: string, forceShow = false) {
        const tab = this.getTab(id);

        // don't do anything if already active
        if (tab === this._activeTab && !forceShow) {
            return;
        }

        // hide other tabs
        this._items.map((t: TabItem) => {
            if (t !== tab) {
                t.triggerEl.classList.remove(...(this._options?.activeClasses?.split(' ') ?? []));
                t.triggerEl.classList.add(...(this._options?.inactiveClasses?.split(' ') ?? []));
                t.triggerEl.setAttribute('class', twMerge(t.triggerEl.classList.value, this._options.inactiveClasses));
                t.targetEl.setAttribute('class', twMerge(t.targetEl.classList.value, 'hidden opacity-0 -translate-y-5'));
                t.triggerEl.setAttribute('aria-selected', 'false');
            }
        });

        // show active tab
        tab.triggerEl.classList.remove(...(this._options?.inactiveClasses?.split(' ') ?? []));
        // tab.triggerEl.classList.add(...this._options?.activeClasses?.split(' ')?? []);
        tab.triggerEl.setAttribute('class', twMerge(tab.triggerEl.classList.value, this._options.activeClasses));
        tab.triggerEl.setAttribute('aria-selected', 'true');
        tab.targetEl.classList.remove('hidden');
        setTimeout(() => {
            tab.targetEl.setAttribute('class', twMerge(tab.targetEl.classList.value, 'opacity-100 translate-y-0'));
        }, 0);

        this.setActiveTab(tab);

        // callback function
        this._options.onShow && this._options.onShow(this, tab);
    }

    updateOnShow(callback: () => void) {
        this._options.onShow = callback;
    }
}

export function initTabs() {
    document.querySelectorAll('[data-tabs-toggle]').forEach(($parentEl) => {
        const tabItems: TabItem[] = [];
        const activeClasses = $parentEl.getAttribute('data-tabs-active-classes');
        const inactiveClasses = $parentEl.getAttribute('data-tabs-inactive-classes');
        let defaultTabId: string | undefined = undefined;
        $parentEl.querySelectorAll('[role="tab"]').forEach(($triggerEl) => {
            const isActive = $triggerEl.getAttribute('aria-selected') === 'true';
            const id = $triggerEl.getAttribute('data-tabs-target') ?? '';
            const tab: TabItem = {
                id: id,
                triggerEl: $triggerEl,
                targetEl: document.querySelector(id) as Element,
            };
            tabItems.push(tab);

            if (isActive) {
                defaultTabId = tab.id;
            }
        });

        new Tabs($parentEl as HTMLElement, tabItems, {
            defaultTabId: defaultTabId,
            activeClasses: activeClasses ? activeClasses : Default.activeClasses,
            inactiveClasses: inactiveClasses ? inactiveClasses : Default.inactiveClasses,
        } as TabsOptions);
    });
}

if (typeof window !== 'undefined') {
    window['Tabs'] = Tabs;
    window['initTabs'] = initTabs;
}

export default Tabs;
