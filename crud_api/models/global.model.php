<?php

interface IGlobal {
    public function responsePayload($userDetails, $remarks, $message, $code);
}

class GlobalMethods implements IGlobal
{
    public function responsePayload($userDetails, $remarks, $message, $code) {
        $status = array("remarks" => $remarks, "message" => $message);
        http_response_code($code);
        return array("status" => $status, "user" => $userDetails, "confirmed_by" => "The Admin");
    }
}