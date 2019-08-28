<?php

namespace Tulic\aPiE\User;

/**
 * Description of User
 *
 * @author Peter Tulic
 */
class User
{

	/**
	 * @Inject
	 * @var UserManager 
	 */
	private $userMan;

	/**
	 * @Inject
	 * @namespace Tulic\aPiE\Base
	 * @var Request
	 */
	private $request;

	private $id = 0;
	private $token = '';
	private $loaded = false;

	public function getId()
	{
		$this->loadProps();
		return $this->id;
	}

	private function loadProps()
	{
		if ($this->loaded) {
			return;
		}
		$auth = $this->request->getAuthorization();
		if ($auth) {
			$this->token = $auth;
		}
		$this->id = $this->userMan->isValidToken($this->token);
		
		$this->loaded = true;
	}
}
