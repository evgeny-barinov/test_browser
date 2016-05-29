<?php


class browser
{
    private $host;

    public $cookie = [];

    private $rawCookie = '';

    private $result = '';
    
    private $curl;

    public function __construct($host)
    {
        $this->host = $host;
        if (!$this->curl = curl_init()) {
            throw new Exception('Curl init failed');
        }
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function go($path, array $data = null)
    {

        curl_setopt($this->curl, CURLOPT_URL, $this->host . $path);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->rawCookie);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        if (is_array($data)) {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }
        $this->result = curl_exec($this->curl);
        $this->setCookie();
    }

    public function regexp($regexp)
    {
        $matches = [];
        if ($this->result) {
            preg_match_all($regexp, $this->result, $matches);
        }
        return $matches[1];
    }

    private function setCookie()
    {
        preg_match_all('/Set-Cookie:\s*(.*)/', $this->result, $matches);
        $this->rawCookie = trim(implode(';', $matches[1]));

        $cookie = explode(';', $this->rawCookie);
        foreach ($cookie as $keyVal) {
            list($key, $val) = explode('=', trim($keyVal));
            $this->cookie[$key] = $val;
        }
    }
}
