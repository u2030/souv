<?php

class Helper
{
    public static $eol = "\r\n";
    public static $boundary;
    const USE_DEFAULT = false;
    const USE_PROXY = false;
    const PROXY_SERVER = '127.0.0.1';
    const PROXY_PORT = 8889;

    public static function GetResponseBody($raw_response)
    {
        list($raw_res_headers, $raw_res_body) = explode("\r\n\r\n", $raw_response, 2);
        $res_Headers = Helper::parse_http_header($raw_res_headers);
        $res_body = Helper::decode_gzip($res_Headers, $raw_res_body);
        return $res_body;
    }

    /*
    public static function GetFirstPartOfFormData($params)
    {
        // invalid characters for "name" and "filename"
        //static $disallow = array("\0", "\"", "\r", "\n");

        $formData =  '--' . Helper::$boundary;
        $formData .= Helper::GetFormData($params);
        return $formData;
    }

    public static function GetFilePartOfFormData($filename, $fileType)
    {
        return Helper::$eol . 'Content-Disposition: form-data; name="files[]"; filename="' .$filename.'"'
            . Helper::$eol.'Content-Type: '.$fileType .Helper::$eol;
    }

    public static function GetLastPartOfFormData()
    {
        $lastPart = Helper::$eol . '--' . Helper::$boundary;
        $lastPart .= Helper::GetFormData(array('file_description[]' => '' ));
        $lastPart .= '--' . Helper::$eol;
        return $lastPart;
    }

    public static function GetFormData($params)
    {
        $eol = "\r\n";
        $formData = '';
        foreach ($params as $k => $v)
        {
            $formData .= $eol . 'Content-Disposition: form-data; name="'. $k . '"'.
                $eol . $eol . $v . $eol . '--' . Helper::$boundary;
        }
        return $formData;
    }
*/

    public static function GetFirstPartOfFormData2($params)
    {
        //$formData =  '--' . Helper::$boundary;
        $formData = Helper::GetFormData2($params);
        return $formData;
    }

    public static function GetFilePartOfFormData2($name , $filename, $fileType)//$name= 'files[]'
    {
        return '--' . Helper::$boundary . Helper::$eol .
        'Content-Disposition: form-data; name="'.$name.'"; filename="' .$filename.'"' . Helper::$eol.
        'Content-Type: '. $fileType .Helper::$eol; //todo check for extre new line
    }

    public static function GetLastPartOfFormData2($params)//array('file_description[]' => '' )
    {
        $lastPart = Helper::GetFormData2($params);
        $lastPart .= '--' . Helper::$boundary .'--' . Helper::$eol;
        return $lastPart;
    }

    public static function GetFormData2($params)
    {
        $eol = "\r\n";
        $formData = '';
        foreach ($params as $k => $v)
        {
            $formData .= '--' . Helper::$boundary . $eol .
                'Content-Disposition: form-data; name="'. $k . '"'. $eol .
                $eol .
                $v . $eol ;
        }
        return $formData;
    }

    public static function Get_HomePage($url, $curlRequestHeaders)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);

        if(Helper::USE_PROXY)
            curl_setopt($ch,CURLOPT_PROXY, Helper::PROXY_SERVER . ':' . Helper::PROXY_PORT);

        // https
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch,CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $pageSource = curl_exec($ch);
        curl_close($ch);
        return $pageSource;
    }

    public static function GetRandomString($len)
    {
        $UID = '';
        for($i=0;$i<$len;$i++)
            $UID .=''. rand(0,9);
        return $UID;
    }

    public static function decode_gzip($h,$d,$rn="\r\n")
    {
        if (isset($h['Transfer-Encoding']))
        {
            $lrn = strlen($rn);
            $str = '';
            $ofs=0;
            do{
                $p = strpos($d,$rn,$ofs);
                $len = hexdec(substr($d,$ofs,$p-$ofs));
                $str .= substr($d,$p+$lrn,$len);
                $ofs = $p+$lrn*2+$len;
            }while ($d[$ofs]!=='0');
            $d=$str;
        }
        if (isset($h['Content-Encoding'])) $d = gzinflate(substr($d,10));
        return $d;
    }

    public static function parse_http_header($str)
    {
        $lines = explode("\r\n", $str);
        $head  = array(array_shift($lines));
        foreach ($lines as $line)
        {
            list($key, $val) = explode(':', $line, 2);
            if ($key == 'Set-Cookie')
            {
                $head['Set-Cookie'][] = trim($val);
            }
            else
            {
                $head[$key] = trim($val);
            }
        }
        return $head;
    }

    public static function withHeaderString( $header )
    {
        // explode the string into lines.
        $lines = explode( "\n", $header );

        // extract the method and uri
        list( $method, $uri ) = explode( ' ', array_shift( $lines ) );

        $headers = [];

        foreach( $lines as $line )
        {
            // clean the line
            $line = trim( $line );

            if ( strpos( $line, ': ' ) !== false )
            {
                list( $key, $value ) = explode( ': ', $line );
                $headers[$key] = $value;
            }
        }

        // create new request object
        return array($method, $uri, $headers );
    }

    public static function decodeChunked($str)
    {
        for ($res = ''; !empty($str); $str = trim($str))
        {
            $pos = strpos($str, "\r\n");
            $len = hexdec(substr($str, 0, $pos));
            $res.= substr($str, $pos + 2, $len);
            $str = substr($str, $pos + 2 + $len);
        }
        return $res;
    }

}