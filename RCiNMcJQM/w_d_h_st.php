<?php
require_once 'Helper.php';
require_once 'Uploader.php';
require_once 'RequestManager.php';

class d_h_st extends UploadServer
{
    protected function Init()
	{
        if(Helper::USE_DEFAULT)
            $uploadId = '41c8b7587c2f20db12384ae8c0576888';
        else
            $uploadId = $this->SetUploadInfo();

        $this->uploadUrl = parse_url('http://fs1.d-h.st/upload?X-Progress-ID='.$uploadId);

        $eol = "\r\n";
        $this->uploadHeaders = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://d-h.st/".$eol.
            "Connection: close".$eol. //keep-alive
            "Upgrade-Insecure-Requests: 1".$eol;

        $this->formParameters_FirstPart = array(
            'UPLOAD_IDENTIFIER"' => $uploadId,
            'action' => 'upload',
            'uploadfolder' => '52416',
            'public' => '0',
            'user_id' => '165812'
        );
        $this->formParameters_file_NamePart = 'files[]';
        $this->formParameters_LastPart = array('file_description[]' => 'botload' );
	}

    private function SetUploadInfo()
    {
        $homeUrl = 'http://d-h.st/';
        $homePageHeadersList =
            array(
                'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Accept-Encoding: gzip, deflate',
                'Referer: http://d-h.st/',
                'Cookie: user=92aa383996c34fab0e39ef0c18bc7a0fbf3a246d%7E165812; session=fq7il3kb9ju2rreh523sou4up0',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1'
            );

        $source = Helper::Get_HomePage($homeUrl, $homePageHeadersList);

        preg_match('/X-Progress-ID=(\w+)"/',$source,$pid);
        if (empty($pid) || empty($pid[1]))
        {
            echo 'Colud not find X-Progress-ID';
            echo $source;
            exit;
        }
        return $pid[1];
    }
}

class url_d_h_st extends UploadServer
{
    protected function Init()
    {
        $this->uploadUrl = parse_url('http://fs1.d-h.st/');

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://d-h.st/".$eol.
            "Connection: close".$eol. //keep-alive
            "Upgrade-Insecure-Requests: 1".$eol;

        $this->lastPart = 'action=uploadurl&uploadfolder=52416&public=0&user_id=165812'.
            '&url='. urlencode($this->inputs->url);
    }

    protected function GetHeader($uploadUrl, $postLen)
    {
        // set upload request headers
        $eol = "\r\n";

        $headerData = "POST ".$uploadUrl['path'].((!empty($uploadUrl['query'])) ? '?'.$uploadUrl['query'] : '')." HTTP/1.1".$eol.
            "Host: ".$uploadUrl['host'].$eol.
            $this->uploadHeaders .
            "Content-Type: application/x-www-form-urlencoded". $eol. // Special
            "Content-Length: ".$postLen.$eol.
            $eol;
        return $headerData;
    }

    protected function initUpload()
    {
        $this->activeProgressBar = false;

        $postLen =  strlen($this->lastPart);
        $headerData = $this->GetHeader($this->uploadUrl, $postLen);

        if(Helper::USE_PROXY)
            $this->socketSender->Connect(Helper::PROXY_SERVER, Helper::PROXY_PORT);
        else
            $this->socketSender->Connect($this->uploadUrl['host'], ((isset($this->uploadUrl['port'])) ? $this->uploadUrl['port'] : 80));

        $this->socketSender->Send($headerData);

        //echo '<br/>' . $headerData .'<br/>'. $formData .'<br/>';
        //flush();
        //ob_flush();
    }
}

