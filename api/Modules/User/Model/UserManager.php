<?php

namespace Tulic\aPiE\User;

use Firebase\JWT;

/**
 * Description of User
 *
 * @author Peter Tulic
 */
class UserManager
{

	/**
	 * @Inject
	 * @namespace Tulic\aPiE\Base
	 * @var DbAdapter;
	 */
	private $db;

	public function login($username, $password)
	{
		$token = ['token' => '', 'message' => ''];
		$adminPass = ADMIN_PASS;
		if ($username == ADMIN_NAME) {
			if (password_verify($password, $adminPass)) {
				$token['token'] = $this->createToken(1, $username, $password);
			} else {
				$token['message'] = 'ERROR' . ERROR_WRONG_PASSWORD;
			}
		} else {
			$token['message'] = 'ERROR' . ERROR_WRONG_USERNAME;
		}

		return $token;
	}

	private function createToken($id, $username, $password)
	{
		return JWT::encode([
			'id' => $id,
			'username' => $username,
			'password' => $password,
			'salt' => uniqid(),
			'valid' => time() + TOKEN_VALID_TIME * 60
		], SECRET_KEY);
	}

	/**
	 * Checks, if the token is valid, and returns a user id on true, 0 on false
	 * @param string $token
	 * @param bool $returnToken returns token object if true, if false, returns user id decoded from the token
	 * @return int
	 */
	public function isValidToken($token, $returnToken = false)
	{
		if (!$token) {
			return 0;
		}
		$decoded = JWT::decode($token, SECRET_KEY, ['HS256']);
		if ($decoded->valid > time()) {
			$login = $this->login($decoded->username, $decoded->password);
			return ($login['token'] ? ($returnToken ? $login : $decoded->id) : 0);
		}
		return 0;
	}
}
