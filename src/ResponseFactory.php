<?php

namespace Electry\ElectryNet\Utils;

class ResponseFactory
{

    public function __construct()
    {
        return new Response([], [], 200);
    }
    public function make($content, $status, $headers, $info)
    {
       dump($content);
        $response = new Response($content, $info, $status);
        $response->withHeaders($headers);
        return $response;
    }

}
