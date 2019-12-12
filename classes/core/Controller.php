<?php

namespace SkyNetFront\Core;

use SkyNetFront\Core\Session;

class Controller {

	protected $session;

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	public function prepare()
	{
		// Empty for now
	}

}
