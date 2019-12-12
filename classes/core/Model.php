<?php

namespace SkyNetFront\Core;

use PDO;

class Model {

    protected $data;

    public function field($name, $default = null)
    {
        return isset($this->data[ $name ]) ? $this->data[ $name ] : $default;
    }

}

