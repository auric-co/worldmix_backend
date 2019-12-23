<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/25/2019
 * Time: 2:16 PM
 */
include_once dirname(__FILE__) . '/Database.php';
include_once dirname(__FILE__) . '/SMS.php';
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;

class System
{
    protected $pdo;
    protected $con;
    protected $email;
    protected $sms;
    protected $password;
    protected $newPassword;
    protected $name;
    protected $msisdn;
    protected $lastName;
    protected $address;
    protected $dob;
    protected $town;
    protected $token;
    protected $category;
    protected $subcategory1;
    protected $subcategory2;
    protected $subcategory3;
    protected $location;
    protected $brand;
    protected $model;
    protected $desc;
    protected $price;
    protected $price2;
    protected $id;
    protected $code;
    protected $mail;
    protected $type;
    protected $country;
    protected $countryCode;
    protected $dateStart;
    protected $deadline;
    protected $bedrooms;
    protected $jobLevel;
    protected $jobQualification;
    protected $vehicleFuel;
    protected $vehicleTransmission;

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
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
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
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
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getSubcategory1()
    {
        return $this->subcategory1;
    }

    /**
     * @return mixed
     */
    public function getSubcategory2()
    {
        return $this->subcategory2;
    }

    /**
     * @return mixed
     */
    public function getSubcategory3()
    {
        return $this->subcategory3;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @param mixed $subcategory1
     */
    public function setSubcategory1($subcategory1)
    {
        $this->subcategory1 = $subcategory1;
    }

    /**
     * @param mixed $subcategory3
     */
    public function setSubcategory3($subcategory3)
    {
        $this->subcategory3 = $subcategory3;
    }

    /**
     * @param mixed $subcategory2
     */
    public function setSubcategory2($subcategory2)
    {
        $this->subcategory2 = $subcategory2;
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
    public function getCategory()
    {
        return $this->category;
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
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * @param mixed $price2
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;
    }

    /**
     * @return mixed
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param mixed $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return mixed
     */

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
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getVehicleTransmission()
    {
        return $this->vehicleTransmission;
    }

    /**
     * @param mixed $vehicleTransmission
     */
    public function setVehicleTransmission($vehicleTransmission)
    {
        $this->vehicleTransmission = $vehicleTransmission;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }


    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
    public function getJobLevel()
    {
        return $this->jobLevel;
    }

    /**
     * @return mixed
     */
    public function getJobQualification()
    {
        return $this->jobQualification;
    }

    /**
     * @param mixed $jobQualification
     */
    public function setJobQualification($jobQualification)
    {
        $this->jobQualification = $jobQualification;
    }

    /**
     * @param mixed $jobLevel
     */
    public function setJobLevel($jobLevel)
    {
        $this->jobLevel = $jobLevel;
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

    /**
     * @return mixed
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param mixed $bedrooms
     */
    public function setBedrooms($bedrooms)
    {
        $this->bedrooms = $bedrooms;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return mixed
     */
    public function getVehicleFuel()
    {
        return $this->vehicleFuel;
    }

    /**
     * @param mixed $vehicleFuel
     */
    public function setVehicleFuel($vehicleFuel)
    {
        $this->vehicleFuel = $vehicleFuel;
    }

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "noreply@worldmixapp.com";
        $this->mail->Password = "";
        $this->mail->SMTPSecure = "TLS"; //ssl
        $this->mail->Port = 587; //465

        $db = new Database();
        $this->pdo = $db->PDO();
        $this->con = $db->mysqli();

        //sms class by easysendsms
            $this->sms = New SMS(SMSUser,SMSPass,"Worldmix");
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

    public function Categories(){
        $sql = "SELECT * FROM `categories` WHERE 1";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $cat = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description'],
                    'icon' => $row['icon']
                );

                array_push($cat, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'categories' => $cat
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Categories found'
            );
        }
    }

    public function SubCategories1(){
        $id = $this->getId();
        $sql = "SELECT * FROM `higher_level_sub_category` WHERE `parent_id` = '$id' ";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $cat = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description']
                );

                array_push($cat, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'categories' => $cat
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Sub Categories found'
            );
        }
    }

    public function SubCategories2(){
        $id = $this->getId();
        $sql = "SELECT * FROM `middle_level_sub_category` WHERE `parent_id` = '$id' ";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $cat = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description']
                );

                array_push($cat, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'categories' => $cat
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Sub Categories found'
            );
        }
    }

    public function SubCategories3(){
        $id = $this->getId();
        $sql = "SELECT * FROM `lower_level_sub_category` WHERE `parent_id` = '$id' ";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $cat = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description']
                );

                array_push($cat, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'categories' => $cat
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Sub Categories found'
            );
        }
    }

    public function listingType(){
        $sql = "SELECT * FROM `listing_type` WHERE 1";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $type = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description']
                );

                array_push($type, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'type' => $type
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Listing Types found'
            );
        }
    }

    public function requestType(){
        $sql = "SELECT * FROM `request_type` WHERE 1";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){

            $type = array();
            while ($row = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'details' => $row['description']
                );

                array_push($type, $ct);
            }

            return array(
                'success' => true,
                'statusCode' => SUCCESS_RESPONSE,
                'type' => $type
            );
        }else{
            return array(
                'success' => true,
                'statusCode' => NOT_FOUND,
                'categories' => null,
                'message' => 'No Request Types found'
            );
        }
    }

    public function sendSMS($message, $mssdn){
        $this->sms->setMessage($message);
        $this->sms->setTo($mssdn);
        $sent = $this->sms->send();

    }

}