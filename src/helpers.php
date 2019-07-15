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

    $n = func_num_args();

    if ($n === 0) {
        print 'n=0';
        $factory = new UrApi\Utils\ResponseFactory;
        return $factory->get();
    }
    $factory = new UrApi\Utils\ResponseFactory;
    return $factory->make($content, $status, $headers, $info);
}
