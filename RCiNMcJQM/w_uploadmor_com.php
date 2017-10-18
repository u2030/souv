<?php
require_once 'Helper.php';
require_once 'Uploader.php';
require_once 'RequestManager.php';

class uploadmor_com extends UploadServer
{
    /**
     * You must set :
     * $uploadUrl,
     * $formParameters_FirstPart, $formParameters_file_NamePart, $formParameters_LastPart,
     * $uploadHeaders
     */
    protected function Init()
    {
		if(!isset($this->inputs->uploadUrl) || $this->inputs->uploadUrl == null)
			$this->inputs->uploadUrl = 'http://up4.uploadmor.com/cgi-bin/upload.cgi?upload_type=file';
		
        $this->uploadUrl = parse_url($this->inputs->uploadUrl);

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://uploadmor.com/".$eol.
            'Origin: http://uploadmor.com'.$eol.
            "Connection: close".$eol; //keep-alive

		if(!isset($this->inputs->sessionId) || $this->inputs->sessionId == null)
			$this->inputs->sessionId = 'evjmwi1lcmb2khyg';
		
        $this->formParameters_FirstPart = array(
            'sess_id' => $this->inputs->sessionId,
            'utype' => 'reg',
            'file_descr' => 'botload',
            'file_public' => '1', // 1
            'link_rcpt' => '',
            'link_pass' => '',
            'to_folder' => '',
            'upload' => 'Start upload',
            '' => 'Add more',
            'keepalive' => '1',
        );
        $this->formParameters_file_NamePart = 'file_0';
        $this->formParameters_LastPart = array();
    }
}

class url_uploadmor_com extends UrlUploadServer
{
    protected function Init()
    {
		if(!isset($this->inputs->uploadUrl) || $this->inputs->uploadUrl == null)
			$this->inputs->uploadUrl = 'http://up4.uploadmor.com/cgi-bin/upload.cgi?upload_type=url';
        $this->uploadUrl = parse_url($this->inputs->uploadUrl);
		
		//&upload_id='.Helper::GetRandomString(12));

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://uploadmor.com/".$eol.
            'Origin: http://uploadmor.com'.$eol.
            "Connection: close".$eol; //keep-alive

		if(!isset($this->inputs->sessionId) || $this->inputs->sessionId == null)
			$this->inputs->sessionId = 'evjmwi1lcmb2khyg';
	
        $this->formParameters_FirstPart = array(
            'sess_id' => $this->inputs->sessionId,
            'utype' => 'reg',
            'file_public' => '1', // 1
            'url_mass' => $this->inputs->url
        );

        $this->formParameters_LastPart = array(
            'proxyurl' => '',
            'recemail' => '',
            'linkpass' => '',
            'to_folder' => 'default',
            'tos' => '',
            'keepalive' => '1'
        );
    }
}