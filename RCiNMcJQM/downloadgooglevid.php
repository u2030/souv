<?php
 if(!isset($_REQUEST["url"]) || strlen($_REQUEST["url"]) < 10){
	echo "no url";
	exit;
}

init();

$inputs = new Inputs();
$inputs->url = $_REQUEST["url"];
$inputs->rawHeaders = "Accept-Encoding: gzip, deflate";
$inputs->headerLines = array();
$inputs->headerLines[] = "Accept-Encoding: gzip, deflate";
$inputs->command = "GET";
$inputs->FileName = $_REQUEST["name"];

if(isset($_REQUEST["session"]))
	$inputs->sessionId = $_REQUEST["session"];

if(isset($_REQUEST["upurl"]))
	$inputs->uploadUrl = $_REQUEST["upurl"];

if(isset($_REQUEST["upload"]) && $_REQUEST["upload"] == true)
	$manager = new upfile_mobi($inputs);
else
	$manager = new url_uploadmor_com($inputs);
	//$manager = new ProxyManager($inputs);

$curlReq = new CurlRequester($manager);
$curlReq->Start();


function init(){
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);

    require_once 'Helper.php';
    require_once 'Uploader.php';
	require_once 'CurlRequester.php';

    require_once 'w_d_h_st.php';
    require_once 'w_iosddl_net.php';
    require_once 'w_upfile_mobi.php';
    require_once 'w_upload_af.php';
    require_once 'w_uploadmor_com.php';



    ob_start();
    ignore_user_abort(true);
    //set_time_limit(2000);
    //echo 'init done.<br/>';
//    ob_end_flush();

    Helper::$boundary = "---------------------------" . md5(mt_rand() . microtime());//'256771316730596'
}

class Inputs
{
    public $url;
    public $rawHeaders;
	public $headerLines;
    public $command;
    public $data;
	public $FileName;
	public $sessionId;
	public $uploadUrl;
}