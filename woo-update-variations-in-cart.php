<?php
/**
 * Plugin Name: Woo Update Variations In Cart
 * Plugin URI: WooUpdateVariationsInCart
 * Description: WooCommerce Update Variations In Cart.
 * Version: 1.0.0
 * Author: Roshni Ahuja
 * Author URI: https://about.me/roshniahuja
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

//Plugin Path
if ( ! defined( 'WUVIC_WOO_UPDATE_CART_ASSESTS_URL' ) ) {

	define('WUVIC_WOO_UPDATE_CART_ASSESTS_URL', plugin_dir_url(__FILE__).'assets/');

}

require 'admin/class-wc-update-variation-in-cart-admin.php';

require 'front/class-wc_update-variation_in-cart.php';

register_activation_hook(__FILE__, 'woo_ck_wuvic_enable_plugin');

function woo_ck_wuvic_enable_plugin() {

    update_option( 'WOO_CK_WUVIC_status', 'true');

}
?>