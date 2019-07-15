<?php

namespace UrApi\Utils;

class ResponseFactory
{
    public $obj;
    public function __construct()
    {
        $this->obj = new Response(['content'], [0, 1, 2, 3], 200);
    }
    public function make($content, $status, $headers, $info)
    {
        $this->obj = new Response($content, $info, $status);
        $this->obj->withHeaders($headers);
        return $this->get();
    }
    public function get()
    {
        
        return $this->obj;
    }

}
