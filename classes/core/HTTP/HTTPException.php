<?php

namespace SkyNetFront\Core\HTTP;

use SkyNetFront\Core\View;

class HTTPException extends \Exception {

    protected $view;

    public function __construct($code = 400, \Throwable $previous = null)
    {
        parent::__construct('', $code, $previous);
    }

    public function hasView()
    {
        return isset($this->view);
    }

    public function getView()
    {
        return $this->view;
    }

}
