<?php

/******************************************/
/**INSERT COUPON BASED ON AFFILIATE LINK**/
/******************************************/
add_action('wp_head', 'sbwcaff_coupon_link_check');
function sbwcaff_coupon_link_check()
{

    if (is_product() && isset($_GET['a']) && isset($_GET['d'])) :

        //setup vars
        $affid       = $_GET['a'];
        $discount    = $_GET['d'];
        $coupon_link = site_url() . $_SERVER['REQUEST_URI'];

?>

        <a id="coupon-popup" href="#sbwcaff-coupon-modal-cont"></a>

        <input type="hidden" name="sbwcaff_affid" id="sbwcaff_affid" value="<?php echo $affid; ?>">
        <input type="hidden" name="sbwcaff_discount" id="sbwcaff_discount" value="<?php echo $discount; ?>">
        <input type="hidden" name="sbwcaff_title" id="sbwcaff_title" value="<?php echo get_post_field('post_title', get_the_ID()); ?>">
        <input type="hidden" name="sbwcaff_coupon_link" id="sbwcaff_coupon_link" data-aju="<?php echo admin_url('admin-ajax.php'); ?>" value="<?php echo $coupon_link; ?>">

        <?php endif;
}

/********************************************************/
/**CHECK FOR COUPON EXISTENCE/CREATE COUPON AND RETURN**/
/*******************************************************/
add_action('wp_ajax_sbwcaff_create_check_coupon', 'sbwcaff_create_check_coupon');
add_action('wp_ajax_nopriv_sbwcaff_create_check_coupon', 'sbwcaff_create_check_coupon');
function sbwcaff_create_check_coupon()
{
    if ($_POST['action'] == 'sbwcaff_create_check_coupon') :

        //get affiliate id, discount, coupon link and product title
        $affid       = $_POST['affid'];
        $discount    = $_POST['discount'];
        $coupon_link = $_POST['coupon_link'];
        $prod_title  = $_POST['prod_title'];
		
        // generate coupon name
        $aff_name = strtolower(str_replace(' ', '_', affwp_get_affiliate_username($affid)));
        $prod_title = strtolower(str_replace(' ', '_', $prod_title));
        $coupon_name = $aff_name . $discount . "_" . date('Ymd');

        //check for existing coupons with affiliate id and link attached
        $coupons = new WP_Query([
            'post_type'      => 'shop_coupon',
            'author'         => '',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_key'       => 'coupon_link',
            'meta_value'     => $coupon_link
        ]);
		
        //if coupon found, return coupon data
        if ($coupons->have_posts()) :
            while ($coupons->have_posts()) : $coupons->the_post();

                //if criteria matched, check if coupon is not expired; if not, display, else create new one
                $today = strtotime('now');
                $expiry = get_post_meta(get_the_ID(), 'date_expires', true);

                //if not expired, return data
                if ($expiry > $today) : ?>

                    <!-- coupon modal popup -->
                    <div id="sbwcaff-coupon-modal-overlay"></div>
                    <div id="sbwcaff-coupon-modal-proper">
                        <?php $_SESSION['sbwcaff_coupon_shown'] = true; ?>
                        <span id="sbwcaff-coupon-modal-close" title="Cancel">x</span>
                        <h3><?php _e('Use the coupon below to get ' . $discount . '% off during checkout:'); ?></h3>
                        <input id="sbwcaff-coupon-actual" title="<?php _e('click to copy coupon code'); ?>" readonly value="<?php echo get_the_title(); ?>">
                        <span id="sbwcaff-coupon-copied" style="display: none;"><?php _e('coupon copied to clipboard'); ?></span>
                    </div>
                    <?php
					break;
                //else if expired, insert new coupon
                else :
                    $new_coupon_inserted = wp_insert_post([
                        'post_type'    => 'shop_coupon',
                        'post_status'  => 'publish',
                        'post_author'  => '',
                        'post_content' => '',
                        'post_title'   => $coupon_name,
                        'post_excerpt' => 'Coupon generated from coupon affiliate link ' . $coupon_link,
                        'meta_input'   => [
                            'discount_type'            => 'percent',
                            'coupon_amount'            => $discount,
                            'coupon_link'              => $coupon_link,
                            'individual_use'           => 'yes',
                            'usage_limit'              => 0,
                            'usage_limit_per_user'     => 1,
                            'limit_usage_to_x_items'   => 0,
                            'usage_count'              => 0,
                            'date_expires'             => strtotime('+ 1 day'),
                            'free_shipping'            => 'no',
                            'exclude_sale_items'       => 'no',
                            'affwp_discount_affiliate' => $affid,
                            '_wjecf_products_and'      => 'no',
                            '_wjecf_categories_and'    => 'no',
                            '_wjecf_is_auto_coupon'    => 'no',
                            '_wjecf_apply_silently'    => 'no',

                        ]
                    ]);

                    if ($new_coupon_inserted) : ?>

                        <!-- coupon modal/popup -->
                        <div id="sbwcaff-coupon-modal-overlay"></div>
                        <div id="sbwcaff-coupon-modal-proper">
                            <?php $_SESSION['sbwcaff_coupon_shown'] = true; ?>
                            <span id="sbwcaff-coupon-modal-close" title="Cancel">x</span>
                            <h3><?php _e('Use the coupon below to get ' . $discount . '% off during checkout:'); ?></h3>
                            <input id="sbwcaff-coupon-actual" title="<?php _e('click to copy coupon code'); ?>" readonly value="<?php echo $coupon_name; ?>">
                            <span id="sbwcaff-coupon-copied" style="display: none;"><?php _e('coupon copied to clipboard'); ?></span>
                        </div>
                <?php
                    endif;
                endif;

            endwhile;
            wp_reset_postdata();

        //if coupon not found, insert new coupon
        else :

            $new_coupon_inserted = wp_insert_post([
                'post_type'    => 'shop_coupon',
                'post_status'  => 'publish',
                'post_author'  => '',
                'post_content' => '',
                'post_title'   => $coupon_name,
                'post_excerpt' => 'Coupon generated from coupon affiliate link ' . $coupon_link,
                'meta_input'   => [
                    'discount_type'            => 'percent',
                    'coupon_amount'            => $discount,
                    'coupon_link'              => $coupon_link,
                    'individual_use'           => 'yes',
                    'usage_limit'              => 0,
                    'usage_limit_per_user'     => 1,
                    'limit_usage_to_x_items'   => 0,
                    'usage_count'              => 0,
                    'date_expires'             => strtotime('+ 1 day'),
                    'free_shipping'            => 'no',
                    'exclude_sale_items'       => 'no',
                    'affwp_discount_affiliate' => $affid,
                    '_wjecf_products_and'      => 'no',
                    '_wjecf_categories_and'    => 'no',
                    '_wjecf_is_auto_coupon'    => 'no',
                    '_wjecf_apply_silently'    => 'no',
                ]
            ]);

            if ($new_coupon_inserted) : ?>
                <!-- coupon modal popup -->
                <div id="sbwcaff-coupon-modal-overlay"></div>
                <div id="sbwcaff-coupon-modal-proper">
                    <?php $_SESSION['sbwcaff_coupon_shown'] = true; ?>
                    <span id="sbwcaff-coupon-modal-close" title="Cancel">x</span>
                    <h2><?php _e('Use the coupon below to get ' . $discount . '% off during checkout:'); ?></h2>
                    <input id="sbwcaff-coupon-actual" title="<?php _e('click to copy coupon code'); ?>" readonly value="<?php echo get_post_field('post_title', $new_coupon_inserted); ?>">
                    <span id="sbwcaff-coupon-copied" style="display: none;"><?php _e('coupon copied to clipboard'); ?></span>
                </div>
<?php endif;
        endif;
    endif;
    wp_die();
}
