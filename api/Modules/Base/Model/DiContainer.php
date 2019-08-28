<?php

namespace Tulic\aPiE\Base;

/**
 * Description of DiContainer
 *
 * @author Peter Tulic
 */
class DiContainer
{

	private $services = [];

	public function __construct() {
		$this->addService($this);
	}

	public function addService($service)
	{
		$this->services[get_class($service)] = $service;
	}

	public function getService($fullClassName)
	{
		if (isset($this->services[$fullClassName])) {
			return $this->services[$fullClassName];
		}
		$service = new $fullClassName;

		$this->services[$fullClassName] = $service;
		$reflection = new \ReflectionClass($service);
		$this->injectDependencies($reflection, $service);

		return $service;
	}

	private function injectDependencies($reflection, $service)
	{
		$properties = $reflection->getProperties();
		foreach ($properties as $property) {
			$docComment = $property->getDocComment();
			if (strpos($docComment, '@Inject') !== false) {
				$matches = [];
				if (!preg_match('~@var\s+([A-Za-z0-9\\\_\-]+)~u', $docComment, $matches)) {
					throw new \Exception('Can\'t read data type of property ' . $property->getName());
				}
				$type = $matches[1];
				if (!preg_match('~@namespace\s+([A-Za-z0-9\\\_\-]+)~u', $docComment, $matches)) {
					$namespace =  '';
					if ($reflection->inNamespace()) {
						$namespace = $reflection->getNamespaceName();
					}
				} else {
					$namespace = $matches[1];
				}
				$fullName = ($namespace ? $namespace . '\\' : '') . $type;
				$property->setAccessible(true);
				$property->setValue($service, $this->getService($fullName));
			}
		}
	}
}
