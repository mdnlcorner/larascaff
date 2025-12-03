import checkMenuHandler from './checkMenuHandler';

document.addEventListener('shownModal', function (e) {
    const searchInput = $(this).find('form').find('[name=search]');
    if (searchInput.length) {
        checkMenuHandler();
        searchInput.on('keyup', function () {
            const value = this.value.toLowerCase();
            $('#menu_permissions tr')
                .show()
                .filter(function (i, item) {
                    return item.innerText.toLowerCase().indexOf(value) == -1;
                })
                .hide();
        });

        $('.copy').on('change', function () {
            new window['AjaxAction'](this.dataset?.url + `/${this.value}/copy-permissions`)
                .onSuccess(function (res) {
                    $('#menu_permissions').html(res);
                    checkMenuHandler();
                }, false)
                .execute();
        });
    }
});
