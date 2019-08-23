<?php

namespace UrApi\Utils;

class ResponseBody
{
    use UtilsTrait;

    protected $appName;

    protected $appVersion;

    protected $apiName;

    protected $apiVersion;

    public $error;

    protected $result;

    protected $data;

    protected $debug;

    public function __construct($appName = 'app_name', $appVersion = 'app_version', $apiName = 'api_name', $apiVersion = 'api_version', ResponseResult $result = null, $error = false)
    {
        $this->appName = $appName;
        $this->appVersion = $appVersion;
        $this->apiName = $apiName;
        $this->apiVersion = $apiVersion;

        // $this->data = []; //Uncomment if data has to be required in response

        $this->error = $error;

        $this->result = $result ?: new ResponseResult();
    }
    public function getData(){
        return $this->data;
    }
    public function resetData(){
        $this->data = [];
    }
    public function countData(){
       return count($this->data);
    }
    public function append($data = [], $replace = false)
    {
        global $otoa, $atoo;
        if(!is_array($data)){
            $data = (array) $data;
        }
        if (count($data) === 0) {
            return false;
        }
        if (!isset($this->data) || is_null($this->data)) {
            $this->data = [];
        }
        //Append de los elementos opcionales que puede tener el Response.
        foreach ($data as $key => $value) {
            if (!isset($this->{$key})) {
                //New Value
               
                $this->{$key} = $value;
            } else {
                foreach ($value as $k => $v) {
                   
                    if (isset($this->{$key}[$k])) {
                        //Replace ?
                        $this->{$key}[$k] = $replace ? $v : $this->{$key}[$k];
                    } else {
                        //Append
                        $this->{$key}[$k] = $v;
                    }
                }
            }

        }
        return true;
    }
    public function resultSet($prop,$val){
       
        if(!isset($this->result->{$prop})){
            $this->result->{$prop} = array();
        }
        $this->result->{$prop}[] = $val;
    }
    public function getContent($data = null)
    {
        $content = $this->toArray();
        return $content;
    }

}
