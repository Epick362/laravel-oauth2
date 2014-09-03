<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

/**
 * Reddit OAuth Provider
 *
 * @package    laravel-oauth2
 * @category   Provider
 * @author     Filip Hajek
 */

class Reddit extends Provider {
	public $name = 'reddit';
	public $scope = array('identity', 'read', 'vote', 'edit', 'history', 'save', 'submit', 'subscribe');

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
		$postdata = http_build_query(array(
			'access_token' => $token->access_token,
		));
		$opts = array(
			'http' => array(
				'method'  => 'GET',
				'header'  => array('Content-type: application/x-www-form-urlencoded',
									'Authorization: bearer ' . $token->access_token,
									'User-Agent: Readditing.com by Epick_362'),
				'content' => $postdata
			)
		);
		$_default_opts = stream_context_get_params(stream_context_get_default());
		$context = stream_context_create(array_merge_recursive($_default_opts['options'], $opts));

		$user = json_decode(file_get_contents($url, false, $context), true);

		return $user;
	}
}
