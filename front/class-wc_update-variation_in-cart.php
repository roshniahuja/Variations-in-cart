<?php
class Wc_updateVariationInCart {

	public function __construct() {

		if ( $this->woo_ck_wuvic_is_woocommerce_active() ) {   

			//add js file to cart 

			add_action('wp_head', array($this, 'woo_ck_wuvic_hook_js'));

			add_action( 'wp_head', array($this, 'woo_ck_wuvic_itr_global_js_vars' ));

			//add edit link on cart page 

			add_filter( 'woocommerce_cart_item_name', array($this, 'woo_ck_wuvic_cart_product_title'), 20, 3);

			//get variation form using ajax

			add_action( 'wp_ajax_get_variation_form', array($this, 'woo_ck_wuvic_get_variation_form' ));

	        add_action( 'wp_ajax_nopriv_get_variation_form', array($this, 'woo_ck_wuvic_get_variation_form' ));

	        //update product

			add_action( 'wp_ajax_update_product_in_cart', array($this, 'woo_ck_wuvic_update_product_in_cart' ));

	        add_action( 'wp_ajax_nopriv_update_product_in_cart', array($this, 'woo_ck_wuvic_update_product_in_cart' ));

		}else{

			add_action( 'admin_notices', array($this, 'woo_ck_wuvic_woocommerce_admin_notice' ));

		} 

	}
	/**
	* Check if WooCommerce is active
	**/

	public function woo_ck_wuvic_is_woocommerce_active() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );

	}

	public function woo_ck_wuvic_woocommerce_admin_notice() {
		?>

			<div class="error">

				<p><?php _e( 'WooCommerce Update Variations In Cart is enabled. Plese Install wocommerce.', 'woo_ck_wuvic_woocommerce_admin_notice-woocommerce' ); ?></p>

			</div> 

        <?php
	}

	public function woo_ck_wuvic_hook_js() {	//apply if condition here

		global $wpdb;

		$woo_ck_wuvic_status = "true";

		if( "true" == $woo_ck_wuvic_status){

			if( is_cart() ){

				wp_enqueue_script( 'wc-add-to-cart-variation' );

				wp_enqueue_script("ck-cart-js",WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'js/cart.js',array('jquery'),'0.1',true);  

				wp_enqueue_style( 'ck-cart-css',WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'css/style.css',false,'0.1','all'); 

			}

		}

	}

	public function woo_ck_wuvic_cart_product_title( $title, $values, $cart_item_key ) {

		if( "" == get_option( "WOO_CK_WUVIC_edit_link_text" ) ){

			$woo_ck_wuvic_edit_link_text = "Edit";

		}

		if( count($values['variation']) &&  "true" == get_option( 'WOO_CK_WUVIC_status' ) ){ //apply if condition here in if()

			$targetPath = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'/img/loader.gif';

			return $title . '<br /><span class="WOO_CK_WUVIC_buttom" id="'.$cart_item_key.'" >'.$woo_ck_wuvic_edit_link_text.'</span>'.'<img src="'.$targetPath.'" alt="Smiley face" height="42" width="42" id="loder_img" style="display:none;"><br /><span class="WOO_CK_WUVIC_buttom" id="additional_info" >Additional Customization</span>';

		}else{ 

			return $title; 

		}

	}

	public function woo_ck_wuvic_itr_global_js_vars() {

		global $wpdb;

		$woo_ck_wuvic_update_btn = get_option( 'WOO_CK_WUVIC_update_btn_text' );

	    $ajax_url = 'var update_variation_params = {"ajax_url":"'. admin_url( 'admin-ajax.php' ) .'","update_text":"'.$woo_ck_wuvic_update_btn.'","cart_updated_text":"Cart updated."};';

	    echo "<script type='text/javascript'>\n";

	    echo "/* <![CDATA[ */\n";

	    echo $ajax_url;

	    echo "\n/* ]]> */\n";

	    echo "</script>\n";

	}

	public function woo_ck_wuvic_get_variation_form() {

		global $woocommerce;

		$items = $woocommerce->cart->get_cart_item($_POST['current_key_value']);

		$product_woo_ck = wc_get_product($items['product_id']);

		$selected_variation = $items['variation'];

		$selected_qty = $items['quantity'];

		$available_variations = $product_woo_ck->get_available_variations();

		$attributes = $product_woo_ck->get_variation_attributes();

		$image_price = get_post_meta($items['product_id'],'_input_image',true);

		$text_price = get_post_meta($items['product_id'],'_input_text',true);
		?>

		<script type='text/javascript' src='<?php echo plugins_url(); ?>/woocommerce/assets/js/frontend/add-to-cart-variation.js?ver=2.6.8'></script>

		<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product_woo_ck->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">

			<table class="variations" cellspacing="0">

				<tbody>

					<?php foreach ( $attributes as $attribute_name => $options ) : ?>

						<tr>

							<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>

							<td class="value">

								<?php

									$selected = $selected_variation[ 'attribute_' . sanitize_title( $attribute_name ) ];

									wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product_woo_ck, 'selected' => $selected ) );

									echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' ) : '';

								?>

							</td>

						</tr>

					<?php endforeach;?>

				</tbody>

			</table>

			<div class="single_variation_wrap">

				<div class="woocommerce-variation single_variation" style="">

					<div class="woocommerce-variation-description"></div>

					<div class="woocommerce-variation-price">

						<span class="price"></span>

					</div>

				    <div class="woocommerce-variation-availability"></div>

				</div>
				
				<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-enabled">

					<?php $targetPath = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'img/loader.gif'; ?>

					<img src="<?php echo $targetPath; ?>" alt="Smiley face" height="42" width="42" id="loder_img_btn" style="display:none;">

					<div class="quantity">

						<?php woocommerce_quantity_input( array( 'input_value' => isset( $selected_qty ) ? wc_stock_amount( $selected_qty ) : 1 ) ); ?>

					</div>

					<input type="hidden" id="product_thumbnail" value='<?php echo $product_woo_ck->get_image();  ?>'>

					<button type="submit" class="single_add_to_cart_button button alt <?php echo get_option( 'WOO_CK_WUVIC_update_btn_class' ); ?>" id="single_add_to_cart_button_id"><?php if( "" != get_option('WOO_CK_WUVIC_update_btn_text') ) { echo get_option( 'WOO_CK_WUVIC_update_btn_text' ); }else{ echo "Update"; }?></button>

					<span id="cancel" class="<?php echo get_option( 'WOO_CK_WUVIC_cancel_btn_class' ); ?>" onclick="cancel_update_variations('<?php echo $_POST['current_key_value']; ?>');" title="Close" style="cursor: pointer; "><?php if( "" != get_option( 'WOO_CK_WUVIC_cancel_btn' ) ){ echo get_option( 'WOO_CK_WUVIC_cancel_btn' ); }else{?>cancel<?php } ?></span>

					<input type="hidden" name="add-to-cart" value="<?php echo absint( $product_woo_ck->id ); ?>">

					<input type="hidden" name="product_id" value="<?php echo absint( $product_woo_ck->id ); ?>">

					<input type="hidden" name="variation_id" class="variation_id" value="9">

					<input name="old_key" class="old_key" type="hidden" value="<?php echo $_POST['current_key_value']; ?>">

				</div>

			</div>

		</form>

		<?php

		die();

	}
 
	public function woo_ck_wuvic_update_product_in_cart() {
		//echo 'test';die;
		parse_str( $_POST[ 'form_data' ] );

		global $wpdb,$woocommerce;

		$cart_url = wc_get_cart_url();

		if( '' != $variation_id){

			$woocommerce->cart->remove_cart_item($_POST['old_key']);

		}

		wp_redirect($cart_url.'?'.$_POST['form_data']);die();

	}

}

new Wc_updateVariationInCart;
?>