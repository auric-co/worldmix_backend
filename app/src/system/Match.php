<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 1/4/2020
 * Time: 3:11 PM
 */

include_once dirname(__FILE__). '/../System.php';
include_once dirname(__FILE__). '/../User.php';
include_once dirname(__FILE__) . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;

class Match extends User
{

    protected $listing;
    protected $request;

    /**
     * @return mixed
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $listing
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
    }


    public function allListing(){

        $sql = "SELECT * FROM `listings` WHERE  `post` = '1'";
        $qry = mysqli_query($this->con, $sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($qry)){
            $id = array('id' => $row['id'], 'category' => $row['category'], 'type' => $row['type'], 'details'=> $this->Listings($row['id'],$row['user']), 'user' => $this->getUser($row['user']));
            array_push($list, $id);
        }
        return $list;
    }

    public function allRequests(){
        $sql = "SELECT * FROM `request` WHERE  `post` = '1'";
        $qry = mysqli_query($this->con, $sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($qry)){
            $id = array('id' => $row['id'], 'category' => $row['category'], 'type' => $row['type'], 'details'=> $this->Requests($row['id'],$row['user']), 'user' => $this->getUser($row['user']));
            array_push($list, $id);
        }
        return $list;

    }

    public function Requests($id, $user){
        $lsql = "SELECT * FROM `request` WHERE `id`= '$id' AND `user` =  '$user' ORDER BY `added` DESC ";
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

    public function Listings($id, $user){
        $lsql = "SELECT * FROM `listings` WHERE `id`= '$id' AND `user` =  '$user' ORDER BY `added` DESC ";
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

    public function getUser($id){
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  `id`='$id' AND `status` = '1'");

        if ($stmt->execute()) {
            $user = "";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = array('id' => $row['id'], 'msisdn' => $row['msisdn'], 'email' => $row['email'], 'country' => $row['country'], 'town' => $row['town']);
                $user = $id;
            }

            return $user;
        }else{
            return array();
        }
    }

    public function secondaryMatCheck(){

    }

    public function LogMatch(){

    }

    public function saveRequestMatch($user){
        $listing = $this->getListing();
        $request = $this->getRequest();
        if ($this->checkMatch("Request", $listing, $request) == true){

            $cat = $this->getCategory();
            $stmt = $this->pdo->prepare("INSERT INTO `matches`(`id`, `type`, `user`, `listing`, `request`, `category`, `status`, `added`) VALUES ('','Request','$user','$listing','$request','$cat','0',now())");

            if ($stmt->execute()) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    public function saveListingMatch($user){

        $listing = $this->getListing();
        $request = $this->getRequest();
        if ($this->checkMatch("Listing", $listing, $request) == true){
            $cat = $this->getCategory();
            $stmt = $this->pdo->prepare("INSERT INTO `matches`(`id`, `type`, `user`, `listing`, `request`, `category`, `status`, `added`) VALUES ('','Listing','$user','$listing','$request','$cat','0',now())");

            if ($stmt->execute()) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    public function checkMatch($type, $listing, $request){
        $sql = "SELECT * FROM `matches` WHERE `type` = '$type' AND `listing` = '$listing' AND `request` = '$request'";
        $qry = mysqli_query($this->con,$sql);

        if (mysqli_num_rows($qry) == 0){
            return true;
        }else{
            return false;
        }
    }

    public function saveBoth($lister, $requester, $u1, $u2){

        $ls = $this->getListing();
        $rq = $this->getRequest();
        if ($this->checkMatch("Request", $ls, $rq) == true && $this->checkMatch("Listing", $ls, $rq) == true){
            if ($this->saveListingMatch($lister) && $this->saveRequestMatch($requester)){
                $this->listingSMS($u1);
                $this->requestSMS($u2);
            }
        }

    }

    public function logCrobjob(){
        //
        $stmt = $this->pdo->prepare("INSERT INTO `cronjobs`(`id`, `status`, `time`) VALUES ('','1',now())");

        if ($stmt->execute()) {
            return true;
        }else{
            return false;
        }
    }

    public function matching(){

        $this->logCrobjob();
        $fullListing = $this->allListing();
        $fullRequest = $this->allRequests();
        for($i=0; $i<count($fullListing)-1; $i++) {
            foreach ($fullRequest as $key){
                switch ($fullListing[$i]['category']){
                    case "1":
                        if ($key['category'] == "1" &&  $fullListing[$i]['details']['details']['parent'] == $key['details']['details']['parent'])
                        {
                            if ($fullListing[$i]['user']['id'] !== $key['user']['id']){
                                $this->setListing($fullListing[$i]['id']);
                                $this->setRequest($key['id']);
                                $this->setCategory($fullListing[$i]['category']);
                                $this->saveBoth($fullListing[$i]['user']['id'], $key['user']['id'],$fullListing[$i]['user']['msisdn'], $key['user']['msisdn']);
                            }

                        }

                        break;
                    case "2":
                        $price = explode("-", $key['details']['details']['price']);
                        $min = $price[0];
                        $max = $price[1];
                        if ($key['category'] == "2" && $fullListing[$i]['category'] == "2"
                            && $key['details']['details']['property'] == $fullListing[$i]['details']['details']['property']
                            && $key['details']['details']['bedrooms'] == $fullListing[$i]['details']['details']['bedrooms']
                            && $key['details']['details']['town'] == $fullListing[$i]['details']['details']['town']
                            && $key['details']['details']['country'] == $fullListing[$i]['details']['details']['country'] &&
                            $fullListing[$i]['details']['details']['price'] >= $min && $fullListing[$i]['details']['details']['price'] <= $max)
                        {
                            if ($fullListing[$i]['user']['id'] !== $key['user']['id']){
                                $this->setListing($fullListing[$i]['id']);
                                $this->setRequest($fullListing[$i]['id']);
                                $this->setCategory($fullListing[$i]['category']);
                                $this->saveBoth($fullListing[$i]['user']['id'], $key['user']['id'],$fullListing[$i]['user']['msisdn'], $key['user']['msisdn']);
                            }

                        }

                        break;
                    case "3":

                        if ($key['category'] == "3" && $fullListing[$i]['category'] == "3" &&
                            $fullListing[$i]['details']['details']['category'] == $key['details']['details']['category'] &&
                            $key['details']['details']['field'] == $fullListing[$i]['details']['details']['field'] &&
                            $fullListing[$i]['details']['details']['level'] == $key['details']['details']['level'] &&
                            $fullListing[$i]['details']['details']['city'] == $key['details']['details']['city'] &&
                            $fullListing[$i]['details']['details']['country'] == $key['details']['details']['country'] &&
                            $key['details']['details']['title'] == $fullListing[$i]['details']['details']['title'])
                        {
                            if ($fullListing[$i]['user']['id'] !== $key['user']['id']){
                                $this->setListing($fullListing[$i]['id']);
                                $this->setRequest($key['id']);
                                $this->setCategory($fullListing[$i]['category']);
                                $this->saveBoth($fullListing[$i]['user']['id'], $key['user']['id'],$fullListing[$i]['user']['msisdn'], $key['user']['msisdn']);
                            }

                        }

                        break;
                    case "4":

                        $price = explode("-", $key['details']['details']['price']);
                        $min = $price[0];
                        $max = $price[1];
                        if ($key['category'] == "4" && $fullListing[$i]['category'] == "4" &&
                            $fullListing[$i]['details']['details']['mode'] == $key['details']['details']['mode'] &&
                            $fullListing[$i]['details']['details']['vehicleType'] == $key['details']['details']['vehicleType'] &&
                            $fullListing[$i]['details']['details']['subType'] == $key['details']['details']['subType'] &&
                            $key['details']['details']['brand'] == $fullListing[$i]['details']['details']['brand'] &&
                            $key['details']['details']['fuel'] == $fullListing[$i]['details']['details']['fuel'] &&
                            $fullListing[$i]['details']['details']['city'] == $key['details']['details']['city'] &&
                            $fullListing[$i]['details']['details']['country'] == $key['details']['details']['city'] &&
                            $fullListing[$i]['details']['details']['price'] >= $min && $fullListing[$i]['details']['details']['price'] <= $max)
                        {
                            if ($fullListing[$i]['user']['id'] !== $key['user']['id']){
                                $this->setListing($fullListing[$i]['id']);
                                $this->setRequest($key['id']);
                                $this->setCategory($fullListing[$i]['category']);
                                $this->saveBoth($fullListing[$i]['user']['id'], $key['user']['id'],$fullListing[$i]['user']['msisdn'], $key['user']['msisdn']);
                            }

                        }


                        break;
                    default:

                        break;
                }
            }
        }

    }

    public function listingSMS($u){
        $message = "Congratulations! Your listing has a new match. Please visit WorldMix to read more";
        $this->sms->setMessage($message);
        $this->sms->setTo($u);
        $sent = $this->sms->smsSend();
    }

    public function requestSMS($u){
        $message = "Congratulations! Your Request has a new match. Please visit WorldMix to read more.";
        $this->sms->setMessage($message);
        $this->sms->setTo($u);
        $sent = $this->sms->smsSend();
    }

    public function listingEmail(){

    }

    public function requestEmail(){

    }
}