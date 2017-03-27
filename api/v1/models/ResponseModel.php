<?php

/**
 * Created by PhpStorm.
 * User: blaze
 * Date: 3/26/2017
 * Time: 7:44 PM
 */
class ResponseModel
{

    public $status;
    public $message;
    public $explain;
    public $data;

    public function __construct($status, $message, $explain, $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->explain = $explain;
        $this->data = $data;
    }
}