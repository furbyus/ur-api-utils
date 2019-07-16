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
    public function append(array $data = [], $replace = false)
    {
        global $otoa, $atoo;
        if (count($data) === 0) {
            return false;
        }
        if (!isset($this->data) || is_null($this->data)) {
            $this->data = [];
        }
        //TODO aquí va el append de los elementos opcionales que puede tener el Response.
        foreach ($data as $key => $value) {
            if (!isset($this->data[$key])) {
                //New Value
                $this->data[$key] = $value;
            } else {
                foreach ($value as $k => $v) {
                    if (isset($this->data[$key][$k])) {
                        //Replace ?
                        $this->data[$key][$k] = $replace ? $v : $this->data[$key][$k];
                    } else {
                        //Append
                        $this->data[$key][$k] = $v;
                    }
                }
            }

        }
    }
    public function getContent($data = null)
    {
        $content = $this->toArray();
        return $content;
    }

}
