<?php

namespace Stripe\StripeTaxForWooCommerce\SDK\lib\Service;

class OAuthService extends \Stripe\StripeTaxForWooCommerce\SDK\lib\Service\AbstractService {

	/**
	 * Sends a request to Stripe\StripeTaxForWooCommerce\SDK\lib's Connect API.
	 *
	 * @param 'delete'|'get'|'post'                                             $method the HTTP method
	 * @param string                                                            $path the path of the request
	 * @param array                                                             $params the parameters of the request
	 * @param array|\Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions $opts the special modifiers of the request
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\StripeObject the object returned by Stripe\StripeTaxForWooCommerce\SDK\lib's Connect API
	 */
	protected function requestConnect( $method, $path, $params, $opts ) {
		$opts          = $this->_parseOpts( $opts );
		$opts->apiBase = $this->_getBase( $opts );

		return $this->request( $method, $path, $params, $opts );
	}

	/**
	 * Generates a URL to Stripe\StripeTaxForWooCommerce\SDK\lib's OAuth form.
	 *
	 * @param null|array $params
	 * @param null|array $opts
	 *
	 * @return string the URL to Stripe\StripeTaxForWooCommerce\SDK\lib's OAuth form
	 */
	public function authorizeUrl( $params = null, $opts = null ) {
		$params = $params ?: array();

		$opts = $this->_parseOpts( $opts );
		$base = $this->_getBase( $opts );

		$params['client_id'] = $this->_getClientId( $params );
		if ( ! \array_key_exists( 'response_type', $params ) ) {
			$params['response_type'] = 'code';
		}
		$query = \Stripe\StripeTaxForWooCommerce\SDK\lib\Util\Util::encodeParameters( $params );

		return $base . '/oauth/authorize?' . $query;
	}

	/**
	 * Use an authoriztion code to connect an account to your platform and
	 * fetch the user's credentials.
	 *
	 * @param null|array $params
	 * @param null|array $opts
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\StripeObject object containing the response from the API
	 * @throws \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\OAuth\OAuthErrorException if the request fails
	 */
	public function token( $params = null, $opts = null ) {
		$params                  = $params ?: array();
		$params['client_secret'] = $this->_getClientSecret( $params );

		return $this->requestConnect( 'post', '/oauth/token', $params, $opts );
	}

	/**
	 * Disconnects an account from your platform.
	 *
	 * @param null|array $params
	 * @param null|array $opts
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\StripeObject object containing the response from the API
	 * @throws \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\OAuth\OAuthErrorException if the request fails
	 */
	public function deauthorize( $params = null, $opts = null ) {
		$params              = $params ?: array();
		$params['client_id'] = $this->_getClientId( $params );

		return $this->requestConnect( 'post', '/oauth/deauthorize', $params, $opts );
	}

	private function _getClientId( $params = null ) {
		$clientId = ( $params && \array_key_exists( 'client_id', $params ) ) ? $params['client_id'] : null;

		if ( null === $clientId ) {
			$clientId = $this->client->getClientId();
		}
		if ( null === $clientId ) {
			$msg = 'No client_id provided. (HINT: set your client_id using '
				. '`new \Stripe\StripeClient([clientId => <CLIENT-ID>
                ])`)".  You can find your client_ids '
				. 'in your Stripe\StripeTaxForWooCommerce\SDK\lib dashboard at '
				. 'https://dashboard.stripe.com/account/applications/settings, '
				. 'after registering your account as a platform. See '
				. 'https://stripe.com/docs/connect/standard-accounts for details, '
				. 'or email support@stripe.com if you have any questions.';

			throw new \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\AuthenticationException( $msg );
		}

		return $clientId;
	}

	private function _getClientSecret( $params = null ) {
		if ( \array_key_exists( 'client_secret', $params ) ) {
			return $params['client_secret'];
		}

		return $this->client->getApiKey();
	}

	/**
	 * @param array|\Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions $opts the special modifiers of the request
	 *
	 * @return \Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions
	 * @throws \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\InvalidArgumentException
	 */
	private function _parseOpts( $opts ) {
		if ( \is_array( $opts ) ) {
			if ( \array_key_exists( 'connect_base', $opts ) ) {
				// Throw an exception for the convenience of anybody migrating to
				// \Stripe\StripeTaxForWooCommerce\SDK\lib\Service\OAuthService from \Stripe\StripeTaxForWooCommerce\SDK\lib\OAuth, where `connect_base`
				// was the name of the parameter that behaves as `api_base` does here.
				throw new \Stripe\StripeTaxForWooCommerce\SDK\lib\Exception\InvalidArgumentException( 'Use `api_base`, not `connect_base`' );
			}
		}

		return \Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions::parse( $opts );
	}

	/**
	 * @param \Stripe\StripeTaxForWooCommerce\SDK\lib\Util\RequestOptions $opts
	 *
	 * @return string
	 */
	private function _getBase( $opts ) {
		return isset( $opts->apiBase ) ?
			$opts->apiBase :
			$this->client->getConnectBase();
	}
}
