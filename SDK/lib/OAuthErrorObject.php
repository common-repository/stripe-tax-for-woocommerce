<?php

namespace Stripe\StripeTaxForWooCommerce\SDK\lib;

/**
 * Class OAuthErrorObject.
 *
 * @property string $error
 * @property string $error_description
 */
class OAuthErrorObject extends StripeObject {

	/**
	 * Refreshes this object using the provided values.
	 *
	 * @param array                                 $values
	 * @param null|array|string|Util\RequestOptions $opts
	 * @param bool                                  $partial defaults to false
	 */
	public function refreshFrom( $values, $opts, $partial = false ) {
		// Unlike most other API resources, the API will omit attributes in
		// error objects when they have a null value. We manually set default
		// values here to facilitate generic error handling.
		$values = \array_merge(
			array(
				'error'             => null,
				'error_description' => null,
			),
			$values
		);
		parent::refreshFrom( $values, $opts, $partial );
	}
}
