<?php

interface IManager
{
    public function GetRequestInfo();
    public function ReceiveHeader($header);
    public function ReceiveData($data);
    public function HasRequest();
    public function GetResponse();
}

class ProxyManager implements IManager
{
    private $isChunked;
    private $inputs;

    public function __construct($inputs) {
        $this->inputs = $inputs;
    }

    public function GetRequestInfo()
    {
        return $this->inputs;
    }

    public function ReceiveHeader($header)
    {
        if (strpos($header, 'chunked') !== false)
        {
            $this->isChunked = true;
        }
        header($header, false);
    }

    public function ReceiveData($data)
    {
        if($this->isChunked == true)
        {
            $len = dechex(strlen($data));
            echo $len . "\r\n" . $data . "\r\n";
        }
        else
        {
            echo $data;
        }

        ob_flush();
        flush();
    }

    public function HasRequest()
    {
        return false;
    }

    public function GetResponse()
    {
        if($this->isChunked == true)
        {
            echo "0\r\n\r\n";
        }
    }

}

abstract class UploadServer implements IManager
{
    protected $socketSender;

    private $fileType,$fileLength, $redirectTo;
    /**
     * @var Inputs
     */
    protected $inputs;
    protected $lastPart;
    protected $activeProgressBar = true;

    private $isReadyToUpload;

    protected $formParameters_FirstPart;
    protected $formParameters_file_NamePart;
    protected $formParameters_LastPart;

    protected $uploadUrl;
    /**
     * @var string
     * without : host, content-length, content-type
     */
    protected $uploadHeaders;

    public function __construct($inputs) {
        $this->inputs = $inputs;
        $this->socketSender = new Uploader();
        $this->isReadyToUpload = false;
        $this->redirectTo = '';
        $this->fileType = "application/octet-stream"; // default
        $this->Init();
    }

    /**
     * You must set :
     * $uploadUrl,
     * $formParameters_FirstPart, $formParameters_file_NamePart, $formParameters_LastPart,
     * $uploadHeaders
     * @return mixed
     */
    abstract protected function Init();

    protected function GetHeader($uploadUrl, $postLen)
    {
        // set upload request headers
        $eol = "\r\n";

        $headerData = "POST ".$uploadUrl['path'].((!empty($uploadUrl['query'])) ? '?'.$uploadUrl['query'] : '')." HTTP/1.1".$eol.
            "Host: ".$uploadUrl['host'].$eol.
            $this->uploadHeaders .
            "Content-Type: multipart/form-data; boundary=". Helper::$boundary . $eol.
            "Content-Length: ".$postLen.$eol.
            $eol;
        return $headerData;
    }

    protected function initUpload()
    {
		if($this->inputs->FileName != null && strlen($this->inputs->FileName) > 0){
			$filename = $this->inputs->FileName;
		}
		else{
			$filename = basename($this->inputs->url);
			if($filename === '')
				$filename = md5(mt_rand());
			$filename = base64_encode($filename);
		}
		
        // generate upload request form post data
        $formData = Helper::GetFirstPartOfFormData2($this->formParameters_FirstPart);
        $preFile = Helper::GetFilePartOfFormData2($this->formParameters_file_NamePart, $filename, $this->fileType);

        $this->lastPart = Helper::$eol;// line break after file content
        $this->lastPart .= Helper::GetLastPartOfFormData2($this->formParameters_LastPart);

        $postLen =  strlen($this->lastPart) + strlen($formData) + strlen($preFile) + $this->fileLength;
        $headerData = $this->GetHeader($this->uploadUrl, $postLen);

        { // connect to upload server
            //echo '<br/>' . $this->uploadUrl['host'] .':'. ((isset($uploadUrl['port'])) ? $uploadUrl['port'] : 80);

            if(Helper::USE_PROXY)
                $this->socketSender->Connect(Helper::PROXY_SERVER, Helper::PROXY_PORT);
            else
                $this->socketSender->Connect($this->uploadUrl['host'], ((isset($this->uploadUrl['port'])) ? $this->uploadUrl['port'] : 80));

            flush();
            ob_flush();

            $this->socketSender->Send($headerData);
            $this->socketSender->Send($formData);
            $this->socketSender->Send($preFile);

            //echo '<br/>' . $headerData .'<br/>'. $formData .'<br/>'. $preFile.'<br/>';
            //flush();
            //ob_flush();
        }
    }

    public function GetRequestInfo()
    {
        $this->isReadyToUpload = false;
        $this->redirectTo = '';
        return $this->inputs;
    }

    public function ReceiveHeader($header)
    {
        //echo '<br/> === header === ' . $header;
        if (preg_match('/^HTTP/i', $header))
        {
            $parts = explode(' ', $header);
            if($parts[1] != '200')
            {
                echo 'response is ' . $header . '<br/>';
                if($parts[1] != '301' && $parts[1] != '302' )
                {
                    echo 'exit';
                    exit;
                }
            }
        }
        else if (strpos($header, ':') !== false)
        {
            //echo $header . '<br/>';
            list($key, $val) = explode(':', $header, 2);
            if ($key === 'Content-Type')
            {
                $this->fileType = $val;
            }
            else if ($key === 'Content-Length')
            {
                $this->fileLength = trim($val);
                echo 'file length is ' . $header . '<br/>';
            }
            else if ($key === 'Location')
            {
                // maybe hase set-cookie
                // and must use it
                echo '<br/>Redirect to:' . $val . '<br/>';
                $this->redirectTo = trim($val);
                $this->inputs->url = $this->redirectTo;
                //curl_close($resURL);
            }
        }
    }

    public function ReceiveData($data)
    {
        if($this->redirectTo !== '')
        {
            return;
        }

        if($this->isReadyToUpload === false)
        {
            $this->isReadyToUpload = true;
            echo '<br/>init Upload : '.$this->inputs->url.'<br/> ';
            flush();
            ob_flush();
            $this->initUpload();
            echo '<br/> Init Upload done.<br/>';//'. time() . ' -
            echo 'first chunk data len: '.strlen($data) .'<br/>';
            flush();
            ob_flush();
            $this->progressThreshold = 0;
            $this->sentDataLength = 0;
        }

        $this->socketSender->Send($data);
        if($this->activeProgressBar)
            $this->showProgress(strlen($data));
    }

    private $sentDataLength;
    private $progressThreshold;

    private function showProgress($currentLen)
    {
        $this->sentDataLength += $currentLen;
        //echo 'sp';
        if($this->sentDataLength >= $this->progressThreshold)
        {
            $this->progressThreshold += $this->fileLength / 10;
            if($this->progressThreshold > $this->fileLength && $this->sentDataLength<= $this->fileLength)
                $this->progressThreshold = $this->fileLength;

            echo round($this->sentDataLength / $this->fileLength, 2)*100 .'%  ('. $this->sentDataLength . ' B) sent.<br/>';
            flush();
            ob_flush();
        }
    }

    public function HasRequest()
    {
        if($this->redirectTo !== '')
            return true;
        return false;
    }

    public function GetResponse()
    {
        //echo '<br/>'. time() . ' - Send Last part<br/>';
        //echo $this->lastPart;
        //flush();
        //ob_flush();
        $this->socketSender->Send($this->lastPart);
        echo $this->socketSender->ReadResponse();
        echo '<br/>end';
    }
}

abstract class UrlUploadServer extends UploadServer
{
    protected function initUpload()
    {
        $this->activeProgressBar = false;

        // generate upload request form post data
        $formData = Helper::GetFirstPartOfFormData2($this->formParameters_FirstPart);
        $this->lastPart = Helper::GetLastPartOfFormData2($this->formParameters_LastPart);

        $postLen =  strlen($formData) + strlen($this->lastPart);
        $headerData = $this->GetHeader($this->uploadUrl, $postLen);


        if(Helper::USE_PROXY)
            $this->socketSender->Connect(Helper::PROXY_SERVER, Helper::PROXY_PORT);
        else
            $this->socketSender->Connect($this->uploadUrl['host'], ((isset($this->uploadUrl['port'])) ? $this->uploadUrl['port'] : 80));

        $this->socketSender->Send($headerData);
        $this->socketSender->Send($formData);

        //echo '<br/>' . $headerData .'<br/>'. $formData .'<br/>';
        //flush();
        //ob_flush();
    }
}
