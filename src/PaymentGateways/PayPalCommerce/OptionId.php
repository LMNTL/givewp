<?php

namespace Give\PaymentGateways\PayPalCommerce;

/**
 * Class OptionId
 *
 * @since 2.8.0
 */
class OptionId {
	/**
	 * Option key name.
	 *
	 * In this option we stores PayPal access token details temporary.
	 *
	 * @since 2.8.0
	 * @var string
	 */
	public static $accessTokenOptionKey = 'temp_give_paypal_commerce_seller_access_token';

	/**
	 * Option key name.
	 *
	 * In this option we stores partner link rest api response temporary.
	 *
	 * @since 2.8.0
	 * @var string
	 */
	public static $partnerInfoOptionKey = 'temp_give_paypal_commerce_partner_link';
}
