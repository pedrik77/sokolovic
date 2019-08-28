<?php

namespace Tulic\aPiE\User;

use Tulic\aPiE\Base\Controller;

/**
 * Description of UserController
 *
 * @author Peter Tulic
 */
class UserController extends Controller
{

	/**
	 * @Inject
	 * @var UserManager
	 */
	private $userMan;

	/**
	 * @Inject
	 * @var User
	 */
	private $user;
	private $action = '';

	public function processRequest($params = [])
	{
		$method = $this->request->getMethod();
		$input = $this->request->getInput();
		$id = $this->request->getNextParam();

		$this->action = $id;
		if ($method == 'GET') {
			$this->get($id);
		} elseif ($method == 'POST') {
			$this->post($input);
		} elseif ($method == 'PUT') {
			$this->put($id, $input);
		} elseif ($method == 'DELETE') {
			$this->delete($id);
		}
	}

	protected function post($input)
	{
		$token = null;
		if ($this->action == 'login') {
			$token = $this->userMan->login($input['username'], $input['password']);
		} elseif ($this->action == 'renew-token') {
			$token = $this->userMan->isValidToken($this->request->getAuthorization(), true);
		}
		if ($token) {
			if ($token['token']) {
				$this->setData('token', $token['token']);
			}
			if ($token['message']) {
				$this->setMessage($token['message']);
			}
		} else {
			$this->setData('token', 0);
		}
	}
}