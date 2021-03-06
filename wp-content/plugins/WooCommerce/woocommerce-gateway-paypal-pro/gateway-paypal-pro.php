<?php
/*
Plugin Name: WooCommerce PayPal Pro Gateway
Plugin URI: http://woothemes.com/woocommerce
Description: A payment gateway for PayPal Pro. A PayPal Pro merchant account, Curl support, and a server with SSL support and an SSL certificate is required (for security reasons) for this gateway to function.
Version: 2.1.6
Author: WooThemes
Author URI: http://woothemes.com/

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html

	PayPal Pro Docs: https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_WPP_IntegrationGuide.pdf
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '6d23ba7f0e0198937c0029f9e865b40e', '18594' );

/**
 * Localisation
 **/
load_plugin_textdomain( 'wc_paypal_pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

add_action('plugins_loaded', 'woocommerce_paypal_pro_init', 0);

function woocommerce_paypal_pro_init() {

	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
 	* Gateway class
 	**/
	class WC_Gateway_PayPal_Pro extends WC_Payment_Gateway {

		function __construct() {

			$this->id					= 'paypal_pro';
			$this->method_title 		= __('PayPal Pro', 'wc_paypal_pro');
			$this->icon 				= WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . '/images/cards.png';
			$this->has_fields 			= true;
			$this->liveurl				= 'https://api-3t.paypal.com/nvp';
			$this->testurl				= 'https://api-3t.sandbox.paypal.com/nvp';
			$this->liveurl_3ds			= 'https://paypal.cardinalcommerce.com/maps/txns.asp';
			$this->testurl_3ds			= 'https://centineltest.cardinalcommerce.com/maps/txns.asp';
			$this->avaiable_card_types 	= array(
				'GB' => array(
					'Visa' 			=> 'Visa',
					'MasterCard' 	=> 'MasterCard',
					'Maestro'		=> 'Maestro/Switch',
					'Solo'			=> 'Solo'
				),
				'US' => array(
					'Visa' 			=> 'Visa',
					'MasterCard' 	=> 'MasterCard',
					'Discover'		=> 'Discover',
					'AmEx'			=> 'American Express'
				),
				'CA' => array(
					'Visa' 			=> 'Visa',
					'MasterCard' 	=> 'MasterCard'
				)
			);
			$this->iso4217 = array(
				'AUD' => '036',
				'CAD' => '124',
				'CZK' => '203',
				'DKK' => '208',
				'EUR' => '978',
				'HUF' => '348',
				'JPY' => '392',
				'NOK' => '578',
				'NZD' => '554',
				'PLN' => '985',
				'GBP' => '826',
				'SGD' => '702',
				'SEK' => '752',
				'CHF' => '756',
				'USD' => '840'
			);

			// Load the form fields
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			// Get setting values
			$this->title 			= $this->settings['title'];
			$this->description 		= $this->settings['description'];
			$this->enabled 			= $this->settings['enabled'];
			$this->api_username 	= $this->settings['api_username'];
			$this->api_password 	= $this->settings['api_password'];
			$this->api_signature 	= $this->settings['api_signature'];
			$this->testmode 		= $this->settings['testmode'];
			$this->enable_3dsecure 	= (isset($this->settings['enable_3dsecure']) && $this->settings['enable_3dsecure']=='yes') ? true : false;
			$this->liability_shift 	= (isset($this->settings['liability_shift']) && $this->settings['liability_shift']=='yes') ? true : false;

			// 3DS
			if ( $this->enable_3dsecure ) {
				$this->centinel_pid		= $this->settings['centinel_pid'];
				$this->centinel_mid		= $this->settings['centinel_mid'];
				$this->centinel_pwd		= $this->settings['centinel_pwd'];

				if (empty($this->centinel_pid) || empty($this->centinel_mid) || empty($this->centinel_pwd))
					$this->enable_3dsecure = false;

				$this->centinel_url = ($this->testmode == "no") ? $this->liveurl_3ds : $this->testurl_3ds;
			}

			// Maestro
			if ( ! $this->enable_3dsecure ) {
				unset( $this->avaiable_card_types['GB']['Maestro'] );
			}

			// Hooks
			add_action( 'woocommerce_api_wc_gateway_paypal_pro', array(&$this, 'authorise_3dsecure') );
			add_action( 'admin_notices', array( &$this, 'ssl_check') );
			add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
		}

		/**
	 	* Check if SSL is enabled and notify the user
	 	**/
		function ssl_check() {

	     	if ($this->enabled=='no') return;

	     	// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
			if (get_option('woocommerce_force_ssl_checkout')=='no' && !class_exists('WordPressHTTPS')) :

				echo '<div class="error"><p>'.sprintf(__('PayPal Pro is enabled, but the <a href="%s">Force secure checkout</a> option is disabled; your checkout may not be secure! Please enable SSL and ensure your server has a valid SSL certificate - PayPal Pro will only work in test mode.', 'wc_paypal_pro'), admin_url('admin.php?page=woocommerce')).'</p></div>';

			endif;
		}

		/**
	     * Initialise Gateway Settings Form Fields
	     */
	    function init_form_fields() {

	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'wc_paypal_pro' ),
								'label' => __( 'Enable PayPal Pro', 'wc_paypal_pro' ),
								'type' => 'checkbox',
								'description' => '',
								'default' => 'no'
							),
				'title' => array(
								'title' => __( 'Title', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'This controls the title which the user sees during checkout.', 'wc_paypal_pro' ),
								'default' => __( 'Credit card (PayPal)', 'wc_paypal_pro' )
							),
				'description' => array(
								'title' => __( 'Description', 'wc_paypal_pro' ),
								'type' => 'textarea',
								'description' => __( 'This controls the description which the user sees during checkout.', 'wc_paypal_pro' ),
								'default' => __( 'Pay with your credit card via PayPal Website Payments Pro.', 'wc_paypal_pro' )
							),
				'testmode' => array(
								'title' => __( 'Test Mode', 'wc_paypal_pro' ),
								'label' => __( 'Enable PayPal Sandbox/Test Mode', 'wc_paypal_pro' ),
								'type' => 'checkbox',
								'description' => __( 'Place the payment gateway in development mode.', 'wc_paypal_pro' ),
								'default' => 'no'
							),
				'api_username' => array(
								'title' => __( 'API Username', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'Get your API credentials from PayPal.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'api_password' => array(
								'title' => __( 'API Password', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'Get your API credentials from PayPal.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'api_signature' => array(
								'title' => __( 'API Signature', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'Get your API credentials from PayPal.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'enable_3dsecure' => array(
								'title' => __( '3DSecure', 'wc_paypal_pro' ),
								'label' => __( 'Enable 3DSecure', 'wc_paypal_pro' ),
								'type' => 'checkbox',
								'description' => __( 'Allows UK merchants to pass 3-D Secure authentication data to PayPal for debit and credit cards. Updating your site with 3-D Secure enables your participation in the Verified by Visa and MasterCard SecureCode programs. (Required to accept Maestro)', 'wc_paypal_pro' ),
								'default' => 'no'
							),
				'centinel_pid' => array(
								'title' => __( 'Centinel PID', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'If enabling 3D Secure, enter your Cardinal Centinel Processor ID.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'centinel_mid' => array(
								'title' => __( 'Centinel MID', 'wc_paypal_pro' ),
								'type' => 'text',
								'description' => __( 'If enabling 3D Secure, enter your Cardinal Centinel Merchant ID.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'centinel_pwd' => array(
								'title' => __( 'Transaction Password', 'wc_paypal_pro' ),
								'type' => 'password',
								'description' => __( 'If enabling 3D Secure, enter your Cardinal Centinel Transaction Password.', 'wc_paypal_pro' ),
								'default' => ''
							),
				'liability_shift' => array(
								'title' => __( 'Liability Shift', 'wc_paypal_pro' ),
								'label' => __( 'Require liability shift', 'wc_paypal_pro' ),
								'type' => 'checkbox',
								'description' => __( 'Only accept payments when liability shift has occurred.', 'wc_paypal_pro' ),
								'default' => 'no'
							),

				);
	    }

	    /**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 */
		function admin_options() {
	    	?>
	    	<h3><?php _e( 'PayPal Pro', 'wc_paypal_pro' ); ?></h3>
	    	<p><?php _e( 'PayPal Pro works by adding credit card fields on the checkout and then sending the details to PayPal for verification.', 'wc_paypal_pro' ); ?></p>
	    	<table class="form-table">
	    		<?php $this->generate_settings_html(); ?>
			</table><!--/.form-table-->
	    	<?php
	    }

		/**
	     * Check if this gateway is enabled and available in the user's country
	     *
	     * This method no is used anywhere??? put above but need a fix below
	     */
		function is_available() {
			global $woocommerce;

			if ($this->enabled=="yes") :

				if ( ! is_ssl() && $this->testmode == "no" ) return false;

				// Currency check
				if (!in_array(get_option('woocommerce_currency'), array('AUD', 'CAD', 'CZK', 'DKK', 'EUR', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'USD'))) return false;

				// Required fields check
				if (!$this->api_username || !$this->api_password || !$this->api_signature) return false;

				return isset($this->avaiable_card_types[$woocommerce->countries->get_base_country()]);

			endif;

			return false;
		}

		/**
	     * Payment form on checkout page
	     */
		function payment_fields() {
			global $woocommerce;

			$available_cards = $this->avaiable_card_types[$woocommerce->countries->get_base_country()];
			?>
			<?php if ($this->testmode=='yes') : ?><p><?php _e('TEST MODE/SANDBOX ENABLED', 'wc_paypal_pro'); ?></p><?php endif; ?>
			<?php if ($this->description) : ?><p><?php echo $this->description; ?></p><?php endif; ?>
			<fieldset>
				<p class="form-row form-row-first">
					<label for="paypal_pro_cart_number"><?php _e("Credit Card number", 'wc_paypal_pro') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="paypal_pro_card_number" />
				</p>
				<p class="form-row form-row-last">
					<label for="paypal_pro_cart_type"><?php _e("Card type", 'wc_paypal_pro') ?> <span class="required">*</span></label>
					<select id="paypal_pro_card_type" name="paypal_pro_card_type" class="woocommerce-select">
						<?php foreach ($available_cards as $card => $label) : ?>
									<option value="<?php echo $card ?>"><?php echo $label; ?></options>
						<?php endforeach; ?>
					</select>
				</p>
				<div class="clear"></div>
				<p class="form-row form-row-first">
					<label for="cc-expire-month"><?php _e("Expiration date", 'wc_paypal_pro') ?> <span class="required">*</span></label>
					<select name="paypal_pro_card_expiration_month" id="cc-expire-month" class="woocommerce-select woocommerce-cc-month">
						<option value=""><?php _e('Month', 'wc_paypal_pro') ?></option>
						<?php
							$months = array();
							for ($i = 1; $i <= 12; $i++) :
							    $timestamp = mktime(0, 0, 0, $i, 1);
							    $months[date('n', $timestamp)] = date('F', $timestamp);
							endfor;
							foreach ($months as $num => $name) printf('<option value="%u">%s</option>', $num, $name);
						?>
					</select>
					<select name="paypal_pro_card_expiration_year" id="cc-expire-year" class="woocommerce-select woocommerce-cc-year">
						<option value=""><?php _e('Year', 'wc_paypal_pro') ?></option>
						<?php
							for ($i = date('y'); $i <= date('y') + 15; $i++) printf('<option value="%u">20%u</option>', $i, $i);
						?>
					</select>
				</p>
				<p class="form-row form-row-last">
					<label for="paypal_pro_card_csc"><?php _e("Card security code", 'wc_paypal_pro') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" id="paypal_pro_card_csc" name="paypal_pro_card_csc" maxlength="4" style="width:4em;" />
					<span class="help paypal_pro_card_csc_description"></span>
				</p>
				<div class="clear"></div>
			</fieldset>
			<script type="text/javascript">

				jQuery(function(){

					jQuery("form.checkout").on( 'change', 'select#paypal_pro_card_type', function(){

						var card_type = jQuery("#paypal_pro_card_type").val();
						var csc = jQuery("#paypal_pro_card_csc").parent();

						if (card_type == "Visa" || card_type == "MasterCard" || card_type == "Discover" || card_type == "AmEx" ) {
							csc.fadeIn("fast");
						} else {
							csc.fadeOut("fast");
						}

						if (card_type == "Visa" || card_type == "MasterCard" || card_type == "Discover") {
							jQuery('.paypal_pro_card_csc_description').text("<?php _e('3 digits usually found on the signature strip.', 'wc_paypal_pro'); ?>");
						} else if ( card_type == "AmEx" ) {
							jQuery('.paypal_pro_card_csc_description').text("<?php _e('4 digits usually found on the front of the card.', 'wc_paypal_pro'); ?>");
						} else {
							jQuery('.paypal_pro_card_csc_description').text('');
						}

					});

					jQuery('select#paypal_pro_card_type').change();

				});

			</script>
			<?php
		}

		/**
	     * Validate the payment form
	     */
		function validate_fields() {
			global $woocommerce;

			$card_type 			= isset($_POST['paypal_pro_card_type']) ? woocommerce_clean($_POST['paypal_pro_card_type']) : '';
			$card_number 		= isset($_POST['paypal_pro_card_number']) ? woocommerce_clean($_POST['paypal_pro_card_number']) : '';
			$card_csc 			= isset($_POST['paypal_pro_card_csc']) ? woocommerce_clean($_POST['paypal_pro_card_csc']) : '';
			$card_exp_month		= isset($_POST['paypal_pro_card_expiration_month']) ? woocommerce_clean($_POST['paypal_pro_card_expiration_month']) : '';
			$card_exp_year 		= isset($_POST['paypal_pro_card_expiration_year']) ? woocommerce_clean($_POST['paypal_pro_card_expiration_year']) : '';

			// Check card security code
			if (!ctype_digit($card_csc)) :
				$woocommerce->add_error(__('Card security code is invalid (only digits are allowed)', 'wc_paypal_pro'));
				return false;
			endif;

			if ((strlen($card_csc) != 3 && in_array($card_type, array('Visa', 'MasterCard', 'Discover'))) || (strlen($card_csc) != 4 && $card_type == 'AmEx')) :
				$woocommerce->add_error(__('Card security code is invalid (wrong length)', 'wc_paypal_pro'));
				return false;
			endif;

			// Check card expiration data
			if (
				!ctype_digit($card_exp_month) ||
				!ctype_digit($card_exp_year) ||
				$card_exp_month > 12 ||
				$card_exp_month < 1 ||
				$card_exp_year < date('y') ||
				$card_exp_year > date('y') + 20
			) :
				$woocommerce->add_error(__('Card expiration date is invalid', 'wc_paypal_pro'));
				return false;
			endif;

			// Check card number
			$card_number = str_replace(array(' ', '-'), '', $card_number);

			if (empty($card_number) || !ctype_digit($card_number)) :
				$woocommerce->add_error(__('Card number is invalid', 'wc_paypal_pro'));
				return false;
			endif;

			return true;
		}

		/**
	     * Process the payment
	     */
		function process_payment( $order_id ) {
			global $woocommerce;

			$order = new WC_Order( $order_id );

			$card_type 			= isset($_POST['paypal_pro_card_type']) ? woocommerce_clean($_POST['paypal_pro_card_type']) : '';
			$card_number 		= isset($_POST['paypal_pro_card_number']) ? woocommerce_clean($_POST['paypal_pro_card_number']) : '';
			$card_csc 			= isset($_POST['paypal_pro_card_csc']) ? woocommerce_clean($_POST['paypal_pro_card_csc']) : '';
			$card_exp_month		= isset($_POST['paypal_pro_card_expiration_month']) ? woocommerce_clean($_POST['paypal_pro_card_expiration_month']) : '';
			$card_exp_year 		= isset($_POST['paypal_pro_card_expiration_year']) ? woocommerce_clean($_POST['paypal_pro_card_expiration_year']) : '';

			// Format card expiration data
			$card_exp_month = (int) $card_exp_month;
			if ($card_exp_month < 10) :
				$card_exp_month = '0'.$card_exp_month;
			endif;

			$card_exp_year = (int) $card_exp_year;
			$card_exp_year += 2000;

			// Format card number
			$card_number = str_replace(array(' ', '-'), '', $card_number);

			/**
		     * 3D Secure Handling
		     */
			if ( $this->enable_3dsecure ) {

				if ( !class_exists( 'CentinelClient' )) include_once( 'lib/CentinelClient.php' );

				$this->clear_centinel_session();

				$centinelClient = new CentinelClient;

				$centinelClient->add("MsgType", "cmpi_lookup");
				$centinelClient->add("Version", "1.7");
				$centinelClient->add("ProcessorId", $this->centinel_pid);
				$centinelClient->add("MerchantId", $this->centinel_mid);
				$centinelClient->add("TransactionPwd", $this->centinel_pwd);
				$centinelClient->add("UserAgent", $_SERVER["HTTP_USER_AGENT"]);
				$centinelClient->add("BrowserHeader", $_SERVER["HTTP_ACCEPT"]);
				$centinelClient->add("TransactionType", 'C');

			    // Standard cmpi_lookup fields
			    $centinelClient->add('OrderNumber', $order_id);
			    $centinelClient->add('Amount', $order->order_total * 100 );
			    $centinelClient->add('CurrencyCode', $this->iso4217[get_option('woocommerce_currency')]);
			    $centinelClient->add('TransactionMode', 'S');

				// Items
				$item_loop = 0;

				if (sizeof($order->get_items())>0) {
					foreach ($order->get_items() as $item) {
						$item_loop++;
						$centinelClient->add('Item_Name_' . $item_loop, $item['name']);
						$centinelClient->add('Item_Price_' . $item_loop, number_format($order->get_item_total( $item, true, true ) * 100) );
						$centinelClient->add('Item_Quantity_' . $item_loop, $item['qty']);
						$centinelClient->add('Item_Desc_' . $item_loop, $item['id'] . ' - ' . $item['name'] );
					}
				}

			    // Payer Authentication specific fields
			    $centinelClient->add('CardNumber', $card_number);
			    $centinelClient->add('CardExpMonth', $card_exp_month);
			    $centinelClient->add('CardExpYear', $card_exp_year);

			    // Send request
			    $centinelClient->sendHttp($this->centinel_url, "5000", "15000");

			    // Save response in session
			    $_SESSION["Centinel_orderid"]   		= $order_id; // Save lookup response in session
			    $_SESSION["Centinel_cmpiMessageResp"]   = $centinelClient->response; // Save lookup response in session
			    $_SESSION["Centinel_Enrolled"]          = $centinelClient->getValue("Enrolled");
			    $_SESSION["Centinel_TransactionId"]     = $centinelClient->getValue("TransactionId");
			    $_SESSION["Centinel_OrderId"]           = $centinelClient->getValue("OrderId");
			    $_SESSION["Centinel_ACSUrl"]            = $centinelClient->getValue("ACSUrl");
			    $_SESSION["Centinel_Payload"]           = $centinelClient->getValue("Payload");
			    $_SESSION["Centinel_ErrorNo"]           = $centinelClient->getValue("ErrorNo");
			    $_SESSION["Centinel_ErrorDesc"]         = $centinelClient->getValue("ErrorDesc");
			    $_SESSION["Centinel_EciFlag"]         	= $centinelClient->getValue("EciFlag");
			    $_SESSION["Centinel_TransactionType"] 	= "C";
			    $_SESSION['Centinel_TermUrl']			= str_replace('http:', 'https:', add_query_arg('wc-api', 'WC_Gateway_PayPal_Pro', home_url('/')));

			    /******************************************************************************/
			    /*                                                                            */
			    /*                          Result Processing Logic                           */
			    /*                                                                            */
			    /******************************************************************************/

        		if ( $_SESSION['Centinel_ErrorNo'] == 0 ) {

        			if ( $_SESSION['Centinel_Enrolled'] == 'Y' ) {

				        @ob_clean();
						?>
						<html>
							<head>
								<title>3DSecure Payment Authorisation</title>
							</head>
							<body>
								<form name="frmLaunchACS" id="3ds_submit_form" method="POST" action="<?php echo $_SESSION["Centinel_ACSUrl"]; ?>">
							        <input type="hidden" name="PaReq" value="<?php echo $_SESSION["Centinel_Payload"]; ?>">
							        <input type="hidden" name="TermUrl" value="<?php echo $_SESSION['Centinel_TermUrl']; ?>">
							        <input type="hidden" name="MD" value="<?php echo urlencode(serialize(array(
							        	'card' 				=> $card_number,
							        	'type' 				=> $card_type,
							        	'csc'				=> $card_csc,
							        	'card_exp_month' 	=> $card_exp_month,
							        	'card_exp_year' 	=> $card_exp_year
							        ))); ?>">
							        <noscript>
							        	<div class="woocommerce_message"><?php _e('Processing your Payer Authentication Transaction', 'wc_paypal_pro'); ?> - <?php _e('Please click Submit to continue the processing of your transaction.', 'wc_paypal_pro'); ?>  <input type="submit" class="button" id="3ds_submit" value="Submit" /></div>
							        </noscript>
							    </form>
							    <script>
							    	document.frmLaunchACS.submit();
							    </script>
							</body>
						</html>
						<?php
						exit;

        			} elseif ( $this->liability_shift && $_SESSION['Centinel_Enrolled'] != 'N' ) {

						$woocommerce->add_error(__('Authentication unavailable. Please try a different payment method or card.','wc_paypal_pro'));
						return;

					} else {

        				// Customer not-enrolled, so just carry on with PayPal process
        				return $this->do_payment( $order, $card_number, $card_type, $card_exp_month, $card_exp_year, $card_csc, '', $_SESSION['Centinel_Enrolled'], '', $_SESSION["Centinel_EciFlag"], '' );

        			}

        		} else {
        			$woocommerce->add_error( __('Error in 3D secure authentication: ', 'wc_paypal_pro') . $_SESSION['Centinel_ErrorNo'] );
        			return;
				}

			}

			// Do payment with paypal
			return $this->do_payment( $order, $card_number, $card_type, $card_exp_month, $card_exp_year, $card_csc );
		}

		function authorise_3dsecure() {
			global $woocommerce;

			if ( !class_exists( 'CentinelClient' )) include_once( 'lib/CentinelClient.php' );

			$pares         	= (!empty($_POST['PaRes'])) ? $_POST['PaRes'] : '';
			$merchant_data 	= (!empty($_POST['MD'])) ? unserialize(urldecode($_POST['MD'])) : '';

			$order_id		= $_SESSION["Centinel_orderid"];
			$order = new WC_Order( $order_id );

		    /******************************************************************************/
		    /*                                                                            */
		    /*    If the PaRes is Not Empty then process the cmpi_authenticate message    */
		    /*                                                                            */
		    /******************************************************************************/

		    if (strcasecmp('', $pares )!= 0 && $pares != null) {

		        $centinelClient = new CentinelClient;

		        $centinelClient->add('MsgType', 'cmpi_authenticate');
		        $centinelClient->add("Version", "1.7");
				$centinelClient->add("ProcessorId", $this->centinel_pid);
				$centinelClient->add("MerchantId", $this->centinel_mid);
				$centinelClient->add("TransactionPwd", $this->centinel_pwd);
				$centinelClient->add("TransactionType", 'C');
		        $centinelClient->add('OrderId', $_SESSION['Centinel_OrderId']);
		        $centinelClient->add('TransactionId', $_SESSION['Centinel_TransactionId']);
		        $centinelClient->add('PAResPayload', $pares);

				$centinelClient->sendHttp($this->centinel_url, "5000", "15000");

		        $_SESSION["Centinel_cmpiMessageResp"]       = $centinelClient->response; // Save authenticate response in session
		        $_SESSION["Centinel_PAResStatus"]           = $centinelClient->getValue("PAResStatus");
		        $_SESSION["Centinel_SignatureVerification"] = $centinelClient->getValue("SignatureVerification");
		        $_SESSION["Centinel_ErrorNo"]               = $centinelClient->getValue("ErrorNo");
		        $_SESSION["Centinel_ErrorDesc"]             = $centinelClient->getValue("ErrorDesc");

		        $_SESSION["Centinel_EciFlag"]        		= $centinelClient->getValue("EciFlag");
		        $_SESSION["Centinel_Cavv"]         			= $centinelClient->getValue("Cavv");
		        $_SESSION["Centinel_Xid"]         			= $centinelClient->getValue("Xid");

		    } else {
		        $_SESSION["Centinel_ErrorNo"]   = "0";
		        $_SESSION["Centinel_ErrorDesc"] = "NO PARES RETURNED";
		    }

		    /******************************************************************************/
		    /*                                                                            */
		    /*                  Determine if the transaction resulted in                  */
		    /*                  an error.                                                 */
		    /*                                                                            */
		    /******************************************************************************/

			$redirect_url = $this->get_return_url( $order );

			if ( $this->liability_shift ) {

				if ( $_SESSION["Centinel_EciFlag"] == '07' || $_SESSION["Centinel_EciFlag"] == '01' ) {

					$woocommerce->add_error(__('Authentication unavailable.  Please try a different payment method or card.','wc_paypal_pro'));
					$order->update_status('failed', __('3D Secure error: No liability shift', 'wc_paypal_pro') );
					wp_redirect( $redirect_url );
					exit;

				}

			}

			if ( $_SESSION['Centinel_ErrorNo'] == "0" ) {

				if ( ($_SESSION["Centinel_PAResStatus"] == "Y" || $_SESSION["Centinel_PAResStatus"] == "A" || $_SESSION["Centinel_PAResStatus"] == "U") && $_SESSION['Centinel_SignatureVerification'] == "Y" ) {

					// If we are here we can process the card
					$this->do_payment( $order, $merchant_data['card'], $merchant_data['type'], $merchant_data['card_exp_month'], $merchant_data['card_exp_year'], $merchant_data['csc'], $_SESSION["Centinel_PAResStatus"], "Y", $_SESSION["Centinel_Cavv"], $_SESSION["Centinel_EciFlag"], $_SESSION["Centinel_Xid"] );

					$this->clear_centinel_session();
					wp_redirect( $redirect_url );
					exit;

				} else {

					$woocommerce->add_error(__('Payer Authentication failed.  Please try a different payment method.','wc_paypal_pro'));
					$order->update_status('failed', sprintf(__('3D Secure error: %s', 'wc_paypal_pro'), $_SESSION['Centinel_ErrorDesc'] ) );
					wp_redirect( $redirect_url );
					exit;

				}

			} else {

        		$woocommerce->add_error( __('Error in 3D secure authentication: ', 'wc_paypal_pro') . $_SESSION['Centinel_ErrorDesc'] );
				$order->update_status('failed', sprintf(__('3D Secure error: %s', 'wc_paypal_pro'), $_SESSION['Centinel_ErrorDesc'] ) );
				wp_redirect( $redirect_url );
				exit;

			}

		}

		function do_payment( $order, $card_number, $card_type, $card_exp_month, $card_exp_year, $card_csc, $centinelPAResStatus = '', $centinelEnrolled = '', $centinelCavv = '', $centinelEciFlag = '', $centinelXid = '' ) {

			global $woocommerce;

			$card_exp = $card_exp_month . $card_exp_year;

			// Send request to paypal
			try {
				$url = ($this->testmode == 'yes') ? $this->testurl : $this->liveurl;

				$post_data = array(
					'VERSION' 		=> '59.0',
					'SIGNATURE' 	=> $this->api_signature,
					'USER' 			=> $this->api_username,
					'PWD' 			=> $this->api_password,
					'METHOD' 		=> 'DoDirectPayment',
					'PAYMENTACTION' => 'Sale',
					'IPADDRESS' 	=> $this->get_user_ip(),
					'AMT' 			=> $order->get_total(),
					'CURRENCYCODE' 	=> get_option('woocommerce_currency'),
					'CREDITCARDTYPE'=> $card_type,
					'ACCT' 			=> $card_number,
					'EXPDATE' 		=> $card_exp,
					'CVV2' 			=> $card_csc,
					'FIRSTNAME' 	=> $order->billing_first_name,
					'LASTNAME' 		=> $order->billing_last_name,
					'STREET' 		=> $order->billing_address_1 . ' ' . $order->billing_address_2,
					'CITY' 			=> $order->billing_city,
					'STATE' 		=> $order->billing_state,
					'ZIP' 			=> $order->billing_postcode,
					'COUNTRYCODE' 	=> $order->billing_country,
					'SHIPTONAME'	=> $order->shipping_first_name . ' ' . $order->shipping_last_name,
					'SHIPTOSTREET' 	=> $order->shipping_address_1,
					'SHIPTOSTREET2' => $order->shipping_address_2,
					'SHIPTOCITY' 	=> $order->shipping_city,
					'SHIPTOSTATE' 	=> $order->shipping_state,
					'SHIPTOCOUNTRYCODE' => $order->shipping_country,
					'SHIPTOZIP' 	=> $order->shipping_postcode
				);

				/* Send Item details - thanks Harold Coronado */
				$item_loop = 0;

				if ( sizeof( $order->get_items() ) > 0 ) {

					$ITEMAMT = 0;

					foreach ( $order->get_items() as $item ) {
						if ( $item['qty'] ) {
							$post_data[ 'L_NUMBER' . $item_loop ]	= $item_loop + 1;
							$post_data[ 'L_NAME' . $item_loop ]		= $item['name'];
							$post_data[ 'L_AMT' . $item_loop ]		= $order->get_item_total( $item, true );
							$post_data[ 'L_QTY' . $item_loop ]		= $item['qty'];

							$ITEMAMT += $order->get_item_total( $item, true ) * $item['qty'];

							//$post_data[ 'L_TAXAMT' . $item_loop ]	= '0';
							$item_loop++;
						}
					}

					// Shipping
					if ( ( $order->get_shipping() + $order->get_shipping_tax() ) > 0 ) {
						$post_data[ 'L_NUMBER' . $item_loop ]	= $item_loop + 1;
						$post_data[ 'L_NAME' . $item_loop ]		= 'Shipping';
						$post_data[ 'L_AMT' . $item_loop ]		= $order->get_shipping() + $order->get_shipping_tax();
						$post_data[ 'L_QTY' . $item_loop ]		= 1;

						$ITEMAMT += $order->get_item_total( $item, true ) * $item['qty'];

						$item_loop++;
					}

					// Discount
					if ( $order->get_order_discount() > 0 ) {
						$post_data[ 'L_NUMBER' . $item_loop ]	= $item_loop + 1;
						$post_data[ 'L_NAME' . $item_loop ]		= 'Order Discount';
						$post_data[ 'L_AMT' . $item_loop ]		= $order->get_order_discount();
						$post_data[ 'L_QTY' . $item_loop ]		= 1;

						$ITEMAMT += $order->get_item_total( $item, true ) * $item['qty'];

						$item_loop++;
					}

					// Fix rounding
					if ( $ITEMAMT < $order->get_total() ) {
						$post_data[ 'L_NUMBER' . $item_loop ]	= $item_loop + 1;
						$post_data[ 'L_NAME' . $item_loop ]		= 'Rounding ammendment';
						$post_data[ 'L_AMT' . $item_loop ]		= $order->get_total() - $ITEMAMT;
						$post_data[ 'L_QTY' . $item_loop ]		= 1;
					}

					$post_data[ 'ITEMAMT' ] = $order->get_total();
				}

				/* 3D Secure */
				if ( $this->enable_3dsecure ) {

					$post_data['AUTHSTATUS3DS'] = $centinelPAResStatus;
					$post_data['MPIVENDOR3DS'] 	= $centinelEnrolled;
					$post_data['CAVV'] 			= $centinelCavv;
					$post_data['ECI3DS'] 		= $centinelEciFlag;
					$post_data['XID'] 			= $centinelXid;

				}

				$response = wp_remote_post( $url, array(
       				'method'		=> 'POST',
        			'body' 			=> $post_data,
        			'timeout' 		=> 70,
        			'sslverify' 	=> false,
        			'user-agent' 	=> 'WooCommerce'
    			));

    			if ( is_wp_error($response) ) throw new Exception(__('There was a problem connecting to the payment gateway.', 'wc_paypal_pro'));

    			if( empty($response['body']) ) throw new Exception(__('Empty Paypal response.', 'wc_paypal_pro'));

    			parse_str( $response['body'], $parsed_response );

				switch ( strtolower($parsed_response['ACK']) ) :
					case 'success':
					case 'successwithwarning':

						// Add order note
						$order->add_order_note( sprintf(__('PayPal Pro payment completed (Transaction ID: %s, Correlation ID: %s)', 'wc_paypal_pro'), $parsed_response['TRANSACTIONID'], $parsed_response['CORRELATIONID'] ) );

						// Payment complete
						$order->payment_complete();

						// Remove cart
						$woocommerce->cart->empty_cart();

						// Empty awaiting payment session
						unset($_SESSION['order_awaiting_payment']);

						// Return thank you page redirect
						return array(
							'result' 	=> 'success',
							'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order->id, get_permalink(get_option('woocommerce_thanks_page_id'))))
						);

					break;
					case 'failure':
					default:

						// Get error message
						if (isset($parsed_response['L_LONGMESSAGE0'])) $error_message = $parsed_response['L_LONGMESSAGE0'];
						elseif (isset($parsed_response['L_SHORTMESSAGE0'])) $error_message = $parsed_response['L_SHORTMESSAGE0'];
						elseif (isset($parsed_response['L_SEVERITYCODE0'])) $error_message = $parsed_response['L_SEVERITYCODE0'];

						// Payment failed :(
						$order->update_status( 'failed', sprintf(__('PayPal Pro payment failed (Correlation ID: %s). Payment was rejected due to an error: ', 'wc_paypal_pro'), $parsed_response['CORRELATIONID'] ) . '(' . $parsed_response['L_ERRORCODE0'] . ') ' . '"' . $error_message . '"' );

						$woocommerce->add_error(__('Payment error:', 'wc_paypal_pro') . ' ' . $error_message );
						return;

					break;
				endswitch;

			} catch(Exception $e) {
				$woocommerce->add_error(__('Connection error:', 'wc_paypal_pro') . ': "' . $e->getMessage() . '"');
				return;
			}
		}


		/**
	     * Get user's IP address
	     */
		function get_user_ip() {
			return (isset($_SERVER['HTTP_X_FORWARD_FOR']) && !empty($_SERVER['HTTP_X_FORWARD_FOR'])) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
		}

		function clear_centinel_session() {
	        unset($_SESSION['Message']);
	        foreach($_SESSION as $key => $value) {
	            if(preg_match("/^Centinel_.*/", $key) > 0) {
	                unset($_SESSION[$key]);
	            }
	        }
	    }

	}

	/**
 	* Add the Gateway to WooCommerce
 	**/
	function add_paypal_pro_gateway($methods) {
		$methods[] = 'WC_Gateway_PayPal_Pro';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_paypal_pro_gateway' );
}
