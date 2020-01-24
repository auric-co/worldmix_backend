<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 9/11/2019
 * Time: 2:46 PM
 * upload to public_html/cronjobs/
 */
include_once dirname(__FILE__) . '/../System.php';
include_once dirname(__FILE__) . '/../User.php';
include_once dirname(__FILE__) . '/../SMS.php';
include_once dirname(__FILE__) . '/../cronjobs/Match.php';
$match = new Match();

$match->matching();