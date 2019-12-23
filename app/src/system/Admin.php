<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/25/2019
 * Time: 2:27 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;
class Admin extends System
{

    protected $secret;
    protected $status;
    protected $file;
    protected $level;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function __construct()
    {
        return parent::__construct();
    }



    public function login()
    {
        try{
            $email = $this->getEmail();
            $sql = "SELECT `id`,`password` FROM `admin` WHERE `email`='$email'";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) == 1){
                $row = mysqli_fetch_assoc($qry);
                $hash = $row['password'];
                if(password_verify($this->getPassword(), $hash)){
                    $paylod = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (60*60*8),
                        'userId' => $row['id']
                    ];

                    $token = JWT::encode($paylod, SECRETE_KEY);
                    $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Login successful','token'=>$token);
                    return $data;

                }else{
                    $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Login Credentials'));
                    return $data;
                }
            }else{
                $data = array('success' => false, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Account not found'));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }

    }

    public function apiLogin(){
        try{
            $query = "SELECT * FROM  `apiusers` WHERE email= :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $this->getEmail()));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $hash = $row['password'];
            $id = $row['id'];

            if (password_verify($this->getPassword(), $hash)) {
                $token = JWT::encode(['id' => $id, 'email' => $this->getEmail()], $this->getSecret(), "HS256");
                return array('statusCode' => SUCCESS_RESPONSE, 'token' => $token);
            }else{
                return array('statusCode' => UNAUTHORISED, 'error' => ['type' => 'AUTHORIZATION_ERROR', 'message' => 'Invalid login Credentials']);
            }

        }catch (\Exception $e){
            return array('statusCode' => INTERNAL_SERVER_ERROR, 'error' => ['type' => 'HANDLED_EXCEPTION', 'message' => $e->getMessage()]);
        }
    }

    public function create(){

        $cat = $this->getCategory();
        $email = $this->getEmail();
        $name = $this->getName();
        $surname = $this->getLastName();
        $gender = $this->getGender();
        $dob = $this->getDob();
        $marital_status = $this->getMaritalStatus();
        $address = $this->getAddress();
        $mobile = $this->getMobile();
        $dept = $this->getDept();
        $pwd = password_hash($this->getPassword(), PASSWORD_BCRYPT, array("cost" => 10));
        $permissions = $this->getPermission();
        $sql = "INSERT INTO `admin`(`id`, `email`, `password`, `permisions`, `name`, `surname`, `gender`, `dob`, `marital_status`, `address`, `mobile`, `dept`, `profile`, `category`) VALUES ('','$email','$pwd','$permissions','$name','$surname', '$gender', '$dob', '$marital_status', '$address', '$mobile', '$dept','','$cat')";
        $qry = mysqli_query($this->con, $sql);

        if ($qry){

            if($this->accountCreateMail()){
                $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully & email sent');
                return $data;
            }else{
                $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully');
                return $data;
            }

        }else{
            $data = array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Account creation not complete. Error: '. mysqli_error($this->con))
            );

            return $data;
        }

    }

    public function adminAll(){
        try{
            $sql = "SELECT * FROM `admin` WHERE 1";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) > 0){
                $results = array();
                while($row = mysqli_fetch_assoc($qry)){
                    $arr = array(
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'email' => $row['email'],
                        'profile' => $row['profile'],
                        'category' => $row['category'],
                        'permission' => $row['permisions']
                    );
                    array_push($results ,$arr);
                }
                $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Fetched Users','users'=>$results, 'results' => mysqli_num_rows($qry));
                return $data;
            }else{
                $data = array('success' => false, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "FETCH_DATA_ERROR", 'message' => 'Users not found'));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function membersAll(){
        try{

            $sql = "SELECT * FROM `members`";
            $qry = mysqli_query($this->con, $sql);

            if (mysqli_num_rows($qry) > 0){

                $details = array();
                while ($row = mysqli_fetch_assoc($qry)){
                    $id = $row['id'];

                    $psql = "SELECT `package` FROM `member_details` WHERE `member_id` = '$id'";
                    $pqry = mysqli_query($this->con, $psql);
                    $prs = mysqli_fetch_assoc($pqry);

                    $ssql = "SELECT  * FROM `dependant` WHERE `member_id` = '$id'";
                    $qqry = mysqli_query($this->con, $ssql);

                    $dependant = array();
                    while($rows = mysqli_fetch_assoc($qqry)){
                        $dep = array(
                            'registered' => $rows['created'],
                            'name' => $rows['name'],
                            'surname' => $rows['surname'],
                            'membership-number' => $rows['membership_no'],
                            'national-ID' => $rows['national_ID'],
                            'D.O.B' => $rows['dob'],
                            'gender' => $rows['gender']
                        );

                        array_push($dependant, $dep);
                    }

                    $member = array(
                        'id' => $id,
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'national-ID' => $row['id_number'],
                        'membership-number' => $row['membership_no'],
                        'D.O.B' => $row['dob'],
                        'gender' => $row['gender'],
                        'address' => $row['address'],
                        'profile' => $this->getProfile($id),
                        'town' => $row['town'],
                        'package' => $this->packageName($prs['package']),
                        'registered' => $this->registrationDate($id),
                        'subscription' => $this->subscription($id),
                        'dependants' => $dependant
                    );

                    array_push($details, $member);

                }
                $data = array(
                    'success' => true,
                    'statusCode' => SUCCESS_RESPONSE,
                    'member' => $details
                );
                return $data;
            }else{
                $data =  array(
                    'success' => false,
                    'statusCode' => SUCCESS_RESPONSE,
                    'error' => array('type' => 'DATA_ERROR', 'message' => 'No data found')
                );
                return $data;
            }
        }catch (\Exception $exception){

            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $exception->getMessage())
            );

        }
    }

    public function subscription($member){

        $sql = "SELECT * FROM `subscriptions` WHERE `member_id` = '$member' AND now() BETWEEN `start` AND `end`";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) == 0){
            $ssql ="SELECT * FROM `subscriptions` WHERE `member_id` = '$member' ORDER BY `end` DESC ";
            $sqry = mysqli_query($this->con, $ssql);
            $rs = mysqli_fetch_assoc($sqry);
            return array(
                'status' => false,
                'date' => $rs['end']
            );
        }else{
            $rs = mysqli_fetch_assoc($qry);
            $bill = $rs['bill_id'];
            $bsql = "SELECT `paid_on` FROM `payment` WHERE `id` = '$bill' ";
            $bqry = mysqli_query($this->con, $bsql);
            $drs = mysqli_fetch_assoc($bqry);

            return array(
                'status' => true,
                'date' => $drs['paid_on']
            );
        }
    }

    public function registrationDate($id){
        $sql = "SELECT `created` FROM `members` WHERE  `id` = '$id'";
        $qry = mysqli_query($this->con, $sql);
        $rs = mysqli_fetch_assoc($qry);
        return $rs['created'];
    }

    public function packageName($id){
        $sql = "SELECT * FROM `packages` WHERE `id` = '$id'";
        $qry = mysqli_query($this->con, $sql);
        $rw = mysqli_fetch_assoc($qry);
        if (mysqli_num_rows($qry) == 1) {
            return $rw['name'];
        }else{
            return null;
        }
    }

    public function getProfile($id){

    }

    public function memberSuspend($id){
        $sql = "UPDATE `member_login` SET `status`='2', `updated` = now() WHERE `member_id` = '$id'";
        $qry = mysqli_query($this->con, $sql);

        if ($qry){
            $data = array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'member successfully suspended'
            );
            return $data;
        }else{
            $data =  array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'INTERNAL_SERVER_ERROR', 'message' => 'Suspending account failed: ' .mysqli_error($this->con) )
            );
            return $data;
        }
    }

    public function blacklist($id){
        $sql = "UPDATE `member_login` SET `status`='2', `updated` = now() WHERE `member_id` = '$id'";
        $qry = mysqli_query($this->con, $sql);

        if ($qry){
            $data = array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'member successfully suspended'
            );
            return $data;
        }else{
            $data =  array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'INTERNAL_SERVER_ERROR', 'message' => 'Suspending account failed: ' .mysqli_error($this->con) )
            );
            return $data;
        }
    }

    public function removeMember($id){
        $dsql = "DELETE FROM `members` WHERE `id` = '$id'";
        $qry = mysqli_query($this->con, $dsql);

        $dtsql = "DELETE FROM `member_details` WHERE `member_id` = '$id'";
        $dtqry = mysqli_query($this->con, $dtsql);

        $dlsql = "DELETE FROM `member_login` WHERE `member_id` = '$id'";
        $dlqry = mysqli_query($this->con, $dlsql);

        $dpsql = "DELETE FROM `dependant` WHERE `member_id` = '$id'";
        $dpqry = mysqli_query($this->con,$dpsql);

        $psql = "DELETE FROM `payment` WHERE `member_id` = '$id'";
        $pqry = mysqli_query($this->con, $psql);

        $ssql = "DELETE FROM `subscriptions` WHERE `member_id` = '$id'";
        $ssqry = mysqli_query($this->con, $ssql);


        if ($qry && $dtqry && $dlqry && $dpqry && $pqry && $ssqry){
            $data = array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'Member successfully removed'
            );
            return $data;
        }else{
            $data =  array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'INTERNAL_SERVER_ERROR', 'message' => 'Error while removing account: ' .mysqli_error($this->con) )
            );
            return $data;
        }

    }

    public function adminUpdate(){

    }

    public function updatePassword(){

        try{
            $pwd = $this->getPassword();
            $payload = JWT::decode($this->getTkn(), SECRETE_KEY, ['HS256']);
        }catch (\Exception $e){

        }
    }

    public function userAccountEmail(){

        $this->mail->addAddress($this->getEmail());
        $this->mail->setFrom("no-reply@velocityhealth.co.za");
        $this->mail->Subject = "Account creation confirmation";
        $this->mail->isHTML(true);

        $body = "";

        $this->mail->Body = $body;

        if ($this->mail->send()) {
            return true;
        }else{
            return false;
        }
    }

    public function accountCreateMail(){
        $this->mail->addAddress($this->getEmail());
        $this->mail->setFrom("admin@velocityhealth.co.zw");
        $this->mail->Subject = "Dashboard Account Creation update";
        $this->mail->isHTML(true);
        $body = '
                    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
                    <title>Mail Upate - Ultra-Med</title>
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
                                                  <img src="http://www.ultramedhealth.com/wp-content/uploads/2016/12/logo5.jpg" alt="img" width="128" border="0" style="display: block; width: 128px;" />
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
                                                  <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">Welcome to the Ultra-Med Health team. Your account has been created and your login credentials are as follows</span>
                                               </font>
                                               <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                               <font face="\'Source Sans Pro\', sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                                  <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">
                                                  Open the  URL <a target="_blank" href="http://www.dashboard.ultramedhealth.com"></a> on the browser and login with the following credentials <br>
                                                  <strong>username: <i style="color: blue;">'.$this->getEmail().'</i></strong><br>
                                                  <strong>password: <i style="color: blue;">'.$this->getPassword().'</i></strong>
                                                  <br>
                                                  <hr>
                                                  <br>
                                                  <span style="color: red; ">Please take note to keep these details private, to ensure security. Thank you!</span>
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
                                                              <img src="http://www.dashboard.ultramedhealth.com/img/icons/android-chrome-512x512.png" alt="img" width="50" border="0" style="display: block; width: 50px;" />
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
                                                              <img src="http://www.ultramedhealth.com/wp-content/uploads/2016/12/logo5.jpg" alt="img" width="177" border="0" style="display: block; width: 177px; max-width: 100%;" />
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
                                                           <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #868686; font-size: 17px; line-height: 20px;">Copyright &copy; 2017 Ultra-Med Health. All&nbsp;Rights&nbsp;Reserved.</span>
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

    public function apiUserCreate(){
        $name = $this->getName();
        $surname = $this->getLastName();
        $email = $this->getEmail();
        $pwd = password_hash($this->getPassword(), PASSWORD_BCRYPT, array("cost" => 10));
        $sql = "INSERT INTO `apiusers`(`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`, `status`) VALUES ('','$name','$surname','$email','$pwd',now(),'', '0')";
        $qry = mysqli_query($this->con, $sql);

        if ($qry){
            //send email about creation to $email
            $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully. Please visit email and verify account');
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

    public function addCategory(){
        $name = $this->getName();
        $desc= $this->getDesc();

        $sql = "INSERT INTO `categories`(`id`, `name`, `description`, `icon`) VALUES ('','$name','$desc','')";
        $qry = mysqli_query($this->con, $sql);
        if ($qry){
            return true;
        }else{
            return false;
        }
    }

    public function addSubCat1(){
        $id = $this->getId();
        $name = $this->getName();
        $desc= $this->getDesc();

        $sql = "INSERT INTO `higher_level_sub_category`(`id`, `parent_id`, `name`, `description`) VALUES ('','$id','$name','$desc')";
        $qry = mysqli_query($this->con, $sql);
        if ($qry)
            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'Sub category saved'
            );
        else
            return array(
                'success' => false,
                'statusCode' => NOT_FOUND,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Sub category failed to save')
            );
    }

    public function addSubCat2(){
        $id = $this->getId();
        $name = $this->getName();
        $desc= $this->getDesc();
        $sql = "INSERT INTO `middle_level_sub_category`(`id`, `parent_id`, `name`, `description`) VALUES ('','$id','$name','$desc')";
        $qry = mysqli_query($this->con,$sql);
        if ($qry)
            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'Sub category saved'
            );
        else
            return array(
                'success' => false,
                'statusCode' => NOT_FOUND,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Sub category failed to save')
            );
    }

    public function addSubCat3(){
        $id = $this->getId();
        $name = $this->getName();
        $desc= $this->getDesc();
        $sql = "INSERT INTO `lower_level_sub_category`(`id`, `parent_id`, `name`, `description`) VALUES ('','$id','$name','$desc')";
        $qry = mysqli_query($this->con,$sql);
        if ($qry)
            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'message' => 'Sub category saved'
            );
        else
            return array(
                'success' => false,
                'statusCode' => NOT_FOUND,
                'error' => array(
                    'type' => "SERVER_ERROR",
                    'message' => 'Sub category failed to save')
            );
    }

    public function addByUpload(){
        $file = $this->getFile();
        $cat = $this->getCategory();
        $lvl = $this->getLevel();

        switch ($lvl){
            case "higher":
                $sql = "INSERT INTO `higher_level_sub_category`(`parent_id`, `name`, `description`) VALUES ";
                foreach ($file as $key){
                    $id = $cat;
                    $temp = json_decode($key, true);
                    $name = $temp['name'];
                    $desc= $temp['description'];
                    $sql .= "('$id','$name','$desc'),";
                }
                $sql = substr($sql, 0, -1);

                $qry = mysqli_query($this->con, $sql);
                if ($qry)
                    return array(
                        'success' => true,
                        'statusCode' => SUCCESS_RESPONSE,
                        'message' => 'Sub category saved'
                    );
                else
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Sub category failed to save. Error: '. mysqli_error($this->con))
                    );

                break;
            case "medium":
                $sql = "INSERT INTO `middle_level_sub_category`(parent_id`, `name`, `description`) VALUES ";
                foreach ($file as $key){
                    $id = $cat;
                    $temp = json_decode($key, true);
                    $name = $temp['name'];
                    $desc= $temp['description'];
                    $sql .= "('$id','$name','$desc');";
                }
                $sql = substr($sql, 0, -1);
                $qry = mysqli_query($this->con, $sql);
                if ($qry)
                    return array(
                        'success' => true,
                        'statusCode' => SUCCESS_RESPONSE,
                        'message' => 'Sub category saved'
                    );
                else
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Sub category failed to save Error: '. mysqli_error($this->con))
                    );
                break;
            case "lower":
                $sql = "INSERT INTO `lower_level_sub_category`(`parent_id`, `name`, `description`) VALUES ";
                foreach ($file as $key){
                    $id = $cat;
                    $temp = json_decode($key, true);
                    $name = $temp['name'];
                    $desc= $temp['description'];
                    $sql .= "('$id','$name','$desc');";
                }
                $sql = substr($sql, 0, -1);
                $qry = mysqli_query($this->con, $sql);
                if ($qry)
                    return array(
                        'success' => true,
                        'statusCode' => SUCCESS_RESPONSE,
                        'message' => 'Sub category saved'
                    );
                else
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'Sub category failed to save Error: '. mysqli_error($this->con))
                    );
                break;
            default:
                return array(
                    'success' => false,
                    'statusCode' => FORBIDEN,
                    'error' => array(
                        'type' => "NOT_ALLOWED",
                        'message' => 'Not Applicable')
                );
                break;
        }
    }
}