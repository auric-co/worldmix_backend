<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/25/2019
 * Time: 2:16 PM
 */
include_once dirname(__FILE__) . '/Database.php';
include_once dirname(__FILE__) . '/MembershipNumber.php';
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;

class System
{
    protected $pdo;
    protected $con;
    protected $email;
    protected $password;
    protected $newPassword;
    protected $name;
    protected $msisdn;
    protected $permission;
    protected $lastName;
    protected $address;
    protected $town;
    protected $token;
    protected $category;
    protected $desc;
    protected $price;
    protected $nationID;
    protected $dob;
    protected $gender;
    protected $id;
    protected $amount;
    protected $maritalStatus;
    protected $package;
    protected $method;
    protected $starting;
    protected $ending;
    protected $confirmation;
    protected $start;
    protected $end;
    protected $bill;
    protected $dept;
    protected $mail;
    /**
     * @return mixed
     */
    public function getDept()
    {
        return $this->dept;
    }


    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @param mixed $dept
     */
    public function setDept($dept)
    {
        $this->dept = $dept;
    }

    /**
     * @param mixed $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param mixed $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }


    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }



    /**
     * @return mixed
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNationID()
    {
        return $this->nationID;
    }



    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }



    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getStarting()
    {
        return $this->starting;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $bill
     */
    public function setBill($bill)
    {
        $this->bill = $bill;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $confirmation
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @param mixed $ending
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }



    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }



    /**
     * @param mixed $msisdn
     */
    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $nationID
     */
    public function setNationID($nationID)
    {
        $this->nationID = $nationID;
    }


    /**
     * @param mixed $package
     */
    public function setPackage($package)
    {
        $this->package = $package;
    }


    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @param mixed $starting
     */
    public function setStarting($starting)
    {
        $this->starting = $starting;
    }

    /**
     * @param mixed $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "admin@ultramedhealth.com";
        $this->mail->Password = "";
        $this->mail->SMTPSecure = "TLS"; //ssl
        $this->mail->Port = 587; //465

        $db = new Database();
        $this->pdo = $db->PDO();
        $this->con = $db->mysqli();
    }

    public function domain(){
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }

        $uri .= $_SERVER['HTTP_HOST'];
        return $uri;
    }

    public function escape_data ($data) {

        if (function_exists('mysqli_real_escape_string')) {
            $data = mysqli_real_escape_string ($this->con, trim($data));
            $data = strip_tags($data);
        } else {
            $data = mysqli_escape_string ($this->con, trim($data));
            $data = strip_tags($data);
        }
        return $data;

    }

    public function validateParameter($fieldName, $value, $dataType, $required = true){

        $data = "";

        if ($required == true) {

            if (empty($value) == true || $value == "") {
                $data = array('message' => $fieldName . ' cannot be empty');
            }

        }

        switch ($dataType) {
            case BOOLEAN:
                if (!is_bool($value)) {
                    $data .= array('message' => $fieldName . ' should be boolean');
                }
                break;

            case INTEGER:
                if (!is_numeric($value)) {
                    $data = array('message' => $fieldName . ' should be integer');
                }
                break;
            case "town":
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be string');
                }

                $province = new MembershipNumber();
                $towns  = $province->townData();

                $count = 0;
                foreach ($towns as $key){
                    if (in_array(strtolower($value), $key['cities'], true)){
                        $count = $count + 1;
                    }
                }

                if ($count == 0){
                    $data = array('message' => $fieldName . ' is not valid town');
                }
                break;
            case "package":
                if (!is_numeric($value)) {
                    $data = array('message' => $fieldName . ' should be package INT ID');
                }
                break;
            case 'pin':
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be string');

                }
                if (!preg_match('%^[0-9]\S{4,}$%', stripslashes(trim($value)))) {
                    $data = array('message' => 'Pin should be atleast 4 numbers only');

                }
                break;
            case "mobile":
                //validate mobile number here and add 00263
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be valid mobile number');

                }
                break;
            case "email":
                if (!preg_match ('%^[A-Za-z0-9._\%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$%', stripslashes(trim($value)))) {
                    $data = array('message' => $fieldName . ' is not a valid email');
                }
                break;
            case STRING:
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be string');

                }
                break;

            default:
                $data = array('message' => 'Datatype not valid');

                break;

        }

        if ($data == ""){
            return array('success' => true, 'data' => $this->escape_data($value));
        }else{
            return array('success' => false, 'statusCode' => FORBIDEN, 'error'=> array('type' => "PARAMETER_ERROR", 'message' => $data['message']));
        }

    }

    public static function createString($len){
        $string = "1qay2wsx3edc4rfv5tgb6zhn7ujm8ik9ollpAQWSXEDCVFRTGBNHYZUJMKILOP";
        return substr(str_shuffle($string), 0, $len);
    }

    public function login(){
        try{
            $u = $this->getEmail();
            $sql = "SELECT COUNT(*) FROM `users` WHERE `email` = '$u'";

            if ($res = $this->pdo->query($sql)) {

                if ($res->fetchColumn() == 1) {
                    $query = "SELECT * FROM `users` WHERE `email`= :username";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute(array(':username' => $this->getEmail()));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $hash = $row['password'];

                    if (password_verify($this->getPassword(), $hash)) {
                        $paylod = [
                            'iat' => time(),
                            'iss' => 'localhost',
                            'exp' => time() + (60*60*8),
                            'userId' => $row['id']
                        ]; //expires in 8 hours

                        $token = JWT::encode($paylod, SECRETE_KEY);
                        $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Login successful','token'=>$token);
                        return $data;

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
        $ID = $this->getNationID();
        $name = $this->getName();
        $sname = $this->getLastName();
        $sex = $this->getGender();
        $dob = $this->getDob();
        $town = $this->getTown();
        $address = $this->getAddress();
        $package = $this->getPackage();

        $member = new MembershipNumber();
        $member->setCity($town);
        $mb = $member->number();
        $mib = $mb['number'];



        $pin = password_hash($this->getPin(), PASSWORD_BCRYPT, array("cost" => 10));

        try{

            $check = "SELECT COUNT(*) FROM `members` WHERE `msisdn` = '$u'";

            if ($res = $this->pdo->query($check)) {

                if ($res->fetchColumn() !=0) {
                    $data = array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array(
                            'type' => "SERVER_ERROR",
                            'message' => 'User MSISDN already exists')
                    );

                    return $data;
                }else {
                    $sql = "INSERT INTO `members`(`id`, `name`, `surname`, `id_number`, `membership_no`, `dob`, `gender`, `msisdn`, `address`, `town`, `created`, `updated`) VALUES ('','$name','$sname','$ID','$mib','$dob','$sex','$u','$address', '$town',now(),'')";
                    $insert = mysqli_query($this->con, $sql);
                    if ($insert){
                        $id = mysqli_insert_id($this->con);
                        $this->registrationInvoice($id);
                        $member->update($mb['province'], $mb['id']);

                        $msql = "INSERT INTO `member_details`(`id`, `member_id`, `package`, `subscription_status`) VALUES ('','$id','$package', '0');";

                        $msql .= "INSERT INTO `member_login`(`id`, `member_id`, `msisdn`, `pin`, `password`, `status`, `created`, `updated`) VALUES ('','$id','$u','$pin','','0',now(),'')";
                        $details = mysqli_query($this->con, $msql);
                        if ($details){
                            $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Account created successfully');
                            return $data;
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
                            'profile' => $row['profile_image']
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

}