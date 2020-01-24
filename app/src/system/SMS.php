<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15/12/2019
 * Time: 07:28
 */
class SMS
{

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

    public function smsSend(){
        $token = base64_encode("Worldmixapp1".":"."Fantel@17");

        $request = json_encode(array('from' => "Worldmix", 'to' => $this->getTo(), 'text' => $this->getMessage()));
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
        $response = json_decode(curl_exec($curl), true);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error){
            return json_encode(array("success" => false, "error" => $error));
        }else{
            if ($response){
                return json_encode(array('success' => true, 'response' => json_encode($response)));
            }else{
                return $response;
            }
        }


    }
}