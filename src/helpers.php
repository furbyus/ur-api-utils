<?php
use Illuminate\Container\Container;

if (!function_exists('register_fn')) {
    function register_fn($fn_name, $fn)
    {
        global $$fn_name;
        $$fn_name = $fn;
    }
}
/*
 *   Example decarations of test functions sum_test, the two ways...
 */
register_fn('sum_test', function ($v1, $v2) {
    return $v1 + $v2;
});
if (!function_exists('sum_test')) {
    function sum_test($v1, $v2)
    {
        return $v1 + $v2;
    }
}

/*
 *   Helper functions
 */

/*
 *   Function response (if package is installed outside Laravel)
 */
register_fn('response', function ($content = '', $status = 200, $headers = [], $info = []) {
    $n = func_num_args();

    if ($n === 0) {
        $factory = new UrApi\Utils\ResponseFactory;
        return $factory->get();
    }
    $factory = new UrApi\Utils\ResponseFactory;
    return $factory->make($content, $status, $headers, $info);
});
if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array   $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}
/*
 *   Function $o2a (Object to Array)
 */
register_fn('o2a', function ($data) {
    return (array) $data;
});
/*
 *   Function $a2o (Array to Object)
 */
register_fn('a2o', function ($data) {
    return (object) $data;
});
/*
 *   Function uresponse helper like Illuminate/Foundation/helpers.php_>response helper, because 'response' function is already declared and it cannot be re-declared
 */
if (!function_exists('uresponse')) {
    function uresponse($content = '', $status = 200, $headers = [], $info = [])
    {
        $factory = app(UrApi\Utils\ResponseFactory::class);
       
        $n = func_num_args();

        if ($n === 0) {
            return $factory->get();
        }

        return $factory->make($content, $status, $headers, $info);

    }
}
