<?php
//echo '<br/>'. time() . ' - Start<br/>';
init();
$inputs = GetInputs();
//echo "Params Read.<br/>";

list($type, $isUrlUpload) = GetRequestType($inputs);
//echo '<br/>type: ' . $type . ' - is url upload: ' . $isUrlUpload;

if($isUrlUpload)
{
    switch($type)
    {
        case 'upload.af':
            $manager = new url_upload_af($inputs);
            break;
        case 'uploadmor.com':
            $manager = new url_uploadmor_com($inputs);
            break;
        case 'd-h.st':
            $manager = new url_d_h_st($inputs);
            break;
    /*    case 'iosddl.net':
            $manager = new url_iosddl_net($inputs);
            break;*/
        default:
            echo 'invalid request type for url upload. you send ' . $type;
            exit;
    }

    $curlReq = new UrlUploader($manager);
    $curlReq->Start();
    exit;
}

switch($type)
{
	case 'proxy':
		$manager = new ProxyManager($inputs);
		break;
	case 'upload.af':
		$manager = new upload_af($inputs);
		break;
	case 'uploadmor.com':
		$manager = new uploadmor_com($inputs);
		break;
    case 'd-h.st':
        $manager = new d_h_st($inputs);
        break;
	case 'iosddl.net':
		$manager = new iosddl_net($inputs);
		break;
	default:
		echo 'invalid request type for file upload. you send ' . $type;
		exit;
}

//echo 'start curl';
$curlReq = new CurlRequester($manager);
$curlReq->Start();
//echo '<br/>'. time() . ' - End<br/>';
exit;
// ===============================
function init()
{
/*    
	ini_set('display_errors', 1); // 0
    ini_set('display_startup_errors', 1); //0 
    error_reporting(E_ALL); // 0
*/
    require_once 'Helper.php';
    require_once 'Uploader.php';
	require_once 'CurlRequester.php';

    require_once 'w_d_h_st.php';
    require_once 'w_iosddl_net.php';
    require_once 'w_upload_af.php';
    require_once 'w_uploadmor_com.php';



    ob_start();
    ignore_user_abort(true);
    //set_time_limit(2000);
    //echo 'init done.<br/>';
    ob_end_flush();

    Helper::$boundary = "---------------------------" . md5(mt_rand() . microtime());//'256771316730596'
}

function GetInputs()
{
    $inputs = new Inputs();
    $inputs->url = base64_decode($_REQUEST["u"]);
	if(isset($_REQUEST["h"]))
		$inputs->rawHeaders = base64_decode($_REQUEST["h"]);
	else
		$inputs->rawHeaders = "";
	if(isset($_REQUEST["c"]))
		$inputs->command = $_REQUEST["c"];
	else
		$inputs->command = "GET";
	
    if (isset($_REQUEST["b"]))
    {
        $inputs->data = base64_decode($_REQUEST["b"]);
    }
    return $inputs;
}

function GetRequestType($inputs)
{
	$reqType = 'proxy';
    $isUrlUpload = false;

	$inputs->headerLines = array();
	$lines = explode("\r\n", trim($inputs->rawHeaders, "\r\n"));

	foreach ($lines as $line)
	{
		//echo '<br/>' . $key;
		if (substr($line, 0, 9) === 'My-Server')
		{
			list($key, $val) = explode(':', $line, 2);
			$reqType = trim($val);
		}
		else if (substr($line, 0, 10) === 'Upload-Url')
        {
            $isUrlUpload = true;
        }
		else
		{
			$inputs->headerLines[] = $line;
			//echo '<br/> aaa  ' . $line;
		}
	}
	return array($reqType, $isUrlUpload);
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