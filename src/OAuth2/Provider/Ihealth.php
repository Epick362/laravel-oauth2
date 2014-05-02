<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;
use OAuth2\Token;
use OAuth2\Exception;

class Ihealth extends Provider
{
	public $name = 'ihealth';

	// public $uid_key = 'uid';

	public $scope = array('OpenApiActivity', 'OpenApiBG', 'OpenApiBP', 'OpenApiSleep', 'OpenApiSpO2', 'OpenApiUserInfo', 'OpenApiWeight');

	public $scope_seperator = ' ';

	protected $params_map = array(
		'scope' => 'APIName'
	);

	public function url_authorize()
	{
		return 'https://api.ihealthlabs.com:8443/api/OAuthv2/userauthorization.ashx';
	}

	public function url_access_token()
	{
		return 'https://api.ihealthlabs.com:8443/api/OAuthv2/userauthorization.ashx';
	}

	public function access($code, $options = array()) {
		$params = array(
			'client_id' 	=> $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' 	=> isset($options['grant_type']) ? $options['grant_type'] : 'authorization_code',
		);

		switch ($params['grant_type'])
		{
			case 'authorization_code':
				$params['code'] = $code;
				$params['redirect_uri'] = isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri;
			break;

			case 'refresh_token':
				$params['refresh_token'] = $code;
			break;
		}

		$response = null;
		$url = $this->url_access_token();

		switch ($this->method)
		{
			case 'GET':
				// Need to switch to Request library, but need to test it on one that works
				$url .= '?'.http_build_query($params);
				$response = file_get_contents($url);

				$return = json_decode($response, true);
			break;

			case 'POST':
				$postdata = http_build_query($params);
				$opts = array(
					'http' => array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata
					)
				);
				$_default_opts = stream_context_get_params(stream_context_get_default());
				$context = stream_context_create(array_merge_recursive($_default_opts['options'], $opts));
				$response = file_get_contents($url, false, $context);

				$return = json_decode($response, true);
			break;

			default:
				throw new Exception('Method \'' . $this->method . '\' must be either GET or POST');
		}

		if (isset($return['Error']))
		{
			throw new Exception($return['Error'], $return['ErrorCode']);
		}		

		// Converts keys to the equivalent
		$return['access_token'] = $return['AccessToken'];
		$return['expires'] = $return['Expires'];
		$return['refresh_token'] = $return['RefreshToken'];
		$return['uid'] = $return['UserID'];

		// Unsets no longer used indexes
		unset($return['AccessToken'], $return['Expires'], $return['RefreshToken'], $return['UserID']);

		switch ($params['grant_type'])
		{
			case 'authorization_code':
				return Token::factory('access', $return);
			break;

			case 'refresh_token':
				return Token::factory('refresh', $return);
			break;
		}
	}
}
