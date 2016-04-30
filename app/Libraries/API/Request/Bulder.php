<?php

namespace App\Libraries\API\Request;

class Builder {

	protected $method = 'POST';

	protected $apiUri = 'http://api.mwow.dk';

	protected $uri;

	protected $HttpClient;

	protected $request = [];

	public function __construct(Client $client) {
		$this->HttpClient = $client;
	}

	public function post(string $url = '', array $request) {
		$this->setUri($url);
		return $this->handleHttpRequest('POST', $request);
	}

	public function get(string $url = '', array $request) {
		$this->setUri($url);
		return $this->handleHttpRequest('GET', $request);
	}

	public function patch(string $url = '', array $request) {
		$this->setUri($url);
		return $this->handleHttpRequest('PATCH', $request);
	}

	public function delete(string $url = '', array $request) {
		$this->setUri($url);
		return $this->handleHttpRequest('DELETE', $request);
	}	

	protected function handleHttpRequest(string $method, array $request) {
		$this->setRequest($request);

		return $this->HttpClient->send(
			$method,
			$this->buildUrl(),
			$this->request
		);
	}

	public function httpQuery($query) {
		if(!is_array($query))
		{
			switch ($query) {
				case is_string($query):
					if($json = json_decode($query, $associative = True))
					{
						$request = $json;
					} else {
						$request = ['body' => $query];
					}
					break;

				case is_object($query):
					if(method_exists($query, 'getAttributes'))
					{
						$request = $query->getAttributes();
					} elseif(method_exists($query, 'toArray')) {
						$request = $query->toArray();
					}
					break;
			}
		}
		$this->setRequest($request);

		return $this;
	}

	public function getRequest() {
		return $this->request;
	}

	public function setRequest(array $request) {
		$this->request = $this->buildRequest($request);

		return $this;
	}

	protected function buildRequest() {
		$body = $this->getRequest();

		return [
			'headers' => ['Accept' => 'application/json'],
			$body
		];
	}

	protected function buildUrl() {
		return $this->apiUri . $this->uri;
	}

    /**
     * Gets the value of method.
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the value of method.
     *
     * @param mixed $method the method
     *
     * @return self
     */
    protected function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Gets the value of apiUri.
     *
     * @return mixed
     */
    public function getApiUri()
    {
        return $this->apiUri;
    }

    /**
     * Sets the value of apiUri.
     *
     * @param mixed $apiUri the api uri
     *
     * @return self
     */
    protected function setApiUri($apiUri)
    {
        $this->apiUri = $apiUri;

        return $this;
    }

    /**
     * Gets the value of uri.
     *
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the value of uri.
     *
     * @param mixed $uri the uri
     *
     * @return self
     */
    protected function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Gets the value of HttpClient.
     *
     * @return mixed
     */
    public function getHttpClient()
    {
        return $this->HttpClient;
    }

    /**
     * Sets the value of HttpClient.
     *
     * @param mixed $HttpClient the http client
     *
     * @return self
     */
    protected function setHttpClient($HttpClient)
    {
        $this->HttpClient = $HttpClient;

        return $this;
    }
}