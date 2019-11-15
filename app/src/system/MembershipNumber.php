<?php

/**
 * Created by PhpStorm.
 * User: chris
 * Date: 9/6/2019
 * Time: 10:29 AM
 */
class MembershipNumber
{
    protected $city;
    protected $id;
    protected $pdo;
    protected $con;

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->PDO();
        $this->con = $db->mysqli();
    }
    public  function Province(){
        $city = $this->getCity();
        $pdata = json_decode(file_get_contents('province.json'), true);

        foreach($pdata as $keys){
            if (in_array(strtolower($city), $keys['cities'], true)){

                return array("code" => $keys['code'], 'id' => $keys['lastID']);
                break;
            }

        }

    }

    public function townData(){
        $pdata = json_decode(file_get_contents('province.json'), true);
        return $pdata;
    }
    public  function memberProvinceId($id){

        return str_pad($id + 1, 6, "0", STR_PAD_LEFT);
    }

    public function number(){
        $data =  $this->Province();
        return  array('number' => "UMH-".$data['code'].$this->memberProvinceId($data['id']), 'province' => $data['code'], 'id' => $data['id'] + 1);

    }


    public function dependantNumber(){
        $member = $this->getId();

        $check = "SELECT * FROM `dependant` WHERE `member_id` = '$member'";
        $csql = mysqli_query($this->con, $check);
        $count = mysqli_num_rows($csql);
        $dpid = $count + 1;
        $sql = "SELECT `membership_no` FROM `members` WHERE `id` = '$member'";
        $qry = mysqli_query($this->con, $sql);
        $rs = mysqli_fetch_assoc($qry);
        $id = $rs['membership_no'];

        return $id."-0".$dpid;
    }

    public function update($province, $id){
        $old = $this->townData();
        $old[$province]['lastID'] = $id;
        file_put_contents("province.json",json_encode($old));
        return $old;
    }

    public function start(){
        $old = json_decode(file_get_contents('province.json'), true);

        $data = array (
            "01" => array ( "name" => "Harare Province","shortHand" => "hre","code" => "01", "cities" =>
                array( "harare","cold comfort","tynwald"), "lastID" => 0
            ),
            "02" => array ( "name" => "Bulawayo Province", "shortHand" => "byo", "code" => "02", "cities" =>
                array( "bulawayo","esigodhini","lupane"), "lastID" => 0
            ),
            "03" => array( "name" => "Midlands Province", "shortHand" => "midlands", "code" => "03","cities" =>
                array( "gweru","kwekwe","kadoma"), "lastID" => 0
            ),
            "04" => array( "name" => "Masvingo Province", "shortHand" => "masvingo", "code" => "04", "cities" =>
                array( "masvingo"), "lastID" => 0
            ),
            "05" => array( "name" => "Manicaland Province", "shortHand" => "manicaland", "code" => "05", "cities" =>
                array("mutare","nyanga","inyanga"),"lastID" => 0
            ),
            "06" => array( "name" => "Mashonaland Central Province", "shortHand" => "mash central", "code" => "06", "cities" =>
                array(), "lastID" => 0
            ),
            "07" => array( "name" => "Mashonaland East Province", "shortHand" => "mash east","code" => "07", "cities" =>
                array("mutoko"), "lastID" => 0
            ),
            "08" => array( "name" => "Mashonaland West Province", "shortHand" => "mash west", "code" => "08", "cities" =>
                array("mutoko"), "lastID" => 0
            ),
            "09" => array( "name" => "Mashonaland South Province", "shortHand" => "mash south", "code" => "09", "cities" =>
                array("mutoko"), "lastID" => 0
            ),
            "10" => array( "name" => "Mashonaland North Province", "shortHand" => "mash north", "code" => "10", "cities" =>
                array("mutoko"), "lastID" => 0
            )
        );

        file_put_contents("province.json",json_encode($data));
        $old = json_decode(file_get_contents('province.json'), true);
        return $old;
    }

}