<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TextService
{
    private $message;
    private $to;
    private $apiCode;
    private $apiPassword;
    private $client;
    private $sender;
    public function __construct($sender, $message, $to, $client = null)
    {
        $this->message = $message;
        $this->to = $to;
        $this->apiCode = config('itextmo.text_api_code');
        $this->apiPassword = config('itextmo.text_api_password');
        $this->client = $client;
        $this->sender = $sender;
    }

    public function send()
    {
        $ch = curl_init();

        $itexmo = array('1' => $this->to,
            '2' => $this->message,
            '3' => $this->apiCode,
            'passwd' => $this->apiPassword,
            '6' => $this->sender
        );

			curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS,
			          http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			return curl_exec ($ch);
			curl_close ($ch);

            if($this->client)
            {
                $this->client->text_recieved_status = 1;
                $this->client->save();
            }

    }
}
