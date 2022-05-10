jQuery(document).ready(function ($) {

    //generate link
    $('button#sbwcaff-gen-link').click(function (e) {
        e.preventDefault();

        var slug, affid, discount, shop_base, prod_base, generated, nonce;

        slug = $('input#pslug').val();
        affid = $('input#affid').val();
        discount = $('input#discount').val();
        shop_base = $(this).data('shop-base');
        prod_base = $(this).data('product-base');
        nonce = $(this).data('nonce');


        if (!slug || !affid || !discount) {
            alert('Please fill out all fields.');
        } else {
            generated = shop_base + prod_base + slug + '/?a=' + affid + '&d=' + discount;
            $('input#generated').val(generated);
        }

    });

    //save link
    $('button#sbwcaff-save-link').click(function (e) {
        e.preventDefault();

        var link, ajax_url,shortlink,nonce;

        link = $('#generated').val();
        ajax_url = $(this).data('aju');
        shortlink = $('#shortlink').val();
        nonce = $(this).data('nonce');

        if (!link) {
            alert('There is no link to save.');
        } else {
            var data = {
                '_ajax_nonce': nonce,
                'action': 'sbwcaff_save_genned_link',
                'link': link,
                'shortlink' : shortlink
            };
            $.post(ajax_url, data, function (response) {
                alert(response);
                location.reload();
            });
        }
    });

});