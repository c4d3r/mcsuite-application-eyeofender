<?php
/**
 * Author: Maxim
 * Date: 20/10/13
 * Time: 11:03
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Helper;

class RESTHelper {

    # METHODS
    const METHOD_POST = "POST";
    const METHOD_GET = "GET";
    const METHOD_PUT = "PUT";
    const METHOD_PATCH = "PATCH";
    const METHOD_DELETE = "DELETE";

    # GENERAL
    const TIMEOUT = 10;

    # TYPES
    const TYPE_JSON = "application/json";

    # DEPENDENCIES
    protected $logger;

    # VARS
    protected $headers;
    protected $data;

    protected $curl;
    protected $url;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function open($url)
    {
        $this->url = $url;
        $this->curl = curl_init($url);
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
    }
    public function execute($method, $headers, $data = NULL)
    {
        curl_setopt ($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($this->curl, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true); // This make sure will follow redirects
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false); // This too
        curl_setopt($this->curl, CURLOPT_HEADER, true); // THis verbose option for extracting the headers

        if($method == self::METHOD_POST)
        {
            if($data != NULL) {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($this->curl, CURLOPT_POST, true);
        }
        else if($method == self::METHOD_GET)
        {
            curl_setopt($this->curl, CURLOPT_HTTPGET, true);
            $url = $this->url . '?' . http_build_query($data, null, '&');
        }
        else if($method == self::METHOD_PUT)
        {
            //TODO: Create PUT request for files
        }
        else
        {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);


        if(!($response = curl_exec($this->curl))) {
            $this->logger->err('[cURL Failure] ' . curl_error($this->curl));
        }

        $this->treatResponse($response);
        return $this;
    }
    public function close()
    {
        curl_close($this->curl);
    }

    private function treatResponse($r) {

        if($r == null or strlen($r) < 1) {
            return;
        }

        $parts  = explode("\n\r",$r); // HTTP packets define that Headers end in a blank line (\n\r) where starts the body
        while(preg_match('@HTTP/1.[0-1] 100 Continue@',$parts[0]) or preg_match("@Moved@",$parts[0])) {
            // Continue header must be bypass
            for($i=1;$i<count($parts);$i++) {
                $parts[$i - 1] = trim($parts[$i]);
            }
            unset($parts[count($parts) - 1]);
        }
        preg_match("@Content-Type: ([a-zA-Z0-9-]+/?[a-zA-Z0-9-]*)@",$parts[0],$reg);// This extract the content type
        $this->headers['content-type'] = $reg[1];
        preg_match("@HTTP/1.[0-1] ([0-9]{3}) ([a-zA-Z ]+)@",$parts[0],$reg); // This extracts the response header Code and Message
        $this->headers['code'] = $reg[1];
        $this->headers['message'] = $reg[2];
        $this->data = "";
        for($i=1;$i<count($parts);$i++) {//This make sure that exploded response get back togheter
            if($i > 1) {
                $this->data .= "\n\r";
            }
            $this->data .= $parts[$i];
        }
    }

    public function getData()
    {
        return $this->data;
    }
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $curl
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return mixed
     */
    public function getCurl()
    {
        return $this->curl;
    }

}