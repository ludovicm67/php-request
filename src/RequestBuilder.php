<?php

namespace ludovicm67\Request;

use ludovicm67\Strings\Strings;

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

  /**
   * @param string $url
   * @param array $options
   **/
  public function __construct($url = '', $options = []) {
    $useragent = null;
    if (isset($_SERVER['HTTP_USER_AGENT'])
      && !empty($_SERVER['HTTP_USER_AGENT'])
    ) {
      $useragent = Strings::clean($_SERVER['HTTP_USER_AGENT']);
    }
    $this->useragent = $useragent;
    $this->url = $url;
    $this->setOptions($options);
  }

  /**
   * @param array $options
   **/
  private function parseOptions($options) {
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

  /**
   * @param array $options
   **/
  public function setOptions($options) {
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

  /**
   * @param string $url
   **/
  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  /**
   * @param bool $followlocation
   **/
  public function setFollowLocation($followlocation = true) {
    $this->followlocation = $followlocation;
    return $this;
  }

  /**
   * @param string $encoding
   **/
  public function setEncoding($encoding) {
    $this->encoding = $encoding;
    return $this;
  }

  /**
   * @param string $useragent
   **/
  public function setUserAgent($useragent) {
    $this->useragent = $useragent;
    return $this;
  }

  /**
   * @param bool $autoreferer
   **/
  public function setAutoReferer($autoreferer = true) {
    $this->autoreferer = $autoreferer;
    return $this;
  }

  /**
   * @param int $time
   **/
  public function setConnectTimeout($time) {
    $this->connecttimeout = $time;
    return $this;
  }

  /**
   * @param int $time
   **/
  public function setTimeout($time) {
    $this->timeout = $time;
    return $this;
  }

  /**
   * @param int $max
   **/
  public function setMaxRedirs($max) {
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
