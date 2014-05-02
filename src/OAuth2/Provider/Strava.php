<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

/*
 * Strava API credentials: https://www.strava.com/settings/api
 * Strava API docs: http://strava.github.io/api/v3/oauth/
 */


/**
 * Strava OAuth Provider
 *
 * @package    laravel-oauth2
 * @category   Provider
 * @author     Andreas Creten
 */

class Strava extends Provider {
	/**
	* @var  string  provider name
	*/
	public $name = 'strava';

	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';

	/**
	 * Returns the authorization URL for the provider.
	 *
	 * @return  string
	 */
	public function url_authorize()
	{
		return 'https://www.strava.com/oauth/authorize';
	}

	/**
	* Returns the access token endpoint for the provider.
	*
	* @return  string
	*/
	public function url_access_token()
	{
		return 'https://www.strava.com/oauth/token';
	}
}
