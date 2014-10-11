<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Exception\ClientException;

/**
 * Reddit OAuth Provider
 *
 * @package    laravel-oauth2
 * @category   Provider
 * @author     Filip Hajek
 */

class Reddit extends Provider {
	public $name = 'reddit';
	public $scope = array('identity', 'read', 'vote', 'edit', 'history', 'save', 'submit', 'subscribe', 'mysubreddits');

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
		return 'https://ssl.reddit.com/api/v1/authorize';
	}

	/**
	* Returns the access token endpoint for the provider.
	*
	* @return  string
	*/
	public function url_access_token()
	{
		return 'https://ssl.reddit.com/api/v1/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://oauth.reddit.com/api/v1/me.json';

		$client = new Client([
			'defaults' => [
		        'headers' => [
		        	'Content-type' => 'application/x-www-form-urlencoded',
		        	'Authorization' => 'bearer ' . $token->access_token,
		        	'User-Agent' => 'Readditing.com by Epick_362'
		        ]
		    ]
		]);

		$client->get($url);

		try {

			return $response->json();
		} catch (\Exception $e) {
			return \App::abort(503);
		}
	}
}
