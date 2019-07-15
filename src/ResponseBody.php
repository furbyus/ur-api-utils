<?php

namespace UrApi\Utils;

class ResponseBody
{
    use UtilsTrait;

    protected $app_name;

    protected $app_version;

    protected $api_name;

    protected $api_version;

    protected $error;

    protected $result;

    protected $data;

    protected $debug;

    public function __construct($app_name = 'app_name', $app_version = 'app_version', $api_name = 'api_name', $api_version = 'api_version')
    {
        $this->app_name = $app_name;
        $this->app_version = $app_version;
        $this->api_name = $api_name;
        $this->api_version = $api_version;

        // $this->data = []; //Uncomment if data has to be required in response

        $this->error = false;

        $this->result = new ResponseResult();
    }
    public function append()
    {
        //TODO aquí va el append de los elementos opcionales que puede tener el Response.
    }
    public function getContent($data = null)
    {
        if (!$data) {
            $data = [];
        }
        $content = $this->toArray();
        return $content;
    }

}
