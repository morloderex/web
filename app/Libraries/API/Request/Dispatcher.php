<?php

namespace App\Libraries\API\Request;
use GuzzleHttp\Client;

/**
* API Communication dispatcher
*/
class Dispatcher
{
	public function __call(string $method, $arguments) {
		$builder = $this->getBuilder();

		if(method_exists($builder, $method))
		{
			return $builder->$method($args);
		}
	}

	public function authenticate() {
		// Authentication logic
	}

	public function getBuilder() {
		return new Builder(
			new Client
		);
	}
}