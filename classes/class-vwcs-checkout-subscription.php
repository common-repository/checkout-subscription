<?php
/*
 * @package           Checkout Subscription
 * @author            Sathishkumar S
 * @copyright         2019 VISIONWEB Software Solution
 * @license           GPL-2.0
 */
class VWCS_Checkout_Subscription {
	private $version;
	public function __construct() {
		$this->version=VWCS_VERSION;
		$this->admin_hooks();
		if(in_array($_REQUEST['page'],array('checkout-subscription'))){
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}


		$address_fields = apply_filters('woocommerce_billing_fields', $address_fields);
	}

	private function admin_hooks() {
		add_action('admin_menu', array($this,'add_admin_menu'));
		add_action('admin_action_subscriptionsavesettings', array($this,'subscriptionsavesettings'));
		if(get_option('subscriptionstatus')=='yes'){
			add_action( 'woocommerce_checkout_after_customer_details', array( $this,'checkout_subscription_field' ) );
			add_action( 'woocommerce_after_checkout_validation', array($this,'cantact_to_sendinblue_validation') , 10, 1);
			add_action( 'woocommerce_add_order_item_meta', array($this,'add_cantact_to_sendinblue') , 10, 1);
		}
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'vwcs-light', VWCS_URL_PATH . '/css/light.min.css', array(), $this->version, 'all' );
		wp_enqueue_style(  'vwcs-style', VWCS_URL_PATH . '/css/style.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'vwcs-particles', VWCS_URL_PATH . '/js/particles.min.js', array('jquery'), $this->version, false );
		wp_enqueue_script( 'vwcs-admin', VWCS_URL_PATH . '/js/admin.js', array('jquery'), $this->version, false );
	}

	public function notice_action($arg,$message) {
		$notice='';
		if(!empty($arg) && ( $arg=='updated' || $arg=='error') ){
			$class=($arg=='updated') ? 'success' : 'error' ;
			$notice='
				<div class="notice-'.$class.' settings-success notice is-dismissible">
					<p>'.$message.'.</p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>';
		}
		return $notice;
	}

	/*All Admin Callbacks*/
	public function add_admin_menu() {
		$svg = 'dashicons-media-document';
	    add_Menu_page('Subscription', __('Subscription','checkout-subscription'), 'manage_options', 'checkout-subscription', array($this,'settingForm'),$svg);
	}

	public function settingForm() {
		include VWCS_PLUGIN_PATH . 'view/settings.php';
	}

	public function subscriptionsavesettings() {
		global $wpdb;
		if (isset($_REQUEST['setting_nonce']) && ! wp_verify_nonce($_REQUEST['setting_nonce'], 'save_subscription_setting')) {
			wp_redirect( $_SERVER['HTTP_REFERER'] .'&updated=error&message=Hey,+Are+you+cheating+ah?');
			exit();
		}
		unset($_POST['setting_nonce']);
		unset($_POST['_wp_http_referer']);
		unset($_POST['action']);
		if(!empty($_POST)){
			foreach ($_POST as $key => $value) {
				update_option(sanitize_key($key),sanitize_text_field($_POST[$key]));
			}
		}
		wp_redirect( $_SERVER['HTTP_REFERER'] .'&updated=updated&message=Settings+Saved');
	}

	public function checkout_subscription_field($order_id) {
		include(VWCS_PLUGIN_PATH.'/view/field.php');
	}

	public function cantact_to_sendinblue_validation($data) {
		global $wpdb;

		if(isset($_POST['issubscribe']) && $_POST['issubscribe']==1){
			$email=$fname=$lname=$sms=$date=$code='';

			if(isset($_POST['billing_email'])) {
				$email=sanitize_text_field($_POST['billing_email']);
			}
			if(isset($_POST['billing_first_name'])) {
				$fname=sanitize_text_field($_POST['billing_first_name']);
			}
			if($_POST['billing_last_name']) {
				$lname=sanitize_text_field($_POST['billing_last_name']);
			}
			if(isset($_POST['billing_country'])) {
				$billing_country=sanitize_text_field($_POST['billing_country']);
				$code=WC_Countries::get_country_calling_code($billing_country);
			}
			if(isset($_POST['billing_phone'])) {
				$sms=sanitize_text_field($_POST['billing_phone']);
			}
			if (isset($_POST['min_age_woo_dob_month']) && isset($_POST['min_age_woo_dob_day']) && isset($_POST['min_age_woo_dob_year'])) {
				$date=sanitize_text_field($_POST['min_age_woo_dob_month']).'-'.sanitize_text_field($_POST['min_age_woo_dob_day']).'-'.sanitize_text_field($_POST['min_age_woo_dob_year']);
			}

			$subscribe = array(
				'email' 		=> $email,
				'listIds' 		=> array(5),
				'updateEnabled'	=> true,
				'attributes' 	=> array(
					'sms'			=> $code.$sms,
					'LASTNAME' 		=> $lname,
					'FIRSTNAME' 	=> $fname,
					'BIRTHDATE' 	=> $date
				)
			);

			$argu=array(
			    'headers'     => array(
			    	"accept"		=> "application/json",
					"api-key" 		=> get_option('sendinblue_api_key'),
					"content-type"	=> "application/json"
				),
			    'body'        => json_encode($subscribe),
			    'method'      => 'POST'
			);

			$response = wp_remote_post( 'https://api.sendinblue.com/v3/contacts', $argu );
			if(array_key_exists('body', $response)){
				$res=json_decode($response['body'],true);
				if(!empty($res['message']) && $res['code']=='invalid_parameter'){
					wc_add_notice($res['message'], 'error');
				}
			}
		}
	}

	public function add_cantact_to_sendinblue($order_id) {
		global $wpdb;

		if(isset($_POST['issubscribe']) && $_POST['issubscribe']==1){
			$email=$fname=$lname=$sms=$date=$code='';

			if(isset($_POST['billing_email'])) {
				$email=sanitize_text_field($_POST['billing_email']);
			}
			if(isset($_POST['billing_first_name'])) {
				$fname=sanitize_text_field($_POST['billing_first_name']);
			}
			if($_POST['billing_last_name']) {
				$lname=sanitize_text_field($_POST['billing_last_name']);
			}
			if(isset($_POST['billing_country'])) {
				$billing_country=sanitize_text_field($_POST['billing_country']);
				$code=WC_Countries::get_country_calling_code($billing_country);
			}
			if(isset($_POST['billing_phone'])) {
				$sms=sanitize_text_field($_POST['billing_phone']);
			}
			if (isset($_POST['min_age_woo_dob_month']) && isset($_POST['min_age_woo_dob_day']) && isset($_POST['min_age_woo_dob_year'])) {
				$date=sanitize_text_field($_POST['min_age_woo_dob_month']).'-'.sanitize_text_field($_POST['min_age_woo_dob_day']).'-'.sanitize_text_field($_POST['min_age_woo_dob_year']);
			}

			$subscribe = array(
				'email' 		=> $email,
				'listIds' 		=> array(5),
				'updateEnabled'	=> true,
				'attributes' 	=> array(
					'sms'			=> $code.$sms,
					'LASTNAME' 		=> $lname,
					'FIRSTNAME' 	=> $fname,
					'BIRTHDATE' 	=> $date
				)
			);

			$argu=array(
			    'headers'     => array(
			    	"accept"		=> "application/json",
					"api-key" 		=> get_option('sendinblue_api_key'),
					"content-type"	=> "application/json"
				),
			    'body'        => json_encode($subscribe),
			    'method'      => 'POST'
			);

			$response = wp_remote_post( 'https://api.sendinblue.com/v3/contacts', $argu );
		}
	}
}