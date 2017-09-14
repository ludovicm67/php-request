<?php

namespace ludovicm67\Request;

class RequestBuilder {

  private $url;
  private $followlocation = true;
  private $encoding;
  private $useragent;
  private $autoreferer    = true;
  private $connecttimeout = 120;
  private $timeout        = 120;
  private $maxredirs      = 10;
  private $post           = false;
  private $postfields;
  private $ssl_verifyhost = 0;
  private $ssl_verifypeer = false;

  public function __construct(string $url = '', $options = []) {
    $this->url = $url;
    $this->setOptions($options);
  }

  private function parseOptions(array $options) {
    $options_keys = [
      'followlocation',
      'encoding',
      'useragent',
      'autoreferer',
      'connecttimeout',
      'timeout',
      'maxredirs',
      'post',
      'postfields',
      'ssl_verifyhost',
      'ssl_verifypeer',
    ];

    foreach ($options_keys as $key) {
      if (isset($options[$key])) {
        $this->{$key} = $options[$key];
      }
    }
  }

  public function setOptions(array $options) {
    if (!empty($options)) $this->parseOptions($options);
    return $this;
  }

  public function getOptions() {
    return [
      'followlocation' => $this->followlocation,
      'encoding'       => $this->encoding,
      'useragent'      => $this->useragent,
      'autoreferer'    => $this->autoreferer,
      'connecttimeout' => $this->connecttimeout,
      'timeout'        => $this->timeout,
      'maxredirs'      => $this->maxredirs,
      'post'           => $this->post,
      'postfields'     => $this->postfields,
      'ssl_verifyhost' => $this->ssl_verifyhost,
      'ssl_verifypeer' => $this->ssl_verifypeer,
    ];
  }

  public function setUrl(string $url) {
    $this->url = $url;
    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setFollowLocation(bool $followlocation = true) {
    $this->followlocation = $followlocation;
    return $this;
  }

  public function setEncoding(string $encoding) {
    $this->encoding = $encoding;
    return $this;
  }

  public function setUserAgent(string $useragent) {
    $this->useragent = $useragent;
    return $this;
  }

  public function setAutoReferer(bool $autoreferer = true) {
    $this->autoreferer = $autoreferer;
    return $this;
  }

  public function setConnectTimeout(int $time) {
    $this->connecttimeout = $time;
    return $this;
  }

  public function setTimeout(int $time) {
    $this->timeout = $time;
    return $this;
  }

  public function setMaxRedirs(int $max) {
    $this->maxredirs = $max;
    return $this;
  }

  public function setVerifySSL() {
    $this->ssl_verifyhost = 2;
    $this->ssl_verifypeer = true;
    return $this;
  }

  public function setPost($datas) {
    $this->post = true;
    $this->postfields = $datas;
    return $this;
  }

  public function getCurlOptions() {
    return [
      // needed
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'cache-control: no-cache'
      ],

      // from user configuration
      CURLOPT_FOLLOWLOCATION => $this->followlocation,
      CURLOPT_ENCODING       => $this->encoding,
      CURLOPT_USERAGENT      => $this->useragent,
      CURLOPT_AUTOREFERER    => $this->autoreferer,
      CURLOPT_CONNECTTIMEOUT => $this->connecttimeout,
      CURLOPT_TIMEOUT        => $this->timeout,
      CURLOPT_MAXREDIRS      => $this->maxredirs,
      CURLOPT_POST           => $this->post,
      CURLOPT_POSTFIELDS     => $this->postfields,
      CURLOPT_SSL_VERIFYHOST => $this->ssl_verifyhost,
      CURLOPT_SSL_VERIFYPEER => $this->ssl_verifypeer
    ];
  }
}
