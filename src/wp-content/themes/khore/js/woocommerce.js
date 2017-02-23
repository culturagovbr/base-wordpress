jQuery(document).ready(function ($) {

    if (jQuery('.add_to_cart_loop').length) {
        jQuery('.woocommerce').on('change', '.quantity .qty', function (event) {
            var total = 0;

            jQuery('.woocommerce .products .quantity .qty').each(function (i, el) {
                total += parseInt(jQuery(el).val());
            });
            if (total > 0)
                jQuery('.woocommerce .add_to_cart_loop').removeAttr('disabled');
            else
                jQuery('.woocommerce .add_to_cart_loop').attr('disabled', 'disabled');
        });

        jQuery('.woocommerce').on('click', '.add_to_cart_loop', function (event) {
            var elements = jQuery('.woocommerce .products').find('[name=product_id]').filter(function () {
                return parseInt(jQuery(this).parent().find('.quantity .qty').val(), 10) > 0;
            });
            for (var i = 0; i < elements.size(); i++) {
                jQuery.ajax({
                    type: 'POST',
                    async: false,
                    url: wc_add_to_cart_params.ajax_url,
                    data: {
                        'action': 'woocommerce_add_to_cart',
                        'product_id': jQuery(elements[i]).val(),
                        'quantity': jQuery(elements[i]).parent().find('.quantity .qty').val()
                    },
                    success: function (data) {
                        if (i == elements.size() - 1)
                            location.href = wc_add_to_cart_params.cart_url;
                    }
                });
            }

            return false;
        });
        jQuery('.woocommerce .products').find('.quantity .qty').val(0);
        jQuery('.woocommerce .add_to_cart_loop').attr('disabled', 'disabled');
    }
});