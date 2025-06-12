import { TOptionToast, TPositionToast } from './types';

class Toast {
    _elements: TPositionToast<Array<Element>> = {};
    _wrapper: TPositionToast<Element> = {};
    _options: TOptionToast = { body: '' };
    _icon = {
        error: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,
        success: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>`,
        info: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle w-4 h-4"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`,
        warning: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle w-4 h-4"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`,
    };

    success(options: typeof this._options) {
        this._options.type = 'success';
        this.show(options);
    }

    error(options: typeof this._options) {
        this._options.type = 'error';
        this.show(options);
    }

    warning(options: typeof this._options) {
        this._options.type = 'warning';
        this.show(options);
    }

    info(options: typeof this._options) {
        this._options.type = 'info';
        this.show(options);
    }

    show(options: typeof this._options) {
        const toastEl = document.createElement('div');
        toastEl.setAttribute('role', 'alert');
        toastEl.classList.add('toast');

        this._options = { ...this._options, ...options, position: options.position ?? 'top-right' };

        if (this._options.type) {
            toastEl.classList.add('toast-' + this._options.type);
            const icon = document.createElement('div');
            icon.classList.add('toast-icon');

            icon.innerHTML = this._icon[this._options.type];
            toastEl.appendChild(icon);
            // reset type
            this._options.type = 'default';
        }

        const content = document.createElement('div');
        content.classList.add('ml-3');
        if (options.title) {
            const title = document.createElement('div');
            title.setAttribute('class', 'toast-title');
            title.innerText = options.title;
            content.appendChild(title);
        }
        const _body = document.createElement('div');
        _body.setAttribute('class', 'toast-body');
        _body.innerText = options.body;

        content.appendChild(_body);
        toastEl.appendChild(content);

        if (options.closeable) {
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.classList.add('toast-close');
            closeButton.innerHTML = `<span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>`;
            closeButton.addEventListener('click', (e) => {
                toastEl.remove();
            });
            toastEl.appendChild(closeButton);
        }

        ['top-right', 'top-center', 'top-left', 'bottom-right', 'bottom-center', 'bottom-left'].forEach((item) => {
            if (this._options.position == item) {
                if (!document.querySelector(`.toast-wrapper.toast-${item}`)) {
                    this._wrapper[item] = document.createElement('div') as Element;
                    this._wrapper[item]?.setAttribute('class', `toast-wrapper toast-${item}`);
                    this._wrapper[item]?.prepend(toastEl);
                    if (this._wrapper[item]) {
                        document.body.appendChild(this._wrapper[item] ?? document.createElement('div'));
                    }
                } else {
                    this._wrapper[item]?.prepend(toastEl);
                }

                if (!this._elements[item]) this._elements[item] = [];
                this._elements[item]?.push(toastEl);

                setTimeout(() => {
                    toastEl.classList.add('showing');
                }, 0);

                setTimeout(() => {
                    toastEl.classList.remove('showing');
                    setTimeout(() => {
                        toastEl.remove();

                        this._elements[item]?.shift();
                        if (!this._elements[item]?.length) {
                            this._wrapper[item]?.remove();
                        }
                    }, 200);
                }, 4000);
            }
        });
    }
}

const toast = new Toast();
export default toast;

if (typeof window != 'undefined') {
    window['toast'] = toast;
}
