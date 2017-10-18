<?php
require_once 'Helper.php';
require_once 'Uploader.php';
require_once 'RequestManager.php';

class iosddl_net extends UploadServer
{

    /**
     * You must set :
     * $uploadUrl,
     * $formParameters_FirstPart, $formParameters_file_NamePart, $formParameters_LastPart,
     * $uploadHeaders
     * @return mixed
     */
    protected function Init()
    {
        $tracker = $this->SetUploadInfo();

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
        "Accept: application/json, text/javascript, */*; q=0.01".$eol.
        "Accept-Language: en-US,en;q=0.5".$eol.
        "Accept-Encoding: gzip, deflate".$eol.
        "X-Requested-With: XMLHttpRequest".$eol.
        "Referer: http://iosddl.net/account_home.html".$eol.
        "Cookie: filehosting=8tbq769d74e6prstsdcimleth1".$eol.
        "Connection: close".$eol; //keep-alive

        $this->formParameters_FirstPart = array(
            '_sessionid' => '8tbq769d74e6prstsdcimleth1',
            'cTracker"' => $tracker,
            'maxChunkSize' => '100000000',
            'folderId' => 'null'
        );

        $this->formParameters_file_NamePart = 'files[]';
        $this->formParameters_LastPart = array('file_description[]' => 'botload' );
    }

    private function SetUploadInfo()
    {
        $homeUrl = 'http://iosddl.net/account_home.html';
        $homePageHeadersList = array(
                'Host: iosddl.net',
                'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Accept-Encoding: gzip, deflate',
                'Referer: http://iosddl.net/',
                'Cookie: filehosting=8tbq769d74e6prstsdcimleth1',
                'Connection: close', // close connection
                'Upgrade-Insecure-Requests: 1'
            );

        if(Helper::USE_DEFAULT)
        {
            $this->uploadUrl = parse_url('http://iosddl.net/core/page/ajax/file_upload_handler.ajax.php?r=iosddl.net&p=http&csaKey1=6aab7d9594a9aa402f9f8bf462c74b29ac23a4299cd29a5ba569b044a0e19338&csaKey2=8b140c282e19ed241a9ee1faca90b5637f9a7b9e936092885504bd97a1719f2a');
            return 'be62756bb60c7852db35026c61c14f37';
        }


        $source = Helper::Get_HomePage($homeUrl, $homePageHeadersList);
        preg_match('/cTracker: \'(\w+)\'/',$source,$pid);

        if (empty($pid) || empty($pid[1]))
        {
            echo 'Colud not find cTracker';
            echo $source;
            exit;
        }
        //echo 'cTracker is ' . $pid[1] . '<br/>';
        $tracker = $pid[1];

        preg_match("/url:\s*'(.+?)'/", $source, $urlpart);
        if (empty($urlpart) || empty($urlpart[1]))
        {
            echo 'Colud not find Upload Url';
            echo $source;
            exit;
        }
        //echo 'Upload Url is ' . $urlpart[1] . '<br/>';
        $this->uploadUrl = parse_url($urlpart[1]);
        return $tracker;
    }
}