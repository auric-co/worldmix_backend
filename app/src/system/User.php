<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 11/10/2019
 * Time: 11:19 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';
include_once dirname(__FILE__) . '/SMS.php';
use Firebase\JWT\JWT;
class User extends System
{

    function repost(){

    }

    function depost(){

    }

    function checkLogin(){

    }

    function sendRegEmail($code){
        $this->mail->addAddress($this->getEmail());
        $this->mail->setFrom("admin@worldmixapp.com");
        $this->mail->Subject = "Confirm account";
        $this->mail->isHTML(true);
        $body = '
                    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
                    <title>Activate Account - Worldmix</title>
                    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
                    <style type="text/css">
                    html { -webkit-text-size-adjust: none; -ms-text-size-adjust: none;}
                    
                        @media only screen and (min-device-width: 750px) {
                            .table750 {width: 750px !important;}
                        }
                        @media only screen and (max-device-width: 750px), only screen and (max-width: 750px){
                          table[class="table750"] {width: 100% !important;}
                          .mob_b {width: 93% !important; max-width: 93% !important; min-width: 93% !important;}
                          .mob_b1 {width: 100% !important; max-width: 100% !important; min-width: 100% !important;}
                          .mob_left {text-align: left !important;}
                          .mob_soc {width: 50% !important; max-width: 50% !important; min-width: 50% !important;}
                          .mob_menu {width: 50% !important; max-width: 50% !important; min-width: 50% !important; box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2); }
                          .mob_center {text-align: center !important;}
                          .top_pad {height: 15px !important; max-height: 15px !important; min-height: 15px !important;}
                          .mob_pad {width: 15px !important; max-width: 15px !important; min-width: 15px !important;}
                          .mob_div {display: block !important;}
                        }
                       @media only screen and (max-device-width: 550px), only screen and (max-width: 550px){
                          .mod_div {display: block !important;}
                       }
                        .table750 {width: 750px;}
                    </style>
                    </head>
                    <body style="margin: 0; padding: 0;">
                    
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
                        <tr>
                        <td align="center" valign="top">   			
                            <!--[if (gte mso 9)|(IE)]>
                             <table border="0" cellspacing="0" cellpadding="0">
                             <tr><td align="center" valign="top" width="750"><![endif]-->
                            <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750" style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
                                <tr>
                                   <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                                    <td align="center" valign="top" style="background: #ffffff;">
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                                         <tr>
                                            <td align="right" valign="top">
                                               <div class="top_pad" style="height: 25px; line-height: 25px; font-size: 23px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                         <tr>
                                            <td align="left" valign="top">
                                               <div style="height: 39px; line-height: 39px; font-size: 37px;">&nbsp;</div>
                                               <a href="#" target="_blank" style="display: block; max-width: 128px;">
                                                  <img src="http://www.worldmixapp.com/uploads/system/logo.jpg" alt="img" width="128" border="0" style="display: block; width: 128px;" />
                                               </a>
                                               <div style="height: 73px; line-height: 73px; font-size: 71px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                         <tr>
                                            <td align="left" valign="top">
                                               <font face="\'Source Sans Pro\', sans-serif" color="#1a1a1a" style="font-size: 52px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">
                                                  <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 52px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">Hey '.$this->getName().',</span>
                                               </font>
                                               <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                                               <font face="\'Source Sans Pro\', sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                                  <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">Welcome to Worldmix. Your activation code is as follows</span>
                                               </font>
                                               <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                               <font face="\'Source Sans Pro\', sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                                  <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">
                                                  
                                                  <strong>Code: <i style="color: blue;">'.$code.'</i></strong><br>
                                                  <br>
                                                  <hr>
                                                  <br>
                                                  <span style="color: red; ">Thank you for registering for the Worldmix App experience. Buy, Sell, Lease, Rent, search and find!</span>
                                                  </span>
                                               </font>
                                               <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                                              
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="90%" style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                                         <tr>
                                            <td align="left" valign="top">
                                               <div style="height: 15px; line-height: 15px; font-size: 13px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                         <tr>
                                            <td align="center" valign="top">
                                               <!--[if (gte mso 9)|(IE)]>
                                               <table border="0" cellspacing="0" cellpadding="0">
                                               <tr><td align="center" valign="top" width="50"><![endif]-->
                                               <div style="display: inline-block; vertical-align: top; width: 50px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td align="center" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <div style="display: block; max-width: 50px;">
                                                              <img src="http://www.worldmixapp.com/img/icons/android-chrome-512x512.png" alt="img" width="50" border="0" style="display: block; width: 50px;" />
                                                           </div>
                                                        </td>
                                                     </tr>
                                                  </table>
                                               </div><!--[if (gte mso 9)|(IE)]></td><td align="left" valign="top" width="390"><![endif]--><div class="mob_div" style="display: inline-block; vertical-align: top; width: 62%; min-width: 260px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                                        <td class="mob_center" align="left" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <font face="\'Source Sans Pro\', sans-serif" color="#000000" style="font-size: 19px; line-height: 23px; font-weight: 600;">
                                                              <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #000000; font-size: 19px; line-height: 23px; font-weight: 600;">Christopher Nyandoro</span>
                                                           </font>
                                                           <div style="height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</div>
                                                           <font face="\'Source Sans Pro\', sans-serif" color="#7f7f7f" style="font-size: 19px; line-height: 23px;">
                                                              <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #7f7f7f; font-size: 19px; line-height: 23px;">Administrator</span>
                                                           </font>
                                                        </td>
                                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                                     </tr>
                                                  </table>
                                               </div><!--[if (gte mso 9)|(IE)]></td><td align="left" valign="top" width="177"><![endif]--><div style="display: inline-block; vertical-align: top; width: 177px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td align="center" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <div style="display: block; max-width: 177px;">
                                                              <img src="http://www.worldmixapp.com/uploads/system/logo.jpg" alt="img" width="177" border="0" style="display: block; width: 177px; max-width: 100%;" />
                                                           </div>
                                                        </td>
                                                     </tr>
                                                  </table>
                                               </div>
                                               <!--[if (gte mso 9)|(IE)]>
                                               </td></tr>
                                               </table><![endif]-->
                                               <div style="height: 30px; line-height: 30px; font-size: 28px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                                         <tr>
                                            <td align="center" valign="top">
                                               <div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
                                               <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                                  <tr>
                                                     <td align="center" valign="top">
                                                        <font face="\'Source Sans Pro\', sans-serif" color="#868686" style="font-size: 17px; line-height: 20px;">
                                                           <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #868686; font-size: 17px; line-height: 20px;">Copyright &copy; '.date('Y').' Worldmix. All&nbsp;Rights&nbsp;Reserved.</span>
                                                        </font>
                                                        <div style="height: 3px; line-height: 3px; font-size: 1px;">&nbsp;</div>
                                                        <font face="\'Source Sans Pro\', sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 20px;">
                                                           <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px;"><a href="#" target="_blank" style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">admin@ultramedhealth.com</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#" target="_blank" style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">(263) 712 103 865</a> &nbsp;&nbsp;</span>
                                                        </font>
                                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                                     </td>
                                                  </tr>
                                               </table>
                                            </td>
                                         </tr>
                                      </table>  
                    
                                   </td>
                                   <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                                </tr>
                             </table>
                             <!--[if (gte mso 9)|(IE)]>
                             </td></tr>
                             </table><![endif]-->
                          </td>
                       </tr>
                    </table>
                    </body>
                    </html>
        ';

        $this->mail->Body = $body;

        if ($this->mail->send()) {
            return true;
        }else{
            return false;
        }
    }

    public function login(){
        try{
            $u = $this->getMsisdn();
            $sql = "SELECT COUNT(*) FROM `users` WHERE `msisdn` = '$u'";

            if ($res = $this->pdo->query($sql)) {

                if ($res->fetchColumn() == 1) {
                    $query = "SELECT * FROM `users` WHERE `msisdn`= :username";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute(array(':username' => $this->getMsisdn()));


                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $hash = $row['password'];

                    if (password_verify($this->getPassword(), $hash)) {


                        if ($row['status'] == 0){

                            $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account  not  updated'));
                            return $data;
                        }elseif ($row['status'] == 1) {
                            $paylod = [
                                'iat' => time(),
                                'iss' => $this->domain(),
                                'exp' => time() + (60*60*8),
                                'userId' => $row['id']
                            ]; //expires in 8 hours
                            $details = array(
                                'user' => [
                                    'name' => $row['name'],
                                    'surname' => $row['surname'],
                                    'email' => $row['email'],
                                    'msisdn' => $row['msisdn'],
                                    'address' => $row['address'],
                                    'town' => $row['town'],
                                    'country'=> $row['country'],
                                    'profile' => $row['profile_image'],
                                    'status' => $row['status']
                                ]
                            );

                            $token = JWT::encode($paylod, SECRETE_KEY);
                            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Login successful', 'token' => $token, 'details' => $details);
                            return $data;
                        }elseif ($row['status'] == 2){
                            //suspended
                            $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account Suspended'));
                            return $data;
                        }else{
                            //blacklisted
                            $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account Blacklisted'));
                            return $data;
                        }
                    } else {
                        $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Login Credentials'));
                        return $data;
                    }
                }else {
                    $data = array('success' => false, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account not found'));
                    return $data;
                }
            }else{
                $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => 'Internal Server Error'));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function changePassword(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $sql = "SELECT COUNT(*) FROM `users` WHERE `id`= '$id'";

            if ($res = $this->pdo->query($sql)) {

                if ($res->fetchColumn() == 1) {

                    $query = "SELECT `password` FROM `users` WHERE `id`= :username";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute(array(':username' => $id));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $hash = $row['password'];

                    if (password_verify($this->getPassword(), $hash)) {

                        $pwd = password_hash($this->getNewPassword(), PASSWORD_BCRYPT, array("cost" => 10));

                        $sql = "UPDATE `users` SET `password`= '$pwd' WHERE `id`= '$id'";
                        $qr = mysqli_query($this->con, $sql);
                        if ($qr){
                            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Change Password successful');
                            return $data;
                        }else{
                            return array(
                                'success' => false,
                                'statusCode' => INTERNAL_SERVER_ERROR,
                                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Change Password failed")
                            );
                        }

                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => FORBIDEN,
                            'error' => array('type' => 'LOGIN_ERROR', 'message' => "Invalid Password")
                        );
                    }

                }else{
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array('type' => 'ACOUNT_ERROR', 'message' => "Damaged account")
                    );
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Internal Server Error")
                );
            }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function Register(){

        $u = $this->getMsisdn();
        $name = $this->getName();
        $sname = $this->getLastName();
        $ccode = $this->getCountryCode();
        $country = $this->getCountry();
        $pwd = password_hash($this->getPassword(), PASSWORD_BCRYPT, array("cost" => 10));

        try{

            $check = "SELECT COUNT(*) FROM `users` WHERE `msisdn` = '$u'";

            if ($res = $this->pdo->query($check)) {

                if ($res->fetchColumn() !=0) {
                    $data = array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Mobile number already exists')
                    );

                    return $data;
                }else {
                    $code = rand(1000, 9999);
                    $sql = "INSERT INTO `users`(`id`, `email`, `password`, `name`, `surname`, `msisdn`, `town`, `country_code`, `country`, `address`, `profile_image`, `token`, `status`) VALUES ('','','$pwd','$name','$sname','$u','','$ccode','$country','','','$code','0')";
                    $insert = mysqli_query($this->con, $sql);
                    if ($insert){
                        $message = "Thank you for registering with WorldMix. Here is your activation code: ".$code;
                        $this->sms->setMessage($message);
                        $this->sms->setTo($u);
                        $sent = $this->sms->smsSend();
                        $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully. Please activate your account', 'sms'=> $sent);
                        return $data;

                    }else{
                        $data = array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array(
                                'type' => "SERVER_ERROR",
                                'message' => 'Account creation failed. Error: '. mysqli_error($this->con))
                        );

                        return $data;
                    }
                }
            }else {
                $data = array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Internal Server Error')
                );

                return $data;
            }

        }catch (\Exception $exception){
            $data = array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Error: '.$exception->getMessage())
            );

            return $data;
        }


    }

    public  function activateAccount(){
        $code = $this->getCode();
        $u = $this->getMsisdn();

        $sql = "UPDATE `users` SET `status`= '1' WHERE `token` ='$code' AND `msisdn` = '$u'";
        $qry = mysqli_query($this->con, $sql);

        if ($qry){
            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Account activated successfully.');
            return $data;
        }else{
            $data = array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Account activation failed. Error: '. mysqli_error($this->con))
            );

            return $data;
        }
    }

    public function getActivationCode(){
        $user = $this->getMsisdn();
        $code = rand(1000, 9999);
        $check = "SELECT COUNT(*) FROM `users` WHERE `msisdn` = '$user' AND `status` = '0'";

        if ($res = $this->pdo->query($check)) {

            if ($res->fetchColumn() == 1) {
                $sql = "UPDATE `users` SET `code` = '$code' WHERE `msisdn` = '$user' AND `status` = '0'";
                $qry = mysqli_query($this->con, $sql);
                if ($qry){
                    $message = "Thank you for registering with WorldMix. Here is your activation code: ".$code;

                    $this->sms->setMessage($message);
                    $this->sms->setTo($user);
                    $sent = $this->sms->smsSend();
                    if($sent['success'] == true){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Code has been sent');
                        return $data;
                    }else{
                        return  array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'code'=> $code, 'message' => 'Code has been updated, however text could not be sent. Please contact admin');
                    }

                }else{
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Code could not be retrieved. Account might be activated already')
                    );
                }
            }elseif ($res->fetchColumn() == 0){
                return array(
                    'success' => false,
                    'statusCode' => NOT_FOUND,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Account not found.')
                );
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Code could be retrieved.')
                );

            }
        }else{
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Internal Server error')
            );

        }

    }

    public function getDetails(){

        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
            $qry = mysqli_query($this->con, $sql);

            if (mysqli_num_rows($qry) == 1){
                $data = "";
                while ($row = mysqli_fetch_assoc($qry)){

                    $data = array(
                        'success' => true,
                        'statusCode' => SUCCESS_RESPONSE,
                        'user' =>[
                            'name' => $row['name'],
                            'surname' => $row['surname'],
                            'email' => $row['email'],
                            'msisdn' => $row['msisdn'],
                            'address' => $row['address'],
                            'town' => $row['town'],
                            'country' => $row['country'],
                            'profile' => $row['profile_image'],
                            'status' => $row['status']
                        ]
                    );

                }

                return $data;
            }else{
                $data =  array(
                    'success' => false,
                    'statusCode' => SUCCESS_RESPONSE,
                    'error' => array('type' => 'DATA_ERROR', 'message' => 'No data found')
                );
                return $data;
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function userDetails($id){
        $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
        $qry = mysqli_query($this->con, $sql);

        if (mysqli_num_rows($qry) == 1){
            $data = "";
            while ($row = mysqli_fetch_assoc($qry)){
                $data = array(
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'email' => $row['email'],
                    'msisdn' => $row['msisdn'],
                    'address' => $row['address'],
                    'town' => $row['town'],
                    'country' => $row['country'],
                    'profile' => $row['profile_image'],
                    'status' => $row['status']
                );

            }

            return $data;
        }else{
            return array();
        }
    }

    public function countMatches($cat, $id){
        $sql = "SELECT * FROM `matches` WHERE `user` = '$id' AND  `category` = '$cat' AND `status` = '0'";
        $qry = mysqli_query($this->con, $sql);
        return mysqli_num_rows($qry);
    }

    public function matchCount(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $services = $this->countMatches(1, $id);
            $accommodation = $this->countMatches(2, $id);
            $jobs = $this->countMatches(3, $id);
            $vehicles = $this->countMatches(4, $id);
            return array(
                'services' => $services, 'accommodation' => $accommodation, 'jobs' => $jobs, 'vehicle' => $vehicles
            );
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function newMatches(){

    }
    public function myMatches(){

        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;


            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'requests'=> $this->requestsMatches($id), 'listings' => $this->listingsMatches($id));
            return $data;
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function myListings($id){
        $lsql = "SELECT * FROM `listings` WHERE `user` = '$id' ORDER BY `added` DESC ";
        $lqry = mysqli_query($this->con, $lsql);
        if (mysqli_num_rows($lqry) > 0){
            $ls = array();
            while ($rs = mysqli_fetch_assoc($lqry)){
                $lds = array(
                    'id' => $rs['id'],
                    'user' => $rs['user'],
                    'category' => $this->Categories($rs['category']),
                    'name' => $rs['name'],
                    'type' => $this->listing_Type($rs['type']),
                    'details' => $this->listingDetails($rs['category'],$rs['id']),
                    'date_created' => $rs['added'],
                    'post' => $rs['post']
                );

                array_push($ls, $lds);
            }

            return  $ls;
        }else{
            return  array();
        }

    }

    public function userListingsAll(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $request = $this->myRequests($id);
            $listings = $this->myListings($id);
            $all = array_merge($request, $listings);
            $vl = array_column($all, 'date_created');
            array_multisort($vl, SORT_DESC, $all);
            if (sizeof($all)){
                return array(
                    'success' => true,
                    'statusCode' => SUCCESS_RESPONSE,
                    'listing' => $all
                );
            }else{
                return array(
                    'success' => true,
                    'statusCode' => NOT_FOUND,
                    'listing' => array(),
                    'message' => 'No listings found'
                );
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function myRequests($id){
        $lsql = "SELECT * FROM `request` WHERE `user` = '$id' ORDER BY `added` DESC ";
        $lqry = mysqli_query($this->con, $lsql);
        if (mysqli_num_rows($lqry) > 0){
            $ls = array();
            while ($rs = mysqli_fetch_assoc($lqry)){
                $lds = array(
                    'id' => $rs['id'],
                    'user' => $rs['user'],
                    'category' => $this->Categories($rs['category']),
                    'name' => $rs['name'],
                    'type' => $this->request_Type($rs['type']),
                    'details' => $this->requestDetails($rs['category'],$rs['id']),
                    'date_created' => $rs['added'],
                    'post' => $rs['post']
                );

                array_push($ls, $lds);
            }

            return $ls;

        }else{
            return array();
        }
    }

    public function listingDetails($cat, $id){
        switch ($cat){
            case "1":
                $sql = "SELECT * FROM `services` WHERE `type` = 'Listing' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'type' => $rs['type'],
                            'parent' => $rs['parent_id'],
                            'name' => $rs['name'],
                            'details' => $rs['description']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }
                break;
            case "2":

                $sql = "SELECT * FROM `accomodation` WHERE `type` = 'Listing' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'name' => $rs['name'],
                            'property' => $rs['higher_level_sub_category'],
                            'thumbnail' => $rs['thumbnail'],
                            'bedrooms' => $rs['bedrooms'],
                            'price' => $rs['price'],
                            'town' => $rs['town'],
                            'country' => $rs['country'],
                            'date_vacant' => $rs['date_vacant'],
                            'details' => $rs['details']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }

                break;
            case "3":

                $sql = "SELECT * FROM `jobs` WHERE `type` = 'Listing' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'category' => $rs['higher_level_subcategory'],
                            'field' => $rs['medium_level_subcategory'],
                            'level' => $rs['level'],
                            'qualification' => $rs['qualification'],
                            'name' => $rs['name'],
                            'deadline' => $rs['deadline'],
                            'details' => $rs['description'],
                            'city' => $rs['city'],
                            'country' => $rs['country']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }

                break;
            case "4":

                $sql = "SELECT * FROM `vehicles`  WHERE `type` = 'Listing' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'name' => $rs['name'],
                            'mode' => $rs['higher_level_sub_category'],
                            'vehicleType' => $rs['middle_level_sub_category'],
                            'subType' => $rs['lower_level_subcategory'],
                            'brand' => $this->brands($id),
                            'thumbnail' => $rs['thumbnail'],
                            'price' => $rs['price'],
                            'description' => $rs['description'],
                            'transmission' => $rs['transmission'],
                            'fuel' => $rs['fuel'],
                            'city' => $rs['city'],
                            'country' => $rs['country'],
                            'date_created' => $rs['date_created'],
                        );
                    }

                    return $ls;
                }else{
                    return array();
                }

                break;
            default:
                return array();
                break;
        }
    }

    public function requestDetails($cat, $id){

        switch ($cat){
            case "1":
                $sql = "SELECT * FROM `services` WHERE `type` = 'Request' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'type' => $rs['type'],
                            'parent' => $rs['parent_id'],
                            'name' => $rs['name'],
                            'details' => $rs['description']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }
                break;
            case "2":

                $sql = "SELECT * FROM `accomodation` WHERE `type` = 'Request' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'property' => $rs['higher_level_sub_category'],
                            'thumbnail' => $rs['thumbnail'],
                            'bedrooms' => $rs['bedrooms'],
                            'price' => $rs['price'],
                            'town' => $rs['town'],
                            'country' => $rs['country'],
                            'date_vacant' => $rs['date_vacant']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }

                break;
            case "3":

                $sql = "SELECT * FROM `jobs` WHERE `type` = 'Request' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'category' => $rs['higher_level_subcategory'],
                            'field' => $rs['medium_level_subcategory'],
                            'level' => $rs['level'],
                            'qualification' => $rs['qualification'],
                            'title' => $rs['name'],
                            'deadline' => $rs['deadline'],
                            'details' => $rs['description'],
                            'city' => $rs['city'],
                            'country' => $rs['country']
                        );
                    }
                    return $ls;
                }else{
                    return array();
                }

                break;
            case "4":

                $sql = "SELECT * FROM `vehicles`  WHERE `type` = 'Request' AND `listing_id` = '$id' ";
                $qry = mysqli_query($this->con, $sql);
                if (mysqli_num_rows($qry) > 0){
                    $ls = "";
                    while ($rs = mysqli_fetch_assoc($qry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'parent' => $rs['listing_id'],
                            'type' => $rs['type'],
                            'mode' => $rs['higher_level_sub_category'],
                            'vehicleType' => $rs['middle_level_sub_category'],
                            'subType' => $rs['lower_level_subcategory'],
                            'brand' => $this->brands($id),
                            'thumbnail' => $rs['thumbnail'],
                            'price' => $rs['price'],
                            'description' => $rs['description'],
                            'transmission' => $rs['transmission'],
                            'fuel' => $rs['fuel'],
                            'city' => $rs['city'],
                            'country' => $rs['country'],
                            'date_created' => $rs['date_created'],
                        );
                    }

                    return $ls;
                }else{
                    return array();
                }

                break;
            default:
                return array();
                break;
        }
    }

    public function Categories($id){
        $lsql = "SELECT * FROM `categories` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $ls = "";
            while ($rs = mysqli_fetch_assoc($lqry)){
                $ls = array(
                    'id' => $rs['id'],
                    'name' => $rs['name'],
                    'description' => $rs['description'],
                    'logo' => $rs['icon']
                );
            }

            return $ls;
        }else{
            return array();
        }
    }

    public function checkUserInfo(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user = $payload->userId;
            $details = $this->userDetails($user);
            /*
                $data = array(
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'email' => $row['email'],
                    'msisdn' => $row['msisdn'],
                    'address' => $row['address'],
                    'town' => $row['town'],
                    'country' => $row['country'],
                    'profile' => $row['profile_image'],
                    'status' => $row['status']
                );
             * */
            // loop through to get empty fields, then return complete or not.
            //add another table in db about notification preferences, if email is there, then send email also, and also if added phone numbers
            //more than one for a fee, then check subscription etc, before send
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function listing_Type($id){
        $lsql = "SELECT * FROM `listing_type` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $ls = "";
            while ($rs = mysqli_fetch_assoc($lqry)){
                $ls = array(
                    'id' => $rs['id'],
                    'name' => $rs['name'],
                    'details' => $rs['description']
                );
            }

            return $ls;
        }else{
            return array();
        }
    }

    public function request_Type($id){
        $lsql = "SELECT * FROM `request_type` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $ls = "";
            while ($rs = mysqli_fetch_assoc($lqry)){
                $ls = array(
                    'id' => $rs['id'],
                    'name' => $rs['name'],
                    'details' => $rs['description']
                );
            }

            return $ls;
        }else{
            return array();
        }
    }

    public function brands($id){
        $lsql = "SELECT * FROM `brand` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $ls = "";
            while ($rs = mysqli_fetch_assoc($lqry)){
                $ls = array(
                    'id' => $rs['id'],
                    'name' => $rs['name'],
                    'category' => $rs['category'],
                    'higher' => $rs['higher'],
                    'medium' => $rs['medium'],
                    'logo' => $rs['icon']
                );
            }

            return $ls;
        }else{
            return array();
        }
    }

    public function matchList($id){
        $lsql = "SELECT * FROM `listings` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $item = "";
            while ($row = mysqli_fetch_assoc($lqry)){
                $cat = $row['category'];
                $userID = $row['user'];
                $details = $this->listingDetails($cat, $id);
                $user = $this->userDetails($userID);
                $item = array('item'=>$details,'user'=> $user);
            }

            return $item;
        }else{
            return array();
        }
    }

    public function matchSeen($id){

        $stmt = $this->pdo->prepare("UPDATE `matches` SET `status` = '1' WHERE `id` = '$id'");

        if ($stmt->execute()) {
            return true;
        }else{
            return false;
        }

    }

    public function matchRequest($id){

        $lsql = "SELECT * FROM `request` WHERE `id` = '$id'";
        $lqry = mysqli_query($this->con, $lsql);

        if (mysqli_num_rows($lqry) > 0){
            $item = "";
            while ($row = mysqli_fetch_assoc($lqry)){
                $cat = $row['category'];
                $userID = $row['user'];
                $details = $this->requestDetails($cat, $id);
                $user = $this->userDetails($userID);
                $item = array('item'=>$details,'user'=> $user);
            }

            return $item;
        }else{
            return array();
        }
    }

    public function matchDetails(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user = $payload->userId;
            $id = $this->getId();
            $sql = "SELECT * FROM `matches` WHERE `id` = '$id' AND `user` = '$user'";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) > 0) {
                $this->matchSeen($id);
                $listings =  array();

                while ($row = mysqli_fetch_assoc($qry)){

                    if ($row['type'] == 'Listing'){
                        $item = $row['listing'];
                        $lsql = "SELECT * FROM `listings` WHERE `id` = '$item'";
                    }else{
                        $item = $row['request'];
                        $lsql = "SELECT * FROM `request` WHERE `id` = '$item'";
                    }

                    $lqry = mysqli_query($this->con, $lsql);

                    while ($rs = mysqli_fetch_assoc($lqry)){
                        $ls = array(
                            'id' => $rs['id'],
                            'category' => $this->Categories($rs['category']),
                            'name' => $rs['name'],
                            'type' => $row['type'],
                            'details' => $this->requestDetails($rs['category'],$rs['id']),
                            'match' => $row['type'] == 'Listing' ? $this->matchList($row['listing']) : $this->matchRequest($row['request']),
                            'date_created' => $row['added'],
                            'status' => $row['status']
                        );
                        array_push($listings, $ls);
                    }
                }



                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'details'=> $listings);


            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }

    }

    public function listingsMatches($id){
        $sql = "SELECT * FROM `matches` WHERE `user` = '$id' AND `type` = 'Listing'";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $listings =  array();

            while ($row = mysqli_fetch_assoc($qry)){

                $item = $row['listing'];
                $lsql = "SELECT * FROM `listings` WHERE `id` = '$item'";
                $lqry = mysqli_query($this->con, $lsql);

                while ($rs = mysqli_fetch_assoc($lqry)){
                    $ls = array(
                        'id' => $rs['id'],
                        'category' => $this->Categories($rs['category']),
                        'name' => $rs['name'],
                        'type' => $row['type'],
                        'details' => $this->listingDetails($rs['category'],$rs['id']),
                        'match' => $this->matchRequest($row['request']),
                        'date_created' => $row['added'],
                        'status' => $row['status']
                    );
                    array_push($listings, $ls);
                }
            }

            return $listings;
        }else{
            return array();
        }
    }

    public function requestsMatches($id){
        $sql = "SELECT * FROM `matches` WHERE `user` = '$id' AND `type` = 'Request'";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $listings =  array();

            while ($row = mysqli_fetch_assoc($qry)){

                $item = $row['request'];
                $lsql = "SELECT * FROM `request` WHERE `id` = '$item'";
                $lqry = mysqli_query($this->con, $lsql);

                while ($rs = mysqli_fetch_assoc($lqry)){
                    $ls = array(
                        'id' => $rs['id'],
                        'category' => $this->Categories($rs['category']),
                        'name' => $rs['name'],
                        'type' => $row['type'],
                        'details' => $this->requestDetails($rs['category'],$rs['id']),
                        'match' => $this->matchList($row['listing']),
                        'date_created' => $row['added'],
                        'status' => $row['status']
                    );
                    array_push($listings, $ls);
                }
            }

            return $listings;
        }else{
            return array();
        }
    }

    public function listing($item){
        $lsql = "SELECT * FROM `listings` WHERE `id` = '$item'";
        $lqry = mysqli_query($this->con, $lsql);

        $ls = "";
        while ($rs = mysqli_fetch_assoc($lqry)){
            $ls = array(
                'id' => $rs['id'],
                'user' => $rs['user'],
                'category' => $rs['category'],
                'name' => $rs['name'],
                'description' => $rs['description'],
                'type' => $rs['type'],
                'date_created' => $rs['added'],
                'post' => $rs['post']
            );
        }

        return $ls;
    }

    public function request($item){
        $lsql = "SELECT * FROM `request` WHERE `id` = '$item'";
        $lqry = mysqli_query($this->con, $lsql);

        $ls = "";
        while ($rs = mysqli_fetch_assoc($lqry)){
            $ls = array(
                'id' => $rs['id'],
                'user' => $rs['user'],
                'category' => $rs['category'],
                'higher_level_sub_category' => $rs['higher_level_sub_category'],
                'middle_level_sub_category' => $rs['middle_level_sub_category'],
                'lower_level_sub_category' => $rs['lower_level_sub_category'],
                'brand' => $rs['brand'],
                'model' => $rs['model'],
                'name' => $rs['name'],
                'description' => $rs['description'],
                'type' => $rs['type'],
                'date_created' => $rs['added'],
                'post' => $rs['post']
            );
        }

        return $ls;
    }

    public function saveVehicle(){
        try {
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $user = $this->getDetails();
            $cat = $this->getCategory();
            $name = $this->getName();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $subcat3 = $this->getModel();
            $fuel = $this->getVehicleFuel();
            $transmission = $this->getVehicleTransmission();
            $description = $this->getDesc();
            $image = $this->getThumbnail();
            $town = $this->getTown();
            $price = $this->getPrice();
            $brand = $this->getBrand();
            $country = $user['user']['user']['country']; //get country from users data
            $type = 1;

            $sql = "INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','0',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $id = mysqli_insert_id($this->con);
                $ssql = "INSERT INTO `vehicles`(`id`, `type`, `listing_id`, `name`, `higher_level_sub_category`, `middle_level_sub_category`, `lower_level_subcategory`, `brand`, `thumbnail`, `description`, `transmission`, `fuel`, `city`, `country`, `price`, `date_created`) VALUES ('','Listing','$id', '$name','$subcat','$subcat2','$subcat3','$brand','$image','$description','$transmission','$fuel','$town','$country','$price',now())";
                $qqry = mysqli_query($this->con, $ssql);
                if ($qqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Vehicle Listing saved');
                }else{
                    $dsql = "DELETE FROM `listings` WHERE `id` = '$id'";
                    $dqry = mysqli_query($this->con, $dsql);
                }

            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Vehicle listing failed to save. Error: '. mysqli_error($this->con))
                );
            }


        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function vehicleSubscribe(){
        //vehicle request
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat = $this->getCategory();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $subcat3 = $this->getSubcategory3();
            $fuel = $this->getVehicleFuel();
            $brand = $this->getBrand();
            $transmission = $this->getVehicleTransmission();
            $town = $this->getTown();
            $price = $this->getPrice();
            $price2 = $price."-".$this->getPrice2();
            $name = $this->getName();
            $country = $this->getCountry();
            $type = 1;

            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','1',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $id = mysqli_insert_id($this->con);
                $ssql = "INSERT INTO `vehicles`(`id`, `type`, `listing_id`, `name`, `higher_level_sub_category`, `middle_level_sub_category`, `lower_level_subcategory`, `brand`, `thumbnail`, `description`, `transmission`, `fuel`, `city`, `country`, `price`, `date_created`) VALUES ('','Request','$id', '$name','$subcat','$subcat2','$subcat3',,'$brand','','','$transmission','$fuel','$town','$country','$price2',now())";
                $qqry = mysqli_query($this->con, $ssql);
                if ($qqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Vehicle Listing saved');

                }else{
                    $dsql = "DELETE FROM `request` WHERE `id` = '$id'";
                    $dqry = mysqli_query($this->con, $dsql);
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Vehicle listing failed to save. Error: '. mysqli_error($this->con))
                );
            }

            return 1;
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function saveService(){
        //service listing
        try{

            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat =  $this->getCategory();
            $service = $this->getId();
            $name =  $this->getName();
            $notes = $this->getDesc();
           $sql = "INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','1', '1',now())";
           $qry = mysqli_query($this->con, $sql);

           if ($qry){
               $id = mysqli_insert_id($this->con);

               $nsql = "INSERT INTO `services`(`id`,`type`, `parent_id`, `listing_id`, `name`, `description`) VALUES ('','Listing','$service','$id','$name','$notes')";
                $nqry = mysqli_query($this->con, $nsql);
               if ($nqry){
                   return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Service Listing saved');
               }else{
                   $dsql = "DELETE FROM `listings` WHERE `id` = '$id'";
                   $dqry = mysqli_query($this->con, $dsql);
                   return array(
                       'success' => false,
                       'statusCode' => INTERNAL_SERVER_ERROR,
                       'error' => array(
                           'type' => "SERVER_ERROR",
                           'message' => 'Services listing failed to save in Services table. Error: '. mysqli_error($this->con))
                   );
               }


           }else{
               return array(
                   'success' => false,
                   'statusCode' => INTERNAL_SERVER_ERROR,
                   'error' => array(
                       'type' => "SERVER_ERROR",
                       'message' => 'Services listing failed to save. Error: '. mysqli_error($this->con))
               );
           }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function serviceSubscribe(){
        //services request
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat =  $this->getCategory();
            $name  = $this->getName();
            $service = $this->getId();
            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','1', '1',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $id = mysqli_insert_id($this->con);
                $ssql = "INSERT INTO `services`(`id`,`type`, `parent_id`, `listing_id`, `name`, `description`) VALUES ('','Request','$service','$id','$name','')";
                $qqry = mysqli_query($this->con, $ssql);
                if ($qqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Service Request saved');

                }else{
                    $dsql = "DELETE FROM `services` WHERE `id` = '$id'";
                    $qqry = mysqli_query($this->con, $ssql);
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Services Request failed to save.')
                    );
                }

            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Services Request failed to save. Error: '. mysqli_error($this->con))
                );
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public  function saveJobs(){
        //jobs listing
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat = $this->getCategory();
            $level = $this->getJobLevel();
            $qualification = $this->getJobQualification();
            $name = $this->getName();
            $type = 1;
            $notes = $this->getDesc();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $deadline = $this->getDeadline();
            $town = $this->getTown();
            $country = $this->getCountry();

            $sql = "INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','1',now());
                    INSERT INTO `jobs`(`id`, `listing_id`, `request_id`, `higher_level_subcategory`, `medium_level_subcategory`, `qualification`, `name`, `deadline`, `description`, `level`, `city`, `country`) VALUES ('',LAST_INSERT_ID(),NULL ,'$subcat','$subcat2','$qualification','$name','$deadline','$notes','$level','$town','$country')";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Job Listing saved');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Jobs listing failed to save. Error: '. mysqli_error($this->con))
                );
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function jobSubscription(){
        //jobs subscription
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat = $this->getCategory();
            $level = $this->getJobLevel();
            $qualification = $this->getJobQualification();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $town = $this->getTown();
            $name = $this->getName();
            $country = $this->getCountry();
            $type = 1;
            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','1',now())";

            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $id = mysqli_insert_id($this->con);
                $slq = "INSERT INTO `jobs`(`id`, `type`, `listing_id`, `name`,`higher_level_subcategory`, `medium_level_subcategory`, `qualification`, `name`, `deadline`, `description`, `level`, `city`, `country`) VALUES ('','Request','$id','$name','$subcat','$subcat2','$qualification','','','','$level','$town','$country')";
                $qqry = mysqli_query($this->con, $slq);
                if ($qqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Job Request saved');
                }else{
                    $qsl = "DELETE FROM `jobs` WHERE `id` = '$id'";
                    $dqr = mysqli_query($this->con, $qsl);
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Jobs Request failed to save. Error: '. mysqli_error($this->con))
                );
            }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function saveAccommodation(){
        //accomodation listing
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $name = $this->getName();
            $subcat = $this->getSubcategory1();
            $bedrooms = $this->getBedrooms();
            $dateVacant = $this->getDateStart();
            $price = $this->getPrice();
            $town = $this->getTown();
            $country = $this->getCountry();
            $cat = $this->getCategory();
            $image = $this->getThumbnail();
            $notes = $this->getDesc();
            $type = 2;
            $sql ="INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','1',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $id = mysqli_insert_id($this->con);
                $ssql = "INSERT INTO `accomodation`(`id`, `type`, `listing_id`, `name`, `higher_level_sub_category`, `thumbnail`, `bedrooms`, `price`, `town`, `country`, `date_vacant`, `details`) VALUES ('', 'Listing','$id','$name','$subcat','$image','$bedrooms','$price','$town','$country','$dateVacant', '$notes')";
                $ssqry = mysqli_query($this->con, $ssql);
                if ($ssqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Accommodation Listing saved');

                }else{
                    $dsql = "DELETE FROM accomodation WHERE `id` = '$id'";
                    $dqry = mysqli_query($this->con, $dsql);
                }

            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Accommodation listing failed to save. Error: '. mysqli_error($this->con))
                );
            }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }

    }

    public function accommodationSubscribe(){
        //accomodation request
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat = $this->getCountry();
            $subcat = $this->getSubcategory1();
            $bedrooms = $this->getBedrooms();
            $price = $this->getPrice();
            $thumbnail = $this->getThumbnail();
            $town = $this->getTown();
            $country = $this->getCountry();
            $price2 = $price." - ". $this->getPrice2();
            $type = 2;
            $sql ="INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','','$type','1',now())";

            $qry = mysqli_query($this->con, $sql);

            if ($qry){
                $id = mysqli_insert_id($this->con);
                $ssql = "INSERT INTO `accomodation`(`id`, `type`, `listing_id`, `name`, `higher_level_sub_category`, `thumbnail`, `bedrooms`, `price`, `town`, `country`) VALUES ('','Request','$id','','$subcat','$thumbnail','$bedrooms','$price2','$town','$country')";
                $qqry = mysqli_query($this->con, $ssql);
                if ($qqry){
                    return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Accommodation Request saved');
                }else{
                    $dsql = "DELETE FROM `accommodation` WHERE `id` = '$id'";
                    $qry = mysqli_query($this->con, $dsql);
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Accommodation Request failed to save. Error: '. mysqli_error($this->con))
                );
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function removeListing(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $id = $this->getId();

            $query = "SELECT * FROM `listings` WHERE  `id` = :id; AND `user` = :user;";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':id' =>$id, ':user' => $user_id));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            switch ($row['category']){
                case "1":

                    $sql = "DELETE FROM `listings` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `services` WHERE `listing_id` = '$id' AND `type` = 'Listing'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Listing removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing listing failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;
                case '2':

                    $sql = "DELETE FROM `listings` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `accomodation` WHERE `listing_id` = '$id' AND `type` = 'Listing'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Listing removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing listing failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;

                case '3':

                    $sql = "DELETE FROM `listings` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `jobs` WHERE `listing_id` = '$id' AND `type` = 'Listing'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Listing removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing listing failed: ".mysqli_error_list($this->con))
                        );
                    }


                    break;
                case '4':
                    $sql = "DELETE FROM `listings` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `vehicles` WHERE `listing_id` = '$id' AND `type` = 'Listing'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Listing removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing listing failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;
                default:


                    break;
            }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }

    }

    public function removeRequest(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $id = $this->getId();

            $query = "SELECT * FROM `request` WHERE  `id` = :id; AND `user` = :user;";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':id' =>$id, ':user' => $user_id));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            switch ($row['category']){
                case "1":

                    $sql = "DELETE FROM `request` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `services` WHERE `listing_id` = '$id' AND `type` = 'Request'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Request removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing request failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;
                case '2':

                    $sql = "DELETE FROM `request` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `accomodation` WHERE `listing_id` = '$id' AND `type` = 'Request'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Request removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing request failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;

                case '3':

                    $sql = "DELETE FROM `request` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `jobs` WHERE `listing_id` = '$id' AND `type` = 'Request'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Request removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing request failed: ".mysqli_error_list($this->con))
                        );
                    }


                    break;
                case '4':
                    $sql = "DELETE FROM `request` WHERE `id` ='$id'";
                    $qry = mysqli_query($this->con, $sql);

                    $ds = "DELETE FROM `vehicles` WHERE `listing_id` = '$id' AND `type` = 'Request'";
                    $dqr = mysqli_query($this->con, $ds);
                    if ($qry && $dqr){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Request removed');
                        return $data;
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing request failed: ".mysqli_error_list($this->con))
                        );
                    }

                    break;
                default:


                    break;
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }

    }

}