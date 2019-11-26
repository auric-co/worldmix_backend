<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 11/10/2019
 * Time: 11:19 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';
use Firebase\JWT\JWT;
class User extends System
{
    function repost(){

    }

    function depost(){

    }

    function checkLogin(){

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

                            $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account has not been updated. Please visit your email to activate your account'));
                            return $data;
                        }elseif ($row['status'] == 1) {
                            $paylod = [
                                'iat' => time(),
                                'iss' => $this->domain(),
                                'exp' => time() + (60*60*8),
                                'userId' => $row['id']
                            ]; //expires in 8 hours

                            $token = JWT::encode($paylod, SECRETE_KEY);
                            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Login successful', 'token' => $token);
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
                            'message' => 'User email already exists')
                    );

                    return $data;
                }else {
                    $code = rand(1000, 9999);

                    $sql = "INSERT INTO `users`(`id`, `email`, `password`, `name`, `surname`, `msisdn`, `town`, `address`, `profile_image`,`token`, `status`) VALUES ('','','$pwd','$name','$sname','$u','','','','$code','0')";
                    $insert = mysqli_query($this->con, $sql);
                    if ($insert){
                        if($this->sendRegText($code)){
                            $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully. Please activate your account');
                            return $data;
                        }else{
                            $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully. Please contact admin to activate your account');
                            return $data;
                        }

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
        $u = $this->getEmail();

        $sql = "UPDATE `users` SET `status`= '1' WHERE `token` ='$code' AND `email` = '$u'";
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
        $email = $this->getEmail();
        $code = rand(1000, 9999);
        $check = "SELECT COUNT(*) FROM `users` WHERE `email` = '$email' AND `status` = '0'";

        if ($res = $this->pdo->query($check)) {

            if ($res->fetchColumn() == 1) {
                $sql = "UPDATE `users` SET `code` = '$code' WHERE `email` = '$email' AND `status` = '0'";
                $qry = mysqli_query($this->con, $sql);
                if ($qry){

                    if($this->sendRegEmail($code)){
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Code has been emailed to you');
                        return $data;
                    }else{
                        return  array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'code'=> $code, 'message' => 'Code has been updated, however email could not be sent. Please contact admin');
                    }

                }else{
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Code could not be retrieved. Internal Server error')
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

    function sendRegText($code){
        /*try{
            $client = "";
            $client->number = "";
            $client->username = "";
            $client->key = "";
            $sid = "AC2a1c0cff95aaab261adaad6596693de3"; // Your Account SID from www.twilio.com/console
            $token = "8f2c8e73562c87dabb8be159e8a58ccb"; // Your Auth Token from www.twilio.com/console
            $client = new Twilio\Rest\Client($sid, $token);

            $message = $client->messages->create(
                '+' . $this->getMsisdn(), // Text this number
                array(
                    'from' => '+18504629824', // From a valid Twilio number
                    'body' => 'Hey there. welcome to worldmix. you activation code is ' . $code
                )
            );
            $cl = new RestClient("MANZVKOGE4ZWRLMJG3YT", "ZmFmYWRmYTVlOTJhNGViY2I1MjM1Mjk1ZWE5NTU0");

            $message_created = $cl->messages->create('WORLDMIX', ['+' . $this->getMsisdn()], 'Hey there. welcome to worldmix. you activation code is ' . $code);

        }catch (\Twilio\Exceptions\ConfigurationException $e){

        } */
    }

    public function myMatches(){
        //return both requests and listings matches
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;

            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'requests'=> $this->listingsMatches($id), 'listings' => $this->listingsMatches($id));
            return $data;
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function listingsMatches($id){
        $sql = "SELECT * FROM `matches` WHERE `user` = '$id' AND `item_type` = '1'";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $listings =  array();

            while ($row = mysqli_fetch_assoc($qry)){
                $item = $row['match_item'];
                $lsql = "SELECT * FROM `listings` WHERE `id` = '$item'";
                $lqry = mysqli_query($this->con, $lsql);

                while ($rs = mysqli_fetch_assoc($lqry)){
                    $ls = array(
                        'id' => $rs['id'],
                        'user' => $row['user'],
                        'category' => $rs['category'],
                        'higher_level_sub_category' => $rs['higher_level_sub_category'],
                        'middle_level_sub_category' => $rs['middle_level_sub_category'],
                        'lower_level_sub_category' => $rs['lower_level_sub_category'],
                        'brand' => $rs['brand'],
                        'model' => $rs['model'],
                        'name' => $rs['name'],
                        'description' => $rs['description'],
                        'type' => $rs['type'],
                        'date_matched' => $row['added'],
                        'post' => $rs['post'],
                        'request' => $this->listing($row['item'])
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
        $sql = "SELECT * FROM `matches` WHERE `user` = '$id' AND `item_type` = '2'";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $listings =  array();

            while ($row = mysqli_fetch_assoc($qry)){
                $item = $row['match_item'];
                $lsql = "SELECT * FROM `request` WHERE `id` = '$item'";
                $lqry = mysqli_query($this->con, $lsql);

                while ($rs = mysqli_fetch_assoc($lqry)){
                    $ls = array(
                        'id' => $rs['id'],
                        'user' => $row['user'],
                        'category' => $rs['category'],
                        'higher_level_sub_category' => $rs['higher_level_sub_category'],
                        'middle_level_sub_category' => $rs['middle_level_sub_category'],
                        'lower_level_sub_category' => $rs['lower_level_sub_category'],
                        'brand' => $rs['brand'],
                        'model' => $rs['model'],
                        'name' => $rs['name'],
                        'description' => $rs['description'],
                        'type' => $rs['type'],
                        'date_matched' => $row['added'],
                        'post' => $rs['post'],
                        'listing' => $this->request($row['item'])
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
            $cat = $this->getCategory();
            $name = $this->getName();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $subcat3 = $this->getSubcategory3();
            $fuel = $this->getVehicleFuel();
            $transmission = $this->getVehicleTransmission();
            $description = $this->getDesc();
            $location = $this->getLocation();
            $town = $this->getTown();
            $price = $this->getPrice();
            $country = $this->getCountry();
            $type = 1;

            $sql = "INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','0',now());
                    INSERT INTO `vehicles`(`id`, `listing_id`, `request_id`, `higher_level_sub_category`, `middle_level_sub_category`, `lower_level_subcategory`, `thumbnail`, `description`, `transmission`, `fuel`, `location`, `city`, `country`, `price`, `date_created`) VALUES ('',LAST_INSERT_ID(),NULL,'$subcat','$subcat2','$subcat3','','$description','$transmission','$fuel','$location','$town','$country','$price',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Vehicle Listing saved');
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

    public function vehichleSubscribe(){
        //vehicle request
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $cat = $this->getCategory();
            $subcat = $this->getSubcategory1();
            $subcat2 = $this->getSubcategory2();
            $subcat3 = $this->getSubcategory3();
            $fuel = $this->getVehicleFuel();
            $transmission = $this->getVehicleTransmission();
            $town = $this->getTown();
            $price = $this->getPrice();
            $price2 = $price."-".$this->getPrice2();
            $country = $this->getCountry();
            $type = 1;

            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','','$type','1',now());
                    INSERT INTO `vehicles`(`id`, `listing_id`, `request_id`, `higher_level_sub_category`, `middle_level_sub_category`, `lower_level_subcategory`, `thumbnail`, `description`, `transmission`, `fuel`, `location`, `city`, `country`, `price`, `date_created`) VALUES ('',NULL,LAST_INSERT_ID(),'$subcat','$subcat2','$subcat3','','','$transmission','$fuel','','$town','$country','$price2',now())";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Vehicle Listing saved');
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
           $sql = "INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','1', '0',now());
                   INSERT INTO `services`(`id`, `parent_id`, `listing_id`, `request_id`,  `name`, `description`) VALUES ('','$service',LAST_INSERT_ID(), NULL,'$name','')";
           $qry = mysqli_query($this->con, $sql);
           if ($qry){
               return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Service Listing saved');

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
            $service = $this->getId();
            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','','1', '1',now());
                   INSERT INTO `services`(`id`, `parent_id`, `listing_id`, `request_id`,  `name`, `description`) VALUES ('','$service',NULL,LAST_INSERT_ID(),'','')";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Service Request saved');

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
            $country = $this->getCountry();
            $type = 1;
            $sql = "INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','','$type','1',now());
                    INSERT INTO `jobs`(`id`, `listing_id`, `request_id`, `higher_level_subcategory`, `medium_level_subcategory`, `qualification`, `name`, `deadline`, `description`, `level`, `city`, `country`) VALUES ('',NULL,LAST_INSERT_ID(),'$subcat','$subcat2','$qualification','','','','$level','$town','$country')";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Job Request saved');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array(
                        'type' => "SERVER_ERROR",
                        'message' => 'Jobs Request failed to save. Error: '. mysqli_error($this->con))
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
            $address = $this->getAddress();
            $type = 2;
            $location = $this->getLocation();
            //change post to 1 if thumbnail upload can be done at the same time
            $sql ="INSERT INTO `listings`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','$name','$type','0',now());
                   INSERT INTO `accomodation`(`id`, `listing_id`, `request_id`, `higher_level_sub_category`, `thumbnail`, `bedrooms`, `price`, `location`, `town`, `address`, `country`, `date_vacant`) VALUES ('',LAST_INSERT_ID,NULL,'$subcat','','$bedrooms','$price','$location','$town','$address','$country','$dateVacant')";

            $qry = mysqli_query($this->con, $sql);

            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Accomodation Listing saved');
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
            $dateVacant = $this->getDateStart();
            $price = $this->getPrice();
            $town = $this->getTown();
            $country = $this->getCountry();
            $price2 = $price." - ". $this->getPrice2();
            $type = 2;
            $sql ="INSERT INTO `request`(`id`, `user`, `category`, `name`, `type`, `post`, `added`) VALUES ('','$user_id','$cat','','$type','1',now());
                   INSERT INTO `accomodation`(`id`, `listing_id`, `request_id`, `higher_level_sub_category`, `thumbnail`, `bedrooms`, `price`, `location`, `town`, `address`, `country`, `date_vacant`) VALUES ('',NULL,LAST_INSERT_ID,'$subcat','','$bedrooms','$price2','','$town','','$country','$dateVacant')";

            $qry = mysqli_query($this->con, $sql);

            if ($qry){
                return array('success' => true, 'statusCode' => CREATED, 'message'=> 'Accomodation Request saved');
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
            $sql = "DELETE FROM `listings` WHERE `id` ='$id'";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Listing removed');
                return $data;
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing listing failed: ".mysqli_error_list($this->con))
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

    public function removeRequest(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user_id = $payload->userId;
            $id = $this->getId();
            $sql = "DELETE FROM `request` WHERE `id` = '$id'";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Request removed');
                return $data;
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Removing request failed: ".mysqli_error_list($this->con))
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


}