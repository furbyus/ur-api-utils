<?php

if (!function_exists('register_fn')) {
    function register_fn($fn_name, $fn)
    {
        global $$fn_name;
        $$fn_name = $fn;
    }
}
register_fn('utils_test', function ($v1, $v2) {

});
if (!function_exists('response')) {
   
}

function response($content = '', $status = 200, $headers = [], $info = [])
{
    dump($info);
    $factory = new Electry\ElectryNet\Utils\ResponseFactory;

    if (func_num_args() === 0) {
        $n = func_num_args  ();
        dump($n);
        return $factory;
    }
    
    return $factory->make($content, $status, $headers, $info);
}