<?php

class ApiController {
    public function __construct()
    {
    }

    protected function getPOST()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        return $data;
    }

    protected function sendErrorResponse($status, $error)
    {
        $ret = [
            'data' => [],
            'error' => [
                'message' => $error,
            ],
        ];

        http_response_code($status);
        
        return json_encode($ret);
    }

    protected function sendSucessMessage($status, $message)
    {
        $ret = [
            'data' => [
                'message' => $message,
            ]
        ];

        http_response_code($status);

        return json_encode($ret);
    }
}
