import Choices, { Choice, Options } from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
import '../../../scss/components/_choices.scss';

type Config = {
    serverSide?: boolean;
    value?: string | [];
    options?: Array<{ valu: string; label: string; selected: boolean; disabled: boolean }>;
    depend?: boolean;
    dependValue?: string;
    dependColumn?: string;
    columnLabel?: string;
    columnValue?: string;
    url?: string;
    modifyQuery?: string;
    limit?: number;
};

const initSelect = (config: Partial<Options> & Config) => {
    return {
        select: null,
        value: config.value ?? [],
        serverSide: config.serverSide ?? false,
        options: config.options ?? [],
        url: new URL(window.location.origin + '/options'),
        init: async function () {
            this.select = new Choices(this.$refs.input, {
                removeItemButton: true,
                searchPlaceholderValue: 'Start typing to search...',
                searchEnabled: true,
                noChoicesText: 'Nothing to choose..',
                noResultsText: 'Not found...',
                itemSelectText: '',
                searchResultLimit: config.limit ?? 20,
                duplicateItemsAllowed: false,
                delimiter: ',',
                allowHTML: false,
                addItemText: (value) => {
                    return `Press Enter to add "${value}"`;
                },
                shouldSort: false,
                searchFields: ['label', 'value'],
            });

            this.select.dependValue = config.dependValue;

            this.select.refreshChoice = async (dependValue: string | null = null, withInitialOptions?: boolean) => {
                this.select.clearStore();
                this.select.clearChoices();

                if (withInitialOptions) {
                    this.select.setChoices(this.options);
                }
                if (!dependValue || dependValue == this.select.dependValue) {
                    this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                    return;
                }
                if (dependValue == this.select.dependValue) {
                    this.select.dependValue = null;
                }
                this.select.dependValue = dependValue;
                this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>Loading...</div>`;

                if (this.serverSide) {
                    this.url.searchParams.set('serverSide', this.serverSide);
                }
                if (config.modifyQuery) {
                    this.url.searchParams.set('modifyQuery', config.modifyQuery);
                }
                if (config.dependColumn) {
                    this.url.searchParams.set('dependColumn', config.dependColumn);
                }
                if (config.columnLabel) {
                    this.url.searchParams.set('columnLabel', config.columnLabel);
                }
                if (config.columnValue) {
                    this.url.searchParams.set('columnValue', config.columnValue);
                }
                if (config.limit) {
                    this.url.searchParams.set('limit', config.limit);
                }

                this.url.searchParams.delete('value');
                this.url.searchParams.set('dependValue', dependValue);

                const req = await (await fetch(this.url)).json();
                this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                if (req.length) {
                    this.select.setChoices(req);
                }
            };

            this.select.setChoices(this.options);

            if (this.serverSide) {
                this.url.searchParams.set('serverSide', this.serverSide);
                if (config.dependColumn) {
                    this.url.searchParams.set('dependColumn', config.dependColumn);
                }
                if (config.modifyQuery) {
                    this.url.searchParams.set('modifyQuery', config.modifyQuery);
                }
                if (config.columnLabel) {
                    this.url.searchParams.set('columnLabel', config.columnLabel);
                }
                if (config.columnValue) {
                    this.url.searchParams.set('columnValue', config.columnValue);
                }
                if (config.limit) {
                    this.url.searchParams.set('limit', config.limit);
                }

                if (this.value) {
                    this.url.searchParams.set('value', this.value);
                }
                if (this.select.dependValue) {
                    this.url.searchParams.set('dependValue', this.select.dependValue);
                }

                // if (this.select._isSelectMultipleElement) {
                //     this.select.input.element.setAttribute('placeholder', 'Loading...')
                // } else {
                //     this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>Loading...</div>`
                // }

                // add search event listener
                this.select.passedElement.element.addEventListener(
                    'search',
                    window['Alpine'].debounce(async (e: any) => {
                        this.select.clearChoices();
                        if (config.depend && !this.select.dependValue) {
                            return;
                        }

                        await this.select.setChoices([
                            {
                                label: 'Loading...',
                                value: '',
                                disabled: true,
                            },
                        ]);

                        this.url.searchParams.set('search', e.detail.value);

                        const req = await (await fetch(this.url.href)).json();
                        this.select.clearChoices();
                        if (req.length) {
                            this.select.setChoices(req);
                        }
                    }, 500),
                );
            }

            if (this.select._isSelectElement) {
                this.value = this.options.filter((item: Choice) => item.selected);
                if (!this.value.length && this.select._isSelectOneElement) {
                    this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                }

                this.select.passedElement.element.addEventListener('showDropdown', async () => {
                    //
                });

                this.select.passedElement.element.addEventListener('removeItem', async () => {
                    if (typeof this.select.getValue(true) == 'undefined') {
                        this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                    }
                });
            }

            if (!window['Select']) {
                window['Select'] = {};
            }
            window['Select'][this.$refs.input.name ?? this.$refs.input.id] = this.select;
        },
    };
};

window['initSelect'] = initSelect;
export default initSelect;
