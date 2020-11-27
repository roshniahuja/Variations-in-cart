<?php
class Wc_updateVariationInCartAdmin {

	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array($this,'wk_custom_product_tab' ));
		add_action( 'woocommerce_product_data_panels',  array($this, 'wk_custom_tab_data' ));
		add_action( 'woocommerce_process_product_meta', array($this, 'shipping_costs_process_product_meta_fields_save' ));
	}
	
	public function wk_custom_product_tab( $default_tabs ) {
	    $default_tabs['custom_tab'] = array(
	        'label'   =>  __( 'Artwork', 'woocommerce-extension' ),
	        'target'  =>  'wk_custom_tab_data',
	        'priority' => 60,
	        'class'   => array()
	    );
	    return $default_tabs;
	}
	

	public function wk_custom_tab_data() {
	  global $post;

	    $post_id = $post->ID;

	    echo '<div id="wk_custom_tab_data" class="panel woocommerce_options_panel">';

	    woocommerce_wp_text_input( array(
	        'id'            => '_input_image',
	        'placeholder'   => __( 'Enter Image Price', 'woocommerce-extension' ),
	        'label'         => __( 'Enter Image Price', 'woocommerce-extension' ), 
	        'description'   => __( 'Enter Image Price', 'woocommerce-extension' ),
	        'desc_tip'      => true,
	       
	    ) );

	    woocommerce_wp_text_input( array(
	        'id'            => '_input_text',
	        'placeholder'   => __( 'Enter Text Price', 'woocommerce-extension' ),
	        'label'         => __( 'Enter Text Price', 'woocommerce-extension' ), 
	        'description'   => __( 'Enter Text Price', 'woocommerce-extension' ),
	        'desc_tip'      => true,
	       
	    ) );

	    woocommerce_wp_hidden_input( array(
	        'id'            => '_hidden_input',
	        'class'         => 'some_class',
	    ) );

	    echo '</div>';
	}

	public function shipping_costs_process_product_meta_fields_save( $post_id ){

	    // save the text field data
	    if( isset( $_POST['_input_image'] ) )
	        update_post_meta( $post_id, '_input_image', esc_attr( $_POST['_input_image'] ) );

	    // save the textarea field data
	    if( isset( $_POST['_input_text'] ) )
	        update_post_meta( $post_id, '_input_text', esc_attr( $_POST['_input_text'] ) );

	    // save the hidden input data
	    if( isset( $_POST['_hidden_input'] ) )
	        update_post_meta( $post_id, '_hidden_input', esc_attr( $_POST['_hidden_input'] ) );
	}

}
new Wc_updateVariationInCartAdmin;
?>