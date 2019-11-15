<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 9/11/2019
 * Time: 2:46 PM
 * upload to public_html/cronjobs/
 */
include_once "../../ussd.ultramedhealth.com/app/src/system/System.php";
$invoice = new System();
$invoice->invoice();