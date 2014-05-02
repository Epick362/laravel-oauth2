<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Foursquare extends Provider
{
	public $name = 'foursquare';

	public $method = 'POST';

	public function url_authorize()
	{
		return 'https://foursquare.com/oauth2/authorize';
	}

	public function url_access_token()
	{
		return 'https://foursquare.com/oauth2/access_token';
	}

	public function get_user_info(Token_Access $token)
	{ 
		$url = 'https://api.foursquare.com/v2/users/self?'.http_build_query(array(
			'oauth_token' => $token->access_token,
			'v' => '20140104',
		));

		$user = json_decode(file_get_contents($url));

		if (isset($user) && ($user->meta->code == 200))
		{
			return array(          
				'uid' => $user->response->user->id,
				'firstname' => $user->response->user->firstName,
				'lastname' => $user->response->user->lastName,
				'name' => sprintf('%s %s', $user->response->user->firstName, $user->response->user->lastName),
				'email' => (isset($user->response->user->contact->email)) ? $user->response->user->contact->email : '',
			);
		} 
		else
		{
			return null;
		}
	}

	public function get_user(Token_Access $token)
	{

		$url = 'https://api.foursquare.com/v2/users/self?'.http_build_query(array(
			'oauth_token' => $token->access_token,
			'v' => '20140104',
		));

		$user = json_decode(file_get_contents($url),true);

		if (isset($user) && ($user['meta']['code'] == 200))
		{
			return $user['response']['user'];
		} 
		else
		{
			return null;
		}
	}
}
