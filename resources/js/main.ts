import $ from 'jquery'
import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'
import Swal, { SweetAlertResult } from 'sweetalert2'
import '../scss/components/_swall.scss'
import Modal from '../js/components/modal'
import { initDropdowns } from './components/dropdown'
import NProgress from 'nprogress'
import '../scss/components/_nprogress.scss'

export function initNProgress(config = {}) {
    NProgress.configure({ showSpinner: false, ...config });
    NProgress.start();
    window.addEventListener('DOMContentLoaded', () => {
        NProgress.done();
    })
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
    }
})

export function numberFormat(num: any, separator = '.', decimal = ',') {
    let number: any;
    if (decimal == ',') {
        number = num.toString().replace(/[^0-9|^,]/g, "");
    } else {
        number = num.toString().replace(/[^0-9|^.]/g, "");
    }
    if (number.substr(0, 1) == 0 && number.length > 1 && number.indexOf(decimal) == -1) {
        number = number.substr(1);
    }
    let parts = number.toString().split(decimal);

    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
    return parts.splice(0, 2).join(decimal);
}

export function initGlobalEvent() {
    document.addEventListener('click', function (this: Document, e) {
        const target = e.target as HTMLElement
        // dropdown handler
        const dropdown = target.closest('[data-dropdown-toggle]') as HTMLElement
        if (dropdown) {
            initDropdowns(dropdown)
        }
    })
}

export function debounce(func: any, timeout = 500) {
    let timer: any;
    return (...args: any) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

let modalEl = document.querySelector('#modalAction') as HTMLElement;

export function initActionModal() {
    if (modalEl) {
        window['modalAction'] = new Modal(modalEl)
    }

    const mainContent = $('.main-content')
    window['datatableId'] = mainContent.find('.table').attr('id') ?? ''

    mainContent.on('click', '[data-handler]', function (e) {
        e.preventDefault()
        const handler = JSON.parse(this.dataset.handler);

        const req = new AjaxAction(this, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                _action_handler: handler.actionHandler,
                _action_name: handler.actionName,
                _action_type: handler.actionType,
                _id: handler.id,
            })
        })

        // if has confirmation
        if (handler.hasConfirmation) {
            confirmation(() => {
                req.onSuccess(res => {
                    showToast(res.status, res.message)
                    reloadDatatable(window['datatableId'])
                }, false)
                    .onError(err => {
                        const message = err.responseJSON?.message
                        showToast('error', message ?? 'Something went wrong')
                    })
                    .execute()
            })

            return
        }

        req.onSuccess(function (res) {
            if (window['modalAction'] && res.html) {
                modalEl.innerHTML = res.html
                window['modalAction'].show()

                const handle = new HandleFormSubmit()
                handle.addData({
                    _action_handler: res.action_handler,
                    _action_name: res.action_name,
                    _action_type: res.action_type,
                    _id: res.id
                })
                    .onSuccess(res => {

                    })
                    .reloadDatatable(window['datatableId'] ?? '')
                    .init();
            } else {
                showToast(res.status, res.message)
                reloadDatatable(window['datatableId'])
            }
        }, false).execute()
    })
}

export function handleCheckMenu() {
    $('.parent').on('click', function (this: HTMLInputElement) {
        const childs = $(this).parents('tr').find('.child')
        childs.prop('checked', this.checked)
    })

    $('.child').on('click', function () {
        const parent = $(this).parents('tr')
        const childs = parent.find('.child')
        const checked = parent.find('.child:checked')

        parent.find('.parent').prop('checked', childs.length == checked.length)
    })

    $('.parent').each(function () {
        const parent = $(this).parents('tr')
        const childs = parent.find('.child')
        const checked = parent.find('.child:checked')

        parent.find('.parent').prop('checked', childs.length == checked.length)
    })
}

// handle action by url parameters
export function initActionByUrl() {
    const url = new URL(window['location'].href)
    const params = url.searchParams
    let resolvedUrl: string = ''

    if (params.get('action')) {
        const actions = $('[data-actions]').data('actions')
        let action = params.get('action') ?? '';

        if (actions[action]) {
            resolvedUrl = actions[action]['url']
        }
    }

    if (params.get('tableAction')) {
        const actions = $('[data-table-actions]').data('tableActions')
        let action = params.get('tableAction') ?? '';
        let id = params.get('tableActionId') ?? '';

        if (actions[action]) {
            resolvedUrl = actions[action]['url']

            //@ts-ignore
            resolvedUrl = resolvedUrl.replaceAll('{{id}}', id);
        }
    }
    if (resolvedUrl != '') {
        (new AjaxAction(resolvedUrl))
            .onSuccess(res => {
                (new HandleFormSubmit())
                    .reloadDatatable(window['datatableId'] ?? '')
                    .init();
            })
            .execute();
    }
    document.addEventListener('hiddenModal', function (e) {
        window['history'].replaceState({}, '', url.pathname)
    })
}

export function confirmation(cb: (res: SweetAlertResult) => void, configs = {}) {
    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Data ini akan dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Yakin!",
        ...configs
    }).then((result) => {
        if (result.isConfirmed) {
            cb && cb(result)
        }
    });
}

export function reloadDatatable(id: any, url = null) {
    window['LaravelDataTables'][id]?.ajax.reload(url, false)
}

export function handleNotification(cb: (res: any) => void) {
    const url = new URL(window['location'].href)
    const params = url.searchParams
    if (params.get('id')) {
        (new AjaxAction(`${url.origin + url.pathname}/${params.get('priority') == '1' ? `approve/${params.get('id')}` : params.get('id')}`))
            .onSuccess(function (res) {
                cb && cb(res)
            })
            .execute();
    }
}

type Tizi = keyof typeof iziToast
export function showToast(type: Tizi = 'success', message = 'Berhasil menyimpan data') {
    // @ts-ignore
    iziToast[type]({
        title: 'Info',
        message,
        position: 'topRight'
    })
}

type TFunction = (res: any) => void
class AjaxOption {
    successCb: null | TFunction = null
    runDefaultSuccessCb = true
    errorCb: null | TFunction = null
    runDefaultErrorCb = true
    options: JQueryAjaxSettings = {}

    onSuccess(cb: TFunction, runDefault = true) {
        this.successCb = cb
        this.runDefaultSuccessCb = runDefault
        return this
    }
    onError(cb: TFunction, runDefault = true) {
        this.errorCb = cb
        this.runDefaultErrorCb = runDefault
        return this
    }
}

export class AjaxAction extends AjaxOption {
    url = ''
    method = 'get'
    el: JQuery | null;
    label: string
    constructor(el: string | HTMLElement, options: JQueryAjaxSettings = {}) {
        super()
        this.options = options
        if (el instanceof HTMLElement) {
            this.el = $(el)
            this.label = this.el.html()
            this.url = this.el.data('url')
            this.method = this.el.data('method')
        } else {
            this.el = null
            this.url = el
            this.label = ''
        }
    }

    setOption(_option: JQueryAjaxSettings) {
        this.options = _option
        return this
    }

    execute() {
        $.ajax({
            url: this.url,
            method: this.method,
            ...this.options,
            beforeSend: () => {
                window['NProgress'].start()
                if (this.el) {
                    this.el.attr('disabled', 'true')
                    this.el.html('Loading...')
                }
            },
            success: (res) => {
                if (this.runDefaultSuccessCb && this.method.toLowerCase() == 'get') {
                    if (window['modalAction']) {
                        modalEl.innerHTML = res
                        window['modalAction'].show()
                    }
                } else {
                    !this.successCb && showToast(res?.status, res?.message)
                }

                this.successCb && this.successCb(res)
            },
            error: err => {
                if (this.runDefaultErrorCb) {

                }
                this.errorCb && this.errorCb(err)
            },
            complete: () => {
                if (this.el) {
                    this.el.prop('disabled', false)
                    this.el.html(this.label)
                }
                window['NProgress'].done()
            }
        })
    }
}

export class HandleFormSubmit extends AjaxOption {
    datatableId: string = '';
    formId: JQuery;
    button: JQuery<HTMLElement>;
    buttonLabel: string;
    formData: Record<string, any> = {}

    constructor(formId = '#formAction') {
        super()
        this.formId = $(formId)
        this.button = this.formId.find('button[type="submit"]')
        this.buttonLabel = this.button.html()
    }

    addData(formData: Record<string, any>) {
        this.formData = formData
        return this;
    }

    reloadDatatable(id: string) {
        this.datatableId = id;
        return this
    }

    init() {
        const _this = this
        this.formId.on('submit', function (this: any, e) {
            e.preventDefault()

            const formData = new FormData(this)

            Object.entries(_this.formData).map(([key, value]) => {
                formData.append(key, value)
            })

            $.ajax({
                url: _this.formId.attr('action'),
                method: _this.formId.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    _this.button.attr('disabled', 'true').html('Loading...')
                    window['NProgress'].start()
                },
                success: res => {
                    if (_this.runDefaultSuccessCb) {
                        // @ts-ignore
                        window['modalAction']?.hide()
                    }
                    showToast(res?.status, res?.message)
                    _this.successCb && _this.successCb(res)
                    if (_this.datatableId) {
                        window['LaravelDataTables'][_this.datatableId].ajax.reload(null, false)
                    }
                },
                error: err => {
                    if (_this.runDefaultErrorCb) {
                        $('.is-invalid').removeClass('is-invalid')
                        $('.invalid-feedback').remove()
                        const message = err.responseJSON?.message
                        const errors = err.responseJSON?.errors

                        if (message) {
                            showToast('error', message)
                        }
                        if (errors) {
                            let i = 0
                            for (let [key, value] of Object.entries(errors)) {
                                let input = _this.formId.find(`[name="${key}"]`)
                                if (!input.length) {
                                    if (key.includes('.')) {
                                        // @ts-ignore
                                        let keySplit = key.split('.')
                                        if (!isNaN(parseInt(keySplit[1])) && parseInt(keySplit[1]).toString().length == keySplit[1].length) {
                                            input = _this.formId.find(`[name="${key}[]"]`)
                                        } else {
                                            let searchKey = keySplit[0] + '[' + keySplit[1] + ']';
                                            input = _this.formId.find(`[name="${searchKey}"]`)
                                        }
                                    }
                                }
                                if (i == 0) {
                                    input.trigger('focus')
                                }

                                input.addClass('is-invalid').parents('.form-wrapper').append(`<div class="invalid-feedback [&.invalid-feedback]:text-sm [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger">${value}</div>`)
                                i++;
                            }
                        }
                    }
                    _this.errorCb && _this.errorCb(null)
                    _this.button.prop('disabled', false).html(_this.buttonLabel)
                },
                complete: () => {
                    _this.button.prop('disabled', false).html(_this.buttonLabel)
                    window['NProgress'].done()
                },
            })
        })
    }
}

export function initFilter() {
    const pageUrl = new URL(window['location'].href)
    $('[data-filter]').on('change', function (this: HTMLInputElement, e: any) {
        if (this.type == 'checkbox') {
            if (this.checked) {
                pageUrl.searchParams.set(this.name, '1')
            } else {
                pageUrl.searchParams.set(this.name, '0')
            }
        } else {
            pageUrl.searchParams.set(this.name, this.value)
        }

        window['history'].pushState({}, '', pageUrl)
        window['LaravelDataTables'][this.dataset.filter ?? '']?.ajax.url(pageUrl.href).load()
    })
}

if (typeof window !== 'undefined') {
    window['NProgress'] = NProgress;
    window['confirmation'] = confirmation;
    window['AjaxAction'] = AjaxAction;
    window['handleCheckMenu'] = handleCheckMenu;
    window['initFilter'] = initFilter
    window['initActionModal'] = initActionModal
    window['initActionByUrl'] = initActionByUrl
    window['numberFormat'] = numberFormat
    window['showToast'] = showToast
    window['reloadDatatable'] = reloadDatatable
    window['debounce'] = debounce
}