<?php
if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}


if (!function_exists("get_grpc_repeated")) {
    function get_grpc_repeated($repeated): array
    {
        $result = [];

        $length = $repeated->count();

        for ($i = 0; $i < $length; $i++) {
            $result[] = $repeated->offsetGet($i);
        }

        return $result;

    }
}