<?php
/**
 * Plugin Name
 *
 * @package           Checkout Subscription
 * @author            VISIONWEB Software Solution
 * @copyright         2019 VISIONWEB Software Solution
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       Checkout Subscription
 * Plugin URI:        https://visionweb.in/
 * Description:       Make user to be subscribed in SendinBlue mailing list on the woocommerce checkout page by simple checkbox
 * Version:           1.1.0
 * Author:            VISIONWEB Software Solution
 * Text Domain:       checkout-subscription
 * License: 		  GPLv2 or later

Checkout Subscription is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Checkout Subscription is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Checkout Subscription. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wpdb;
define( 'VWCS_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'VWCS_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'VWCS_PLUGIN_BASE', dirname( plugin_basename( __FILE__ ) ) );
define( 'VWCS_VERSION', "1.1.0" );
require VWCS_PLUGIN_PATH . 'classes/class-vwcs-checkout-subscription.php';
function run_VWCS_Checkout_Subscription() {
    $plugin = new VWCS_Checkout_Subscription();
}
run_VWCS_Checkout_Subscription();