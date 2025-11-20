import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
import '../../../scss/components/_select.scss';

const initSelect = (config) => {
    return {
        select: null,
        value: config.value ?? [],
        model: config.model ?? false,
        options: config.options ?? [],
        url: new URL(`${window.location.origin}/select-options`),
        init: async function () {
            const $this = this;
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

            this.select.refreshChoice = async (dependValue = null, withInitialOptions) => {
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

                // if (this.model) {
                //     this.url.searchParams.set('model', this.model);
                // }
                if (config.module) {
                    this.url.searchParams.set('module', config.module);
                }
                if (config.dependColumn) {
                    this.url.searchParams.set('dependColumn', config.dependColumn);
                }
                // if (config.columnLabel) {
                //     this.url.searchParams.set('columnLabel', config.columnLabel);
                // }
                // if (config.columnValue) {
                //     this.url.searchParams.set('columnValue', config.columnValue);
                // }
                // if (config.limit) {
                //     this.url.searchParams.set('limit', config.limit);
                // }

                this.url.searchParams.delete('value');
                this.url.searchParams.set('dependValue', dependValue);

                const req = await (await fetch(this.url)).json();
                this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                if (req.length) {
                    this.select.setChoices(req);
                }
            };

            this.select.setChoices(this.options);

            if (this.model) {
                // this.url.searchParams.set('model', this.model);
                if (config.dependColumn) {
                    this.url.searchParams.set('dependColumn', config.dependColumn);
                }
                if (config.module) {
                    this.url.searchParams.set('module', config.module);
                }
                // if (config.columnLabel) {
                //     this.url.searchParams.set('columnLabel', config.columnLabel);
                // }
                // if (config.columnValue) {
                //     this.url.searchParams.set('columnValue', config.columnValue);
                // }
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
                    window['Alpine'].debounce(async (e) => {
                        this.select.clearChoices();
                        // if (config.depend && !this.select.dependValue) {
                        //     return;
                        // }

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
                this.value = this.options.filter((item) => item.selected);
                if (!this.value.length && this.select._isSelectOneElement) {
                    this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                }

                this.select.passedElement.element.addEventListener('showDropdown', async () => {
                    //
                });

                this.select.passedElement.element.addEventListener('removeItem', async function () {
                    if (typeof $this.select.getValue(true) == 'undefined') {
                        this.innerHTML = `<option></option>`;
                        $this.select.itemList.element.innerHTML = `<div class='choices__placeholder choices__item'>${config.placeholder ?? 'Select an option'}</div>`;
                    }
                });
            }

            Object.entries(config.dependTo).forEach(([name, event]) => {
                // @ts-ignore
                document.querySelector(`[data-input-name=${name}]`)?.addEventListener(event, function () {
                    $this.select.refreshChoice(this.value);
                });
            });

            if (!window['Select']) {
                window['Select'] = {};
            }
            window['Select'][this.$refs.input.name ?? this.$refs.input.id] = this.select;
        },
    };
};

window['initSelect'] = initSelect;
export default initSelect;
