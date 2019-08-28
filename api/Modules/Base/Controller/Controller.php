<?php

namespace Tulic\aPiE\Base;

/**
 * Description of Controller
 *
 * @author Peter Tulic
 */
abstract class Controller
{

	private $data = [];
	private $lockedData = [];
	private $message = '';

	/**
	 * @Inject
	 * @namespace Tulic\aPiE\Base
	 * @var Request $request
	 */
	protected $request;

	public abstract function processRequest($params = []);

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getData()
	{
		if ($this->data) {
			return $this->data;
		} else {
			return null;
		}
	}

	protected function setData($name, $value, $lock = false)
	{
		if (in_array($name, $this->lockedData)) {
			return false;
		} else {
			$this->data[$name] = $value;
			if ($lock) {
				$this->lockData($name);
			}
			return true;
		}
	}

	protected function lockData($name)
	{
		if (!in_array($name, $this->lockedData)) {
			$this->lockedData[] = $name;
			return true;
		}
		return false;
	}

	protected function isLocked($name)
	{
		return in_array($name, $this->lockedData);
	}

	protected function mapData($data)
	{
		if($data){
			foreach ($data as $key => $value) {
				$this->setData($key, $value);
			}
		}
	}

	protected function get($id)
	{ }

	protected function post($input)
	{ }

	protected function put($id, $input)
	{ }

	protected function delete($id)
	{ }
}