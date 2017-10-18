<?php
require_once 'Helper.php';
require_once 'Uploader.php';
require_once 'RequestManager.php';

class upload_af extends UploadServer
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
        $this->SetUploadInfo();

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: https://upload.af/".$eol.
            'Origin: https://upload.af'.$eol.
            "Connection: close".$eol; //keep-alive;;

        $this->formParameters_FirstPart = array(
            'sess_id' => 'j8u75hzn7ngzj2e1',
            'utype' => 'reg',
            'file_descr' => 'botload',
            'file_public' => '',
            'link_rcpt' => '',
            'link_pass' => '',
            'to_folder' => '',
            'upload' => 'Start upload',
            '' => 'Add more',
            'keepalive' => '1',
        );
        $this->formParameters_file_NamePart = 'file_0';//'files[]';
        $this->formParameters_LastPart = array();//'file_description[]' => '' );
    }

    private function SetUploadInfo()
    {
        $homeUrl = 'https://upload.af/';
        $homePageHeadersList = array(
            'Host: upload.af',
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Accept-Encoding: gzip, deflate',
            'Referer: http://upload.af/',
            'Cookie: lang=english; xfss=j8u75hzn7ngzj2e1',
            'Connection: close', // close connection
            'Upgrade-Insecure-Requests: 1'
        );

        if(Helper::USE_DEFAULT)
            $source = '';
        else
            $source = Helper::Get_HomePage($homeUrl, $homePageHeadersList);

        //ParsSource
        preg_match('/id="uploadfile" action="(.*?)"/',$source,$pid);
        if (empty($pid) || empty($pid[1]))
        {
            echo 'Use default url. Colud not find uploadfile url';
            echo $source;
            $this->uploadUrl = parse_url('https://s38.uploadcdn.net/cgi-bin/upload.cgi?upload_type=file');
        }
        else
        {
            if (filter_var($pid[1], FILTER_VALIDATE_URL) === false)
            {
                echo 'Use default url. Invalid Url: ' . $pid[1] . '<br/>';
                $this->uploadUrl = parse_url('https://s38.uploadcdn.net/cgi-bin/upload.cgi?upload_type=file');
            }
            else
            {
                echo 'Upload Url is: ' . $pid[1] . '<br/>';
                $this->uploadUrl = parse_url($pid[1]);
            }
        }
    }
}

class url_upload_af extends UrlUploadServer
{
    protected function Init()
    {
        $this->SetUploadInfo();

        $eol = "\r\n";
        $this->uploadHeaders = "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0".$eol.
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".$eol.
            "Accept-Language: en-US,en;q=0.5".$eol.
            "Accept-Encoding: gzip, deflate".$eol.
            "Referer: https://upload.af/".$eol.
            'Origin: https://upload.af'.$eol.
            "Connection: close".$eol; //keep-alive;;

        $this->formParameters_FirstPart = array(
            'sess_id' => 'j8u75hzn7ngzj2e1',
            'utype' => 'reg',
            'url_mass' => $this->inputs->url
        );

        $this->formParameters_LastPart = array(
            'proxyurl' => '',
            'recemail' => '',
            'linkpass' => '',
            'to_folder' => 'default',
            'tos' => '',
            'keepalive' => '1',
        );
    }

    private function SetUploadInfo()
    {
        $homeUrl = 'https://upload.af/';
        $homePageHeadersList = array(
            'Host: upload.af',
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Accept-Encoding: gzip, deflate',
            'Referer: http://upload.af/',
            'Cookie: lang=english; xfss=j8u75hzn7ngzj2e1',
            'Connection: close', // close connection
            'Upgrade-Insecure-Requests: 1'
        );

        if(Helper::USE_DEFAULT)
            $source = '';
        else
            $source = Helper::Get_HomePage($homeUrl, $homePageHeadersList);

        //ParsSource
        preg_match('/id="uploadurl" action="(.*?)"/',$source,$pid);
        if (empty($pid) || empty($pid[1]))
        {
            echo 'Use default url. Colud not find uploadfile url';
            echo $source;
            $this->uploadUrl = parse_url('https://s35.uploadcdn.net/cgi-bin/upload.cgi?upload_type=url&upload_id='
                .Helper::GetRandomString(12));
        }
        else
        {
            if (filter_var($pid[1], FILTER_VALIDATE_URL) === false)
            {
                echo 'Use default url. Invalid Url: ' . $pid[1] . '<br/>';
                $this->uploadUrl =  parse_url('https://s35.uploadcdn.net/cgi-bin/upload.cgi?upload_type=url&upload_id='
                    .Helper::GetRandomString(12));
            }
            else
            {
                echo 'Upload Url is: ' . $pid[1] . '<br/>';
                $this->uploadUrl = parse_url($pid[1].'&upload_id='.Helper::GetRandomString(12));
            }
        }
    }

}