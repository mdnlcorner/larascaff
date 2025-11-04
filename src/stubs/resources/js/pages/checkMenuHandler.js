export default function checkMenuHandler() {
    $('.parent').on('click', function () {
        const childs = $(this).parents('tr').find('.child');
        childs.prop('checked', this.checked);
    });

    $('.child').on('click', function () {
        const parent = $(this).parents('tr');
        const childs = parent.find('.child');
        const checked = parent.find('.child:checked');

        parent.find('.parent').prop('checked', childs.length == checked.length);
    });

    $('.parent').each(function () {
        const parent = $(this).parents('tr');
        const childs = parent.find('.child');
        const checked = parent.find('.child:checked');

        parent.find('.parent').prop('checked', childs.length == checked.length);
    });
}
