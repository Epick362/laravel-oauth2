<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Github extends Provider {

	public $name = 'github';

	// https://api.github.com

	public function url_authorize()
	{
		return 'https://github.com/login/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://github.com/login/oauth/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://api.github.com/user?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$opts = array(
			'http' => array(
				'method'  => 'GET',             
				'header'  => 'User-Agent: github.com/madewithlove/laravel-oauth2',
			)
		);

		$context = stream_context_create($opts);
		$user = json_decode(file_get_contents($url, false, $context));

		return array(
			'uid' => $user->id,
			'nickname' => $user->login,
			'name' => $user->name,
			'email' => $user->email,
			'urls' => array(
				'GitHub' => 'http://github.com/'.$user->login,
				'Blog' => $user->blog,
			),
		);
	}

	public function get_user(Token_Access $token)
	{
		$url = 'https://api.github.com/user?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$opts = array(
			'http' => array(
				'method'  => 'GET',             
				'header'  => 'User-Agent: github.com/madewithlove/laravel-oauth2',
			)
		);

		$context = stream_context_create($opts);
		$user = json_decode(file_get_contents($url, false, $context), true);

		return $user;
	}
}
