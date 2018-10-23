<?php

class APIRequest
{
    // curlのインスタンス？
    private $ch;
    private $request_url;
    private $token_type;
    private $access_token;
    private $post_param_json;

    function __construct()
    {
        $this->ch = curl_init();
    }

    function set_request_url(String $url)
    {
        $this->request_url = $url;
    }

    function get_request_url()
    {
        return $this->request_url;
    }

    function set_head_parameter(String $token_type, String $access_token)
    {
        $this->token_type = $token_type;
        $this->access_token = $access_token;
    }

    function get_head_parameter()
    {
        return [ 
            'token_type' => $this->token_type,
            'access_token' => $this->access_token 
        ];
    }

    function set_post_parameter(Array $param)
    {
        $this->post_param_json = json_encode($param);
    }

    function get_post_parameter()
    {
        return $this->post_param_json;
    }

    function request_api()
    {
        $this->set_curlopt_url();
        $this->set_curlopt_returntransfer();
        $this->set_curlopt_httpheader();
        $this->set_curlopt_customrequest('PUT');
        $this->set_curlopt_postparameter();
        $json_array = $this->exec_api();
        if ( curl_errno($this->ch) )
        {
            echo '[Curl error] ' . curl_errno($this->ch), PHP_EOL;
        }
        $this->close_api();

        return $json_array;
    }

    function set_curlopt_url()
    {
        // TODO: get parameter set code
        curl_setopt($this->ch, CURLOPT_URL, $this->request_url);
    }

    function set_curlopt_returntransfer()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    // TODO: Firebase Rule need Change Authorize User And This Method need Modify.
    function set_curlopt_httpheader()
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            // 'Authorization: ' . $this->token_type . ' ' . $this->access_token,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        ]);    
    }

    function set_curlopt_customrequest(String $http_method)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $http_method);
    }

    function set_curlopt_postparameter()
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->post_param_json);
    }

    function exec_api()
    {
        $response_json = curl_exec($this->ch);
        $response_json_decode =  json_decode($response_json, true);

        if ( is_null($response_json_decode) )
        {
            return [ 'error' => 'response is null.' ];
        }
        return $response_json_decode;
    }

    function close_api()
    {
        curl_close($this->ch);
    }
}