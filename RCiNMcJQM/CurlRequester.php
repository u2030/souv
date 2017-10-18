<?php
class CurlRequester
{
    /** @var IManager $requestManager */
    private $requestManager;

    public function __construct($manager)
    {
        $this->requestManager = $manager;
    }

    public function Start()
    {
        do
        {
            $inputs = $this->requestManager->GetRequestInfo();
            $ch = $this->GetCurlHandle($inputs);
            curl_exec($ch);
			//echo 'curl exe<br/>';
            curl_close($ch);

        }while($this->requestManager->HasRequest());

        $this->requestManager->GetResponse();
    }

    private function header_callback($ch, $header)
    {
        $this->requestManager->ReceiveHeader($header);
		return strlen($header);
	}

    private function write_callback($ch, $data)
    {
        $this->requestManager->ReceiveData($data);
        return strlen($data);
    }

    private function GetCurlHandle($inputs)
    {
        $url = $inputs->url;

        $headers = $inputs->headerLines;
        $command = $inputs->command;
        $data = $inputs->data ;

        $ch = curl_init();

        if(Helper::USE_PROXY)
            curl_setopt($ch,CURLOPT_PROXY, Helper::PROXY_SERVER . ':' . Helper::PROXY_PORT);

        if($command == "POST")
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        // https
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $curlRequestHeaders = array();
        //$headersLine = explode("\r\n", $headers);
        foreach ($headers as $line)
        {
            $curlRequestHeaders[] = $line;
        }

        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'header_callback'));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this, 'write_callback'));
        return $ch;
    }
}

class UrlUploader
{
    /** @var IManager $requestManager */
    private $requestManager;

    public function __construct($manager)
    {
        $this->requestManager = $manager;
    }

    public function Start()
    {
        $this->requestManager->ReceiveData('');
        $this->requestManager->GetResponse();
    }
}