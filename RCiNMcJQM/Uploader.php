<?php
require_once 'Helper.php';

class Uploader
{
	private $sock;
	
	public function __construct()
	{
		$this->Init();
	}
	
	private function Init()
	{
		$this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	}
	
	public function Connect($host, $port)
	{
		echo ' socket connecting to '.$host.':'. $port.'<br/>';
		socket_connect($this->sock, $host, $port) or die("error: could not connect to host\n");
		
		if (!$this->sock)
		{
			throw new Exception('Connection could not be established');
		}
		echo 'socket connected to '.$host.':'. $port.'<br/>';
		flush();
        ob_flush();
	}

	public function Send($data)
	{
	    //echo '[[['.strlen($data).']]]';
        //flush();
        //ob_flush();
		//echo '<br/>'. time() . ' - Socket send<br/>';
		//echo 'socket sending ['.$data.']<br/>';
        //if(strlen($data) > 0)
		    socket_send($this->sock, $data, strlen($data), 0);

	}
	
	function ReadResponse()
	{
		echo '<br/>'. time() . ' - Reading Response ... <br/>';
		flush();
        ob_flush();
		$headers = $this->readResponseHeaders($this->sock, $resp, $msg);
		//echo '<br/>'. time() . ' - Header Read end.<br/>';
        //var_dump($headers);
        //echo '<br>';
		$body    = $this->readResponseBody($this->sock, $headers);
		//echo '<br/>'. time() . ' - Body Read end. size ' . strlen($body).'<br/>';
        //echo $body .'<br>';
		return $body;
	}
	
function readResponseHeaders($sock, &$response_code, &$response_status)
{
    $headers = '';
    $read    = 0;

    while (true) {
        $headers .= socket_read($sock, 1);

		if ($headers === '') 
		{ 
			echo ('Connection reset by peer'); 
			exit;
		}
		
        $read    += 1;

        if ($read >= 4 && $headers[$read - 1] == "\n" && substr($headers, -4) == "\r\n\r\n") {
            break;
        }
    }

    $headers = $this->parseHeaders($headers, $resp, $msg);

    $response_code   = $resp;
    $response_status = $msg;

    return $headers;
}

function readResponseBody($sock, array $headers)
{
    $responseIsChunked = (isset($headers['transfer-encoding']) && stripos($headers['transfer-encoding'], 'chunked') !== false);
    $contentLength     = (isset($headers['content-length'])) ? $headers['content-length'] : -1;
    $isGzip            = (isset($headers['content-encoding']) && $headers['content-encoding'] == 'gzip') ? true : false;
    $close             = (isset($headers['connection']) && stripos($headers['connection'], 'close') !== false) ? true : false;

    $body = '';

    if ($contentLength >= 0) {
        $read = 0;
        do {
            $buf = socket_read($sock, $contentLength - $read);
            $read += strlen($buf);
            $body .= $buf;
        } while ($read < $contentLength);

    } else if ($responseIsChunked) {
        $body = $this->readChunked($sock);
    } else if ($close) {
        while (!feof($sock)) {
            $body .= socket_read($sock, 1024);
        }
    }

    if ($isGzip) {
        $body = gzinflate(substr($body, 10));
    }

    return $body;
}

function readChunked($sock)
{
    $body = '';

    while (true) {
        $data = '';

        do {
            $data .= socket_read($sock, 1);
        } while (strpos($data, "\r\n") === false);

        if (strpos($data, ' ') !== false) {
            list($chunksize, $chunkext) = explode(' ', $data, 2);
        } else {
            $chunksize = $data;
            $chunkext  = '';
        }

        $chunksize = (int)base_convert($chunksize, 16, 10);

        if ($chunksize === 0) {
            socket_read($sock, 2); // read trailing "\r\n"
            return $body;
        } else {
            $data    = '';
            $datalen = 0;
            while ($datalen < $chunksize + 2) {
                $data .= socket_read($sock, $chunksize - $datalen + 2);
                $datalen = strlen($data);
            }

            $body .= substr($data, 0, -2); // -2 to remove the "\r\n" before the next chunk
        }
    } // while (true)
}

function parseHeaders($headers, &$response_code = null, &$response_message = null)
{
    $lines  = explode("\r\n", $headers);
    $return = array();

    $response = array_shift($lines);

    if (func_num_args() > 1) {
        list($proto, $code, $message) = explode(' ', $response, 3);

        $response_code    = $code;

        if (func_num_args() > 2) {
            $response_message = $message;
        }
    }

    foreach($lines as $header) {
        if (trim($header) == '') continue;
		
        list($name, $value) = explode(':', $header, 2);
		$name = strtolower(trim($name));
		if ($name == 'set-cookie')
		{
			$return['set-cookie'][] = trim($value);
		}
		else
		{
			$return[$name] = trim($value);
		}
    }
    return $return;
}
}
