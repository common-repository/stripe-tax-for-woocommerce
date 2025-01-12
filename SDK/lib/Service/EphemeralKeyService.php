<?php

// File generated from our OpenAPI spec

namespace Stripe\StripeTaxForWooCommerce\SDK\lib\Service;

class EphemeralKeyService extends \Stripe\StripeTaxForWooCommerce\SDK\lib\Service\AbstractService {

	/**
	 * Invalidates a short-lived API key for a given resource.
	 *
	 * @param string                                                                 $id
	 * @param null|array                                                             $params
	 * @param null|array|\Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions $opts
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\EphemeralKey
	 * @throws \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\ApiErrorException if the request fails
	 */
	public function delete( $id, $params = null, $opts = null ) {
		return $this->request( 'delete', $this->buildPath( '/v1/ephemeral_keys/%s', $id ), $params, $opts );
	}

	/**
	 * Creates a short-lived API key for a given resource.
	 *
	 * @param null|array                                                             $params
	 * @param null|array|\Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions $opts
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\EphemeralKey
	 * @throws \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\ApiErrorException if the request fails
	 */
	public function create( $params = null, $opts = null ) {
		if ( ! $opts || ! isset( $opts['stripe_version'] ) ) {
			throw new \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\InvalidArgumentException( 'stripe_version must be specified to create an ephemeral key' );
		}

		return $this->request( 'post', '/v1/ephemeral_keys', $params, $opts );
	}
}
