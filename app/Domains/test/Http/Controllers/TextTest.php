<?php

namespace App\Domains\test\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TextTest
{
    private $message;
    private $to;
    private $apiCode;
    private $apiPassword;
    private $client;

    public function __construct()
    {
        $this->message = "test 123";
        $this->to = '09167564157';
        $this->apiCode = config('itextmo.text_api_code');
        $this->apiPassword = config('itextmo.text_api_password');
        // $this->client = $client;
    }

    public function send()
    {
        $ch = curl_init();
			$itexmo = array('1' => $this->to,
                '2' => $this->message,
                '3' => $this->apiCode,
                'passwd' => $this->apiPassword,
                '6' => 'AJPONCE'
            );

			curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS,
			          http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			return curl_exec ($ch);
			curl_close ($ch);

            // if($this->client)
            // {
            //     $this->client->text_recieved_status = 1;
            //     $this->client->save();
            // }

    }
}
