<?php
require_once 'Helper.php';
require_once 'Uploader.php';
require_once 'RequestManager.php';

class upfile_mobi extends UploadServer
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
			$this->inputs->uploadUrl = 'http://149.56.30.6:2052/upload_1';
		
        $this->uploadUrl = parse_url($this->inputs->uploadUrl);
		
        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://upfile.mobi/index.php".$eol.
            "Connection: close".$eol;

		if(!isset($this->inputs->sessionId) || $this->inputs->sessionId == null)
			$this->inputs->sessionId = '7cf2adf5692710ed1ff88de2cf02d5dd';
		
        $this->formParameters_FirstPart = array();
        $this->formParameters_file_NamePart = 'file';
        $this->formParameters_LastPart = array(
            'folder_id' => '78883',
            'pass' => '',
            'info' => '',
            'agree' => 'yes',
            'host' => 'upfile.mobi',
            'a_id' => '248468',
            'a_code' =>  $this->inputs->sessionId
		);
    }
}

class url_upfile_mobi extends UrlUploadServer
{
    protected function Init()
    {
		if(!isset($this->inputs->uploadUrl) || $this->inputs->uploadUrl == null)
			$this->inputs->uploadUrl = 'http://upfile.mobi/index.php?page=import_files_multiple';
        $this->uploadUrl = parse_url($this->inputs->uploadUrl);

        $eol = "\r\n";
        $this->uploadHeaders =  "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: http://upfile.mobi/index.php".$eol.
			"Cookie: __cfduid=ddff2440bc569c642839eab0577aaaa6e1494773697; aid=1k5pr2u6c0l8v8e1il00hden05; a_secret_id=248468; a_secret_code=7cf2adf5692710ed1ff88de2cf02d5dd".$eol.
            "Connection: close".$eol;
	
        $this->formParameters_FirstPart = array(
            'url' => $this->inputs->url,
			'filename' => $this->inputs->FileName
        );

        $this->formParameters_LastPart = array(
            'folder_id' => '',
            'info' => '',
            'pass' => '',
            'agree' => 'yes'
        );
    }
}