<?php

////////////////////////////////////////
///         Config Section           ///
////////////////////////////////////////


/*----Proxy Server----*/

//Proxies gotta protect your server IP
//Switch true or false to use a proxy server
$use_proxy = false;

//Optional Proxy Server
$proxy_server_ip = "192.99.54.185";
$proxy_server_port = 44551;

//Proxy Type
$proxy_type = CURLPROXY_SOCKS5;


/*----UserAgent----*/
//Use a Fake UserAgent = true;
//Proxy real UserAgent = false;
$use_fake_useragent = true;


/*----TimeOut----*/
//Time to abort script execution, use seconds value
//15 * 60 = 15 minutes
$time_to_abort = 15*60;


//End of config section
//--------------------------------------

//Get the remote filename
function getDisposition($content){
	//Ignore warnings, needed to suppress errors on buffer
	error_reporting(E_ALL ^ E_WARNING);
	//Get content-disposition filename
	$ret = explode('"', explode('; filename="', $content)[1])[0];
	//Reactive warnings
	error_reporting(E_ALL);

	//Check size, case 0 return false,
	return (strlen($ret) == 0) ? false : $ret;
}

//Needed to allow XHR - CORS
header('Access-Control-Allow-Origin: *');

//Added improvement to some free web hosting
//Start buffering base header
ob_start();

//File Url
$url = $_GET['url'];

//Set PHP Execution time
ini_set('max_execution_time', $time_to_abort);

//Set UserAgent string
$useragent = (!$use_fake_useragent) ? $_SERVER['HTTP_USER_AGENT'] : "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";


//Get URL headers
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, $time_to_abort);
curl_setopt($ch, CURLOPT_URL, $url);

//Case use_proxy is set to true, define proxy to cURL
if($use_proxy){
	curl_setopt($ch, CURLOPT_PROXY, $proxy_server_ip.':'.$proxy_server_port);
	curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
}

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

//Try get file size from headers
$contentLenght = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

//Workaround to get file size
if($contentLenght == -1){
    if( preg_match( "/Content-Length: (\d+)/", $response, $matches ) ) {
      $contentLenght = (int)$matches[1];
    }
}

//Stream Original Content-Type/Content-Length/HTTP Code
header('Content-Type: '.$contentType);		
header('Content-Length: '.$contentLenght);
header('HTTP/1.1: '.$http_code);

//Get Disposition filename
$filename = getDisposition($response);
//Check whether that have filename
if($filename != false){
    //Send header Disposition
    header('Content-disposition: attachment; filename='.$filename); 
}

//Added improvement to some free web hosting
//End buffering base header
ob_end_clean();

$offset = 0;
$length = $contentLenght;
if (isset($_SERVER['HTTP_RANGE'])) {
    $partialContent = true;
    preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    $offset = intval($matches[1]);
    $length = $contentLenght - $offset - 1;
} else {
    $partialContent = false;
}
if ($partialContent) {
    header('HTTP/1.1 206 Partial Content');
    header('Accept-Ranges: bytes');
    header('Content-Range: bytes '.$offset.
        '-'.($offset + $length).
        '/'.$contentLenght);
} else {
    header('Accept-Ranges: bytes');
}


$ch = curl_init();
if (isset($_SERVER['HTTP_RANGE'])) {
    // If HTTP_RANGE header is setted up, we're dealing with partial content
    $partialContent = true;
    // Stream range from client to real url server
    preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    $offset = intval($matches[1]);
    $length = $contentLenght - $offset - 1;
    $headers = array(
        'Range: bytes='.$offset.
        '-'.($offset + $length).
        ''
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, $time_to_abort);
curl_setopt($ch, CURLOPT_URL, $url);

//Case use_proxy is set to true, define proxy to cURL
if($use_proxy){
	curl_setopt($ch, CURLOPT_PROXY, $proxy_server_ip.':'.$proxy_server_port);
	curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
}

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_exec($ch);
