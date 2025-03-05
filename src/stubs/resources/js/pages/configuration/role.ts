document.addEventListener('shownModal', function (e) {
    const el = e.target as HTMLElement

    if ((el.querySelector('form') as HTMLFormElement).action.indexOf('permissions') != -1) {
        window['handleCheckMenu']()
        $('.search').on('keyup', function (this: HTMLInputElement) {
            const value = this.value.toLowerCase()
            $('#menu_permissions tr').show().filter(function (i, item) {
                return item.innerText.toLowerCase().indexOf(value) == -1
            }).hide()
        })

        $('.copy').on('change', function (this: HTMLSelectElement) {
            new window['AjaxAction'](window.location.origin + `/configuration/roles/${this.value}/copy-permissions`)
                .onSuccess(function (res) {
                    $('#menu_permissions').html(res)
                    window['handleCheckMenu']()
                }, false)
                .execute()
        })
    }
})