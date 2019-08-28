<?php

namespace Tulic\aPiE\Base;

use Tulic\aPiE\Base\Controller;
use Tulic\aPiE\Base\Utils;

/**
 * Description of RouterController
 *
 * @author Peter Tulic
 */
class RouterController extends Controller implements RouterInterface
{

	private $controller;

	/**
	 * @Inject
	 * @var DiContainer;
	 */
	private $di;

	public function processRequest($params = [])
	{
		$method = $this->request->getMethod();

		$module = Utils::dashToPascal($this->request->getNextParam());

		if (!$module) {
			$this->setData('message', 'no action available');
			return;
		}

		$controller = 'Tulic\aPiE\\' . $module . '\\' . $module . 'Controller';
		$this->controller = $this->di->getService($controller);
		if ($method == 'GET') {
			$this->get(null);
		} elseif ($method == 'POST') {
			$this->post(null);
		} elseif ($method == 'PUT') {
			$this->put(null, null);
		} elseif ($method == 'DELETE') {
			$this->delete(null);
		} else {
			$this->setData('message', 'ERROR' . ERROR_METHOD_NOT_ALLOWED);
			return;
		}
		$this->controller->processRequest();
		$this->setData('message', $this->controller->getMessage());
		$this->setData('data', $this->controller->getData());
	}

	public function run()
	{
		$this->processRequest();
	}

	public function getResult()
	{
		return $this->getData();
	}
}
