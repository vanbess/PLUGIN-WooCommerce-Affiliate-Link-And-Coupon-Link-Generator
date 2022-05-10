<?php
/* * *********************************** */
/* * ADMIN PAGE FOR GENERATING LINKS* */
/* * *********************************** */

function sbwcaff_register_admin_page()
{

    // coupon generation page sub link
    add_submenu_page(
        'edit.php?post_type=coupon_link',
        __('Generate Coupon/Discount Link', 'woocommerce'),
        __('Coupon Link Generator', 'woocommerce'),
        'manage_options',
        'sbwcaff-settings',
        'sbwcaff_settings',
        1
    );

    // Polr API key input
    add_submenu_page(
        'edit.php?post_type=coupon_link',
        __('Polr API Settings', 'woocommerce'),
        __('Polr API Settings', 'woocommerce'),
        'manage_options',
        'sbwcaff-polr-api',
        'sbwcaff_polr_api',
        1
    );
}

add_action('admin_menu', 'sbwcaff_register_admin_page');

/**
 * Generate Polr API key input
 */
function sbwcaff_polr_api()
{

    global $title; ?>

    <div id="sbwcaff-polr-api">

        <h3 style="background: white; padding: 20px; border-left: 4px solid #666; margin-left: -20px; margin-top: 0px; box-shadow: -2px 2px 5px #00000012;">
            <?php _e($title, "woocommerce"); ?>
        </h3>

        <div id="sbwcaff-polr-api-input-cont">

            <!-- instructions -->
            <p><b><i><?php _e("If you have a Polr account for generating shortlinks, add your API key below to enable shortening of generated affiliate links.", "woocommerce"); ?></i></b></p>

            <p>
                <label for="sbwcaff-polr-api-key"><b><?php _e("Polr API Key:", "woocommerce"); ?></b></label>
            </p>

            <!-- api key input -->
            <p>
                <input type="text" name="sbwcaff-polr-api-key" id="sbwcaff-polr-api-key" style="width: 350px;" placeholder="<?php _e("enter API key here", "woocommerce"); ?>" value="<?php echo get_option('_sbwcaff_api_key'); ?>">
            </p>

            <p>
                <label for="sbwcaff-polr-api-link"><b><?php _e("Base Polr API URL to be used (make sure trailing slash is <u>NOT</u> present!):", "woocommerce"); ?></b></label>
            </p>

            <!-- api base link -->
            <p>
                <input type="url" name="sbwcaff-polr-api-link" id="sbwcaff-polr-api-link" style="width: 350px;" value="<?php echo get_option('_sbwcaff_api_link'); ?>" placeholder="<?php _e("e.g. http://somesite.com", "woocommerce"); ?>">
            </p>

            <!-- save -->
            <p>
                <button id="save-polr-api" class="button button-primary button-large" nonce="<?php echo wp_create_nonce('save polr api key'); ?>"><?php _e("Save Polr API Settings", "woocommerce"); ?></button>
            </p>

        </div>

        <script id="save-polr-api-js">
            $ = jQuery;

            $(document).ready(function() {

                $('#save-polr-api').on('click', function(e) {

                    e.preventDefault();

                    var nonce = $(this).attr('nonce');
                    var api_k = $('#sbwcaff-polr-api-key').val();
                    var api_url = $('#sbwcaff-polr-api-link').val();

                    if (!api_k) {
                        alert('<?php _e('Please enter your Polr API key before attempting to save', 'woocommerce') ?>');
                        return;
                    }

                    if (!api_url) {
                        alert('<?php _e('Please enter your base Polr API URL before attempting to save', 'woocommerce') ?>');
                        return;
                    }

                    var data = {
                        '_ajax_nonce': nonce,
                        'action': 'sbwcwaff_save_polr_api_key',
                        'api_k': api_k,
                        'api_url': api_url
                    }

                    $.post(ajaxurl, data, function(response) {
                        alert(response);
                        location.reload();
                    });

                });
            });
        </script>
    </div>


<?php }

/**
 * Render coupon link generation html
 */
function sbwcaff_settings()
{

    global $title;

?>
    <div id="sbwcaff_settings">

        <h3><?php _e($title, "woocommerce"); ?></h3>

        <div id="sbwcaff-settings-input-cont">
            <p><?php _e('Use the inputs below to generate your coupon link. Once generated you can save the link for later reference.'); ?></p>

            <!-- product slug -->
            <input type="text" name="pslug" id="pslug" placeholder="<?php _e('product slug*'); ?>">

            <!-- affiliate id -->
            <input type="text" name="affid" id="affid" placeholder="<?php _e('affiliate id*'); ?>">

            <!-- discount -->
            <input type="number" name="discount" id="discount" placeholder="<?php _e('discount percentage*'); ?>">

            <?php
            //get wc permalink structure
            $wc_permalinks = maybe_unserialize(get_option('woocommerce_permalinks'));
            ?>

            <!-- generate -->
            <button id="sbwcaff-gen-link" data-shop-base="<?php echo site_url(); ?>" data-product-base="<?php echo $wc_permalinks['product_base']; ?>/" class="button"><?php _e('Generate'); ?></button>

            <!-- generated link -->
            <input type="text" name="generated" id="generated" shorten-nonce="<?php echo wp_create_nonce('retrieve shortlink'); ?>" placeholder="<?php _e('generated link will appear here'); ?>">

            <!-- generated shortlink -->
            <input type="text" name="shortlink" id="shortlink" placeholder="<?php _e("if you have Polr API key present on settings page, generated shortlink will display here", "woocommerce"); ?>">

            <!-- save link -->
            <button id="sbwcaff-save-link" data-aju="<?php echo admin_url('admin-ajax.php'); ?>" data-nonce="<?php echo wp_create_nonce('save aff link'); ?>" class="button button-primary"><?php _e('Save Link'); ?></button>
        </div>
    </div>

    <script id="shorten-aff-link">
        $ = jQuery;

        $(document).ready(function() {
            $('#sbwcaff-gen-link').click(function(e) {
                e.preventDefault();

                var to_shorten = $('#generated').val();
                var nonce = $('#generated').attr('shorten-nonce');

                var data = {
                    '_ajax_nonce': nonce,
                    'action': 'sbwcaff_retrieve_shortlink',
                    'to_shorten': to_shorten
                }

                $.post(ajaxurl, data, function(response) {
                    $('#shortlink').val(response.body);
                });

            });
        });
    </script>
<?php
}

/******************************
 * RETRIEVE SHORTLINK VIA AJAX
 ******************************/
add_action('wp_ajax_nopriv_sbwcaff_retrieve_shortlink', 'sbwcaff_retrieve_shortlink');
add_action('wp_ajax_sbwcaff_retrieve_shortlink', 'sbwcaff_retrieve_shortlink');

function sbwcaff_retrieve_shortlink()
{

    check_ajax_referer('retrieve shortlink');

    $api_key = get_option('_sbwcaff_api_key');
    $api_url = get_option('_sbwcaff_api_link');

    $to_shorten = urlencode($_POST['to_shorten']);

    $request_url = "$api_url/api/v2/action/shorten?key=$api_key&url=$to_shorten";

    $short_url = wp_remote_post($request_url);

    wp_send_json($short_url);

    wp_die();
}

/*****************************
 * SAVE POLR API KEY VIA AJAX
 ****************************/
add_action('wp_ajax_nopriv_sbwcwaff_save_polr_api_key', 'sbwcwaff_save_polr_api_key');
add_action('wp_ajax_sbwcwaff_save_polr_api_key', 'sbwcwaff_save_polr_api_key');

function sbwcwaff_save_polr_api_key()
{

    check_ajax_referer('save polr api key');

    $api_key = $_POST['api_k'];
    $api_url = $_POST['api_url'];

    update_option('_sbwcaff_api_key', $api_key);
    update_option('_sbwcaff_api_link', $api_url);

    wp_send_json(__('Polr API settings saved.'), 'woocommerce');

    wp_die();
}

/*******************************
 * SAVE GENERATED LINK VIA AJAX
 ******************************/
add_action('wp_ajax_sbwcaff_save_genned_link', 'sbwcaff_save_genned_link');
add_action('wp_ajax_nopriv_sbwcaff_save_genned_link', 'sbwcaff_save_genned_link');

function sbwcaff_save_genned_link()
{

    check_ajax_referer('save aff link');

    $link_inserted = wp_insert_post([
        'post_type'    => 'coupon_link',
        'post_status'  => 'publish',
        'post_author'  => '',
        // 'post_title'   => htmlentities($_POST['link']),
        'post_title'   => htmlentities($_POST['shortlink']),
        'post_content' => '',
        'meta_input'   => [
            '_date_used' => '',
            '_coupon_id' => '',
            '_original_link' => htmlentities($_POST['link']),
        ]
    ]);

    if ($link_inserted) :
        _e('Link has been saved. Once used the associated coupon ID will be attached to it for tracking purposes.');
    else :
        _e('Link could not be saved. Please try again once the page has reloaded.');
    endif;

    wp_die();
}
?>