(function ($) {
    $('.asset-button-remove').on('click', function (e) {
        e.stopPropagation();
        $(this).closest('tr').remove();
        return false;
    });

    $('#asset-button-add').on('click', function (e) {
        e.stopPropagation();
        $('#asset-inputs').attr('hidden', null);
        return false;
    });

    $('#asset-button-complete').on('click', function (e) {
        e.stopPropagation();

        var __name = $('#asset_name'),
            __value = $('#asset_value');

        if (__name.val().length && __value.val().length) {
            var row = $('<tr>'),
                __button_del = $('<a class="asset-button-remove" href="" title="Remove"><i class="fa fa-fw fa-minus-square" style="color: red;"></i></a>');

            row.append($('<td>').text(__name.val()));
            row.append($('<td>').text(__value.val()));
            row.append($('<td>').append(__button_del));

            $(this).closest('table').find('tbody').append(row);

            $('#asset-inputs').attr('hidden', true);
        }
        return false;
    });

})(jQuery);