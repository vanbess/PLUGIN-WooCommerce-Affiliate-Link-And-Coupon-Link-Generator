jQuery(document).ready(function ($) {

    /* NOTE: MODAL FUNCTIONALITY ASSUMES PRE-INSTALLATION OF MAGNIFIC POPUP */

    //insert/query coupon
    var affid, discount, coupon_link, ajax_url, prod_title;
    affid = $('#sbwcaff_affid').val();
    discount = $('#sbwcaff_discount').val();
    coupon_link = $('#sbwcaff_coupon_link').val();
    prod_title = $('#sbwcaff_title').val();
    ajax_url = $('#sbwcaff_coupon_link').data('aju');

    // magnific
    $('#coupon-popup').magnificPopup({
		type: 'inline',
		preloader: false,
		modal: true
	});

    if (affid && discount && coupon_link) {
        var data = {
            'action': 'sbwcaff_create_check_coupon',
            'affid': affid,
            'discount': discount,
            'coupon_link': coupon_link,
            'prod_title': prod_title
        };
        $.post(ajax_url, data, function (response) {
            $('.product-main').append('<div id="sbwcaff-coupon-modal-cont" class="mfp-hide white-popup-block"></div>');
            $('#sbwcaff-coupon-modal-cont').html(response);
            $('#coupon-popup').trigger('click');
        });
    }

    //hide coupon modal
    $(document).on('click', '#sbwcaff-coupon-modal-close', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

    //coupon text on click
    $('body').on('click', 'input#sbwcaff-coupon-actual', function () {
        $(this).select();
        document.execCommand('copy');
        $('#sbwcaff-coupon-copied').show();
    });
});