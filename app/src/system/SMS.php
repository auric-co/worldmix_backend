<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15/12/2019
 * Time: 07:28
 */
class SMS
{
    protected $username = "Worldmixapp1";
    protected $password = "Fantel@17";
    protected $from;
    protected $to;
    protected $message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }


    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    public function send(){
        $token = base64_encode($this->username.":".$this->password);

        $request = json_encode(array('from' => "WorldMix", 'to' => $this->getTo(), 'text' => $this->getMessage()));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.infobip.com/sms/2/text/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic ".$token,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                )
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $err;
            } else {
                $data = json_decode($response, true);
                return $data;
            }


    }
}