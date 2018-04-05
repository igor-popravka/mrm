(function ($) {
    var __form_product = document.forms.product,
        __handler_button_remove = function (e) {
            e.stopPropagation();
            $(this).closest('tr').remove();
            return false;
        };

    $('.asset-button-remove').on('click', __handler_button_remove);

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

            row.append($('<td class="td-asset-name">').text(__name.val()));
            row.append($('<td class="td-asset-value">').text(__value.val()));
            row.append($('<td>').append(__button_del));

            __button_del.on('click', __handler_button_remove);

            $(this).closest('table').find('tbody').append(row);

            $('#asset-inputs').attr('hidden', true);
        }
        return false;
    });

    $(__form_product).on('submit', function (e) {
        var __rows = $('#asset-table').find('tbody tr');

        if (__rows.length) {
            var assets = [];
            $(__rows).each(function () {
                assets.push({
                    name: $('td.td-asset-name', this).text(),
                    value: $('td.td-asset-value', this).text()
                });
            });

            $('#product_assets').val(JSON.stringify(assets));
        }
    });
})(jQuery);