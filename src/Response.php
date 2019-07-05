<?php

namespace Electry\ElectryNet\Utils;

use Illuminate\Http\Response as IlluminateResponse;

class Response extends IlluminateResponse
{
    use UtilsTrait;

    protected $conHeaders = ['Content-Type' => ['value' => 'application/json', 'overWrite' => true]];

    protected $noHeaders = ['X-Powered-By'];

    protected $status_code;

    public $body;

    public function __construct(array $data, $info = null, $code = 200)
    {
        parent::__construct();
        if (class_exists('Laravel\Lumen\Application')) {
            $this->constructLaravel($data, $code);
        } else {
            //Workaround, si estamos usando la libreria en otro framework distinto a Laravel o Lumen...
            $this->constructOther($info);
        }

        $this->body->append($data);
        $this->status_code = $code;
    }
    private function constructLaravel($data, $code)
    {
        $this->body = new ResponseBody
            (
            config('general.api.name'),
            config('general.api.version'),
            config('general.app.name'),
            config('general.app.version')
        );

    }
    private function constructOther(array $info)
    {
        if (!count($info) === 4) {
            throw new \Exception("Error Building ElectryResponse Instance, 'info' passed to 'new' operator isn't an array of length 4, passed an array of length " . count($info), 1);
        }
       dd($info);
        $this->body = new ResponseBody($info[0], $info[1], $info[2], $info[3]);

    }
    public function send()
    {
        foreach ($this->conHeaders as $hname => $hvalue) {
            $this->header($hname, $hvalue, true);
        }
        if (count($this->noHeaders) > 0) {
            foreach ($this->noHeaders as $header) {
                header_remove($header);
            }
        }
        return $this;
    }
    public function removeHeader($header = '')
    {
        if (!($header == '')) {
            array_merge($this->noHeaders, [$header]);
            if (key_exists($header, $this->conHeaders)) {
                unset($this->conHeaders[$header]);
            }
        }
    }
    public function replaceHeader($hname, $hval)
    {return $this->addHeader($hname, $hval, true);}
    public function addHeader($hname, $hvalue = '', $replace = false)
    {
        if (!isset($hname) || $hvalue === '') {
            return false;
        }
        if (!key_exists($hname, $this->conHeaders)) {

        }
        $this->conHeaders[$hname] = ['value' => $hval, 'overWrite' => $replace];
        return true;
    }
}

trait UtilsTrait
{

    public function toArray()
    {
        $return = [];
        foreach ($this as $key => $value) {
            if (!\is_callable($this->{$key}) && !is_null($value)) {
                $return[$key] = $value;
            }
            if (is_object($this->{$key}) && method_exists($this->{$key}, 'toArray')) {
                $return[$key] = $this->{$key}->toArray();
            }

        }
        return $return;
    }
    public function toObject($json = false)
    {
        $return = new \stdClass();
        foreach ($this as $key => $value) {
            if (!\is_callable($this->{$key}) && !is_null($value)) {
                $return->{$key} = $value;
            }
            if (is_object($this->{$key}) && method_exists($this->{$key}, 'toArray')) {
                $return->{$key} = $this->{$key}->toObject();
            }

        }
        return ($json) ? json_encode($return) : $return;
    }
}
