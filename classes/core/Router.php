<?php

namespace SkyNetFront\Core;

class Router {

	protected $rules;

	public function __construct()
	{
		$this->rules = [];
	}

	public function rule($path, array $params)
	{
		$path = trim(trim($path), '/');

		if (isset($this->rules[ $path ]))
		{
			throw new \Exception("Overriding of the rule '{$path}'");
		}

		$this->rules[ $path ] = $params;
	}

	public function hasRule($path, $trim = true)
	{
		if ($trim)
		{
			$path = trim(trim($path), '/');
		}

		return isset($this->rules[ $path ]);
	}

	public function getRule($path)
	{
		$path = trim(trim($path), '/');

		return $this->hasRule($path, false)
			? $this->rules[ $path ]
			: null;
	}

	public function process()
	{
		$routeConfig = [
			'controller' => 'Index',
			'action' => 'index'
		];

		$path = trim($_SERVER['REQUEST_URI'], '/');

		$query = null;
		$question = strpos($path, '?');

		if ($question !== false)
		{
			$query = substr($path, $question + 1);
			$path = trim(substr($path, 0, $question), '/');
		}

		$root = getenv('ROOT_PATH');

		if (!empty($root))
		{
			$path = preg_replace('#^' . getenv('ROOT_PATH') . '#', '', $path);
			$path = trim($path, '/');
		}

		// Check if whole path is defined as rule

		if ($this->hasRule($path))
		{
			$routeConfig = array_merge($routeConfig, $this->getRule($path));
			$this->processRoute($routeConfig, $path);
			return;
		}

		// Then maybe this is a controller with an action (maybe with param)

		if (!empty($path))
		{
			$routeParts = explode('/', $path);

			$lastPart = array_pop($routeParts);
			if (!empty($routeParts)) $midPart = array_pop($routeParts);

			if (!empty($routeParts))
			{
				$routeConfig['controller'] = implode('/', $routeParts);
				$routeConfig['action'] = $midPart;
				$routeConfig['param'] = $lastPart;
			}
			else
			{
				if (isset($midPart))
				{
					$routeConfig['action'] = $midPart;
					$routeConfig['param'] = $lastPart;
				}
				else
				{
					$routeConfig['action'] = $lastPart;
				}
			}
		}

		$this->processRoute($routeConfig, $path);
	}

	protected function processRoute(array $config, $path)
	{
		$controllerName = $config['controller'];
		$actionName = $config['action'];
		$param = isset($config['param']) ? $config['param'] : null;

		// Include controller

		$controllerParts = $this->makeUnifiedPathArray($controllerName);
		$controllerPath = $this->makeUnifiedPathFromUnifiedArray($controllerParts, PATH_APP . 'controller/');

		if (!file_exists($controllerPath))
		{
			$this->errorPage404();
			return;
		}

		// Create session

		$session = new Session();
		$session->start();

		// Create controller

		$controllerName = $this->makeNameFromUnifiedArray($controllerParts, 'Controller');
		$controller = new $controllerName($session);

		// Prepare

		$controller->prepare();

		// Execute action

		$actionName = 'action_' . $actionName;

		if (method_exists($controller, $actionName))
		{
			$controller->$actionName($param);
		}
		else
		{
			$this->errorPage404();
		}
	}

	protected function makeUnifiedPathArray($path)
	{
		$parts = explode('/', $path);

		return array_map('ucfirst', $parts);
	}

	protected function makeUnifiedPathFromUnifiedArray(array $parts, $root = PATH_ROOT)
	{
		return $root . implode('/', $parts) . '.php';
	}

	protected function makeNameFromUnifiedArray(array $parts, $namespace = null)
	{
		return '\\SkyNetFront\\' . (isset($namespace) ? $namespace . '\\' : '') . implode('\\', $parts);
	}

	protected function errorPage404()
	{
		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
		// header('Location:' . $host . '404');
	}
}
