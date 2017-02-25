<?php
/*
	Plugin Name: eZ Cash for Woocommerce
	Plugin URI: https://github.com/madurapa/woocommerce-ezcash
	Description: Dialog eZ Cash WooCommerce Payment Gateway allows you to accept payments via Dialog, Etisalat and Hutch mobile phones.
	Version: 1.0.2
	Author: Maduka Jayalath
	Author URI: https://github.com/madurapa
	License: GPL-3.0+
 	License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 	GitHub Plugin URI: https://github.com/madurapa/woocommerce-ezcash
*/


if (!defined('ABSPATH'))
    exit;

add_action('plugins_loaded', 'mj_wc_ezcash_init', 0);

function mj_wc_ezcash_init()
{

    if (!class_exists('WC_Payment_Gateway')) return;

    /**
     * Gateway class
     */
    class WC_Mj_EzCash_Gateway extends WC_Payment_Gateway
    {

        public function __construct()
        {

            $this->id = 'mj_wc_ezcash_gateway';
            $this->icon = apply_filters('woocommerce_ezcash_icon', plugins_url('ezcash.png', __FILE__));
            $this->has_fields = false;
            $this->order_button_text = __('Make Payment', 'woothemes');
            $this->notify_url = WC()->api_request_url('check_ezcash_response');
            $this->method_title = __('eZ Cash', 'woothemes');
            $this->method_description = __('Payment Methods Accepted: Dialog, Etisalat and Hutch Mobile Phones', 'woothemes');

            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->test_mode = $this->get_option('test_mode') === 'yes' ? true : false;

            $this->public_test_key = $this->get_option('public_test_key');
            $this->private_test_key = $this->get_option('private_test_key');

            $this->public_live_key = $this->get_option('public_live_key');
            $this->private_live_key = $this->get_option('private_live_key');

            $this->public_key = $this->test_mode ? $this->public_test_key : $this->public_live_key;
            $this->private_key = $this->test_mode ? $this->private_test_key : $this->private_live_key;

            $this->merchant_code = $this->get_option('merchant_code');
            $this->request_url = $this->get_option('request_url');

            //Actions
            add_action('woocommerce_api_check_ezcash_response', array($this, 'response_page'));
            add_action('woocommerce_receipt_mj_wc_ezcash_gateway', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // Check if the gateway can be used
            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }
        }


        /**
         * Check if the store curreny is set to LKR
         **/
        public function is_valid_for_use()
        {
            if (!in_array(get_woocommerce_currency(), array('LKR'))) {
                $this->msg = __('eZ Cash doesn\'t support your store currency, set it to Sri Lankan Rupee (Rs)', 'woothemes') . ' <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=general">here</a>';
                return false;
            }
            return true;
        }


        /**
         * Check if this gateway is enabled
         */
        public function is_available()
        {
            if ($this->enabled == "yes") {
                if (!($this->public_key && $this->private_key && $this->merchant_code && $this->request_url)) {
                    return false;
                }
                return true;
            }
            return false;
        }


        /**
         * Admin Panel Options
         **/
        public function admin_options()
        {
            echo '<h3>eZ Cash</h3>';
            echo '<p>eZ Cash WooCommerce Payment Gateway allows you to accept payments on your WooCommerce store via Dialog, Etisalat and Hutch mobile phones. To open an eZ Cash merchant account click <a href="http://www.ezcash.lk" target="_blank">here</a></p>';

            if ($this->is_valid_for_use()) {

                echo '<table class="form-table">';
                $this->generate_settings_html();
                echo '</table>';

            } else {
                echo '<div class="inline error"><p><strong>eZ Cash Payment Gateway Disabled</strong>: ' . $this->msg . '</p></div>';
            }
        }


        /**
         * Initialise Gateway Settings Form Fields
         **/
        function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woothemes'),
                    'type' => 'checkbox',
                    'label' => __('Enable eZ Cash Payment Gateway', 'woothemes'),
                    'description' => __('Enable or disable the gateway.', 'woothemes'),
                    'desc_tip' => true,
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woothemes'),
                    'type' => 'text',
                    'description' => __('The title which the user sees during checkout.', 'woothemes'),
                    'desc_tip' => false,
                    'default' => __('eZ Cash', 'woothemes')
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => __('The description which the user sees during checkout.', 'woothemes'),
                    'default' => __('Payment Methods Accepted: Dialog, Etisalat and Hutch Mobile Phones', 'woothemes')
                ),
                'merchant_code' => array(
                    'title' => __('Merchant Code', 'woothemes'),
                    'type' => 'text',
                    'description' => __('Enter your Merchant Alias.', 'woothemes'),
                    'desc_tip' => false,
                    'default' => 'TESTMERCHANT'
                ),
                'request_url' => array(
                    'title' => __('Request URL', 'woothemes'),
                    'type' => 'text',
                    'description' => __('eZ Cash server request URL.', 'woothemes'),
                    'desc_tip' => false,
                    'default' => 'https://ipg.dialog.lk/ezCashIPGExtranet/servlet_sentinal'
                ),
                'public_test_key' => array(
                    'title' => __('Public Test Key', 'woothemes'),
                    'type' => 'textarea',
                    'description' => __('Enter your Public Test Key here.', 'woothemes'),
                    'default' => ''
                ),
                'private_test_key' => array(
                    'title' => __('Private Test Key', 'woothemes'),
                    'type' => 'textarea',
                    'description' => __('Enter your Private Key here', 'woothemes'),
                    'default' => ''
                ),
                'public_live_key' => array(
                    'title' => __('Public Live Key', 'woothemes'),
                    'type' => 'textarea',
                    'description' => __('Enter your Public Live Key here.', 'woothemes'),
                    'default' => ''
                ),
                'private_live_key' => array(
                    'title' => __('Private Live Key', 'woothemes'),
                    'type' => 'textarea',
                    'description' => __('Enter your Private Live Key here.', 'woothemes'),
                    'default' => ''
                ),
                'testing' => array(
                    'title' => __('Gateway Testing', 'woothemes'),
                    'type' => 'title',
                    'description' => '',
                ),
                'test_mode' => array(
                    'title' => __('Test Mode', 'woothemes'),
                    'type' => 'checkbox',
                    'label' => __('Enable Test Mode', 'woothemes'),
                    'default' => 'no',
                    'description' => __('Test mode enables you to test payments before going live. If you ready to start receiving payment on your site, kindly uncheck this.', 'woothemes'),
                )
            );
        }

        /**
         * Process the payment and return the result
         **/
        public function process_payment($order_id)
        {
            $order = wc_get_order($order_id);
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }


        /**
         * Output for the order received page.
         **/
        public function receipt_page($order_id)
        {
            if (!is_checkout_pay_page()) {
                return;
            }

            if (is_checkout_pay_page() && get_query_var('order-pay')) {

                $order_key = urldecode($_GET['key']);
                $order_id = absint(get_query_var('order-pay'));
                $order = wc_get_order($order_id);

                if ($order->id == $order_id && $order->order_key == $order_key) {
                    $mcode = $this->merchant_code;
                    $tid = $order_id . '_' . rand();
                    $tamount = $order->order_total;
                    $rurl = $this->notify_url;
                    $sensitiveData = $mcode . '|' . $tid . '|' . $tamount . '|' . $rurl;
                    $publicKey = $this->public_key;
                    $encrypted = '';

                    if (!openssl_public_encrypt($sensitiveData, $encrypted, $publicKey))
                        wp_die(__('Failed to encrypt data', 'woothemes'));

                    $invoice = base64_encode($encrypted);

                    $html = '';
                    $html .= '<p>' . __('Thank you for your order, please click the button below to pay with Dialog, Etisalat and Hutch mobile phones using eZ Cash.', 'woothemes') . '</p>';
                    $html .= '<div id="mj-wc-ezcash-form">';
                    $html .= '<form id="order_review" method="post" action="https://ipg.dialog.lk/ezCashIPGExtranet/servlet_sentinal">';
                    $html .= '<input type="hidden" value="' . $invoice . '" name="merchantInvoice">';
                    $html .= '<button class="button alt" id="mj-wc-ezcash-payment-button">' . __('Pay Now', 'woothemes') . '</button>';
                    $html .= '&nbsp;&nbsp;<a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woothemes') . '</a>';
                    $html .= '</form>';
                    $html .= '</div>';

                    echo $html;
                }
            }
        }


        /**
         * Verify the payment response
         **/
        function response_page()
        {
            $decrypted = '';
            $encrypted = $_POST['merchantReciept'];
            $privateKey = $this->private_key;
            $encrypted = base64_decode($encrypted);

            if (!openssl_private_decrypt($encrypted, $decrypted, $privateKey)) {
                wp_die(__('Failed to encrypt data', 'woothemes'));
            } else {
                $decrypted_array = explode('|', $decrypted);
                if (is_array($decrypted_array) && count($decrypted_array) > 0) {

                    $transaction_id = $decrypted_array[0];
                    $order_id = (int)substr($transaction_id, 0, strrpos($transaction_id, '_'));
                    $status_code = $decrypted_array[1];
                    $status_description = $decrypted_array[2];
                    $transaction_amount = $decrypted_array[3];
                    $merchant_code = $decrypted_array[4];
                    $wallet_reference_id = $decrypted_array[5];

                    $order = wc_get_order($order_id);

                    if ($order && $this->merchant_code == $merchant_code && $status_code == 2) {
                        $order_total = $order->get_total();
                        if ($transaction_amount < $order_total) {
                            $order->update_status('on-hold', '');
                            add_post_meta($order_id, '_transaction_id', $transaction_id, true);
                            $order->add_order_note('Look into this order. This order is currently on hold. Reason: Amount paid is less than the order amount. Amount Paid was Rs ' . $transaction_amount . ' while the order amount is Rs ' . $order_total . '.');
                            $order->reduce_order_stock();
                            $notice = __('Thank you for shopping with us. The payment was successful, but the amount paid is not the same as the order amount. Your order is currently on-hold. Kindly contact us for more information regarding your order and payment status.', 'woothemes');
                            wc_add_notice($notice, 'notice');
                        } else {
                            $order->payment_complete($transaction_id);
                            $order->add_order_note(__('eZ Cash payment completed'));
                        }

                        add_post_meta($order_id, '_wallet_reference_id', $wallet_reference_id, true);
                        $order->add_order_note(sprintf('Transaction ID: %s', $transaction_id));
                        $order->add_order_note(sprintf('Wallet Reference ID: %s', $wallet_reference_id));

                        wc_empty_cart();
                        wp_redirect($this->get_return_url($order));
                        exit;

                    } else if ($order && $this->merchant_code == $merchant_code) {
                        $order->update_status('failed');
                        $order->add_order_note(sprintf('Transaction ID: %s', $transaction_id));
                        $order->add_order_note($status_description);
                        wc_add_notice(__('Payment error: ', 'woothemes') . $status_description, 'error');
                    }
                }
            }

            wp_redirect(wc_get_page_permalink('checkout'));
            exit;
        }


    }


    /**
     * Add eZ Cash Gateway to WC
     **/
    function mj_wc_add_ezcash_gateway($methods)
    {
        $methods[] = 'WC_Mj_EzCash_Gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'mj_wc_add_ezcash_gateway');


    /**
     * Add Settings link to the plugin entry in the plugins menu for WC below 2.1
     **/
    if (version_compare(WOOCOMMERCE_VERSION, "2.1") <= 0) {
        add_filter('plugin_action_links', 'mj_wc_ezcash_plugin_action_links', 10, 2);

        function mj_wc_ezcash_plugin_action_links($links, $file)
        {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_Mj_EzCash_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }

            return $links;
        }
    } else {

        /**
         * Add Settings link to the plugin entry in the plugins menu for WC 2.1 and above
         **/
        add_filter('plugin_action_links', 'mj_wc_ezcash_plugin_action_links', 10, 2);

        function mj_wc_ezcash_plugin_action_links($links, $file)
        {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=wc_mj_ezcash_gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }

            return $links;
        }
    }


    /**
     * Display the test mode notice
     **/
    function mj_wc_ezcash_test_mode_notice()
    {
        $ezcash_settings = get_option('woocommerce_mj_wc_ezcash_gateway_settings');
        $test_mode = $ezcash_settings['test_mode'] === 'yes' ? true : false;

        $public_test_key = $ezcash_settings['public_test_key'];
        $private_test_key = $ezcash_settings['private_test_key'];

        $public_live_key = $ezcash_settings['public_live_key'];
        $private_live_key = $ezcash_settings['private_live_key'];

        $merchant_code = $ezcash_settings['merchant_code'];
        $request_url = $ezcash_settings['request_url'];

        $public_key = $test_mode ? $public_test_key : $public_live_key;
        $private_key = $test_mode ? $private_test_key : $private_live_key;

        if ($test_mode) {
            echo '<div class="update-nag"> eZ Cash Test Mode is still enabled. Click <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=wc_mj_ezcash_gateway">here</a> to disable it when you want to start accepting live payment on your site.</div>';
        }

        // Check required fields
        if (!($public_key && $private_key && $merchant_code && $request_url)) {
            echo '<div class="error"><p>' . sprintf('Please enter your eZ Cash keys, Request URL and the Merchant Code <a href="%s">here</a> to be able to use the eZ Cash WooCommerce plugin.', admin_url('admin.php?page=wc-settings&tab=checkout&section=mj_wc_ezcash_gateway')) . '</p></div>';
        }

    }

    add_action('admin_notices', 'mj_wc_ezcash_test_mode_notice');

}