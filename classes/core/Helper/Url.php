<?php

namespace SkyNetFront\Core\Helper;

class Url {

	public function get($path = null)
    {
        $result = isset($path) ? $path : '/';

        $root = getenv('ROOT_PATH');

		if (!empty($root))
		{
			$result = $root . '/' . trim($result, '/');
        }

        $result = '/' . trim($result, '/');

		return $result;
    }

}
