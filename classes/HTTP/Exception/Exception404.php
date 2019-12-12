<?php

namespace SkyNetFront\HTTP\Exception;

use SkyNetFront\Core\HTTP\HTTPException;
use SkyNetFront\Core\View;

class Exception404 extends HTTPException {

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(404, $previous);

        $this->view = new View('HTTP/Exception/404');
    }

}
