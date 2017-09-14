<?php

namespace ludovicm67\Request;

use ludovicm67\Request\Exception\RequestException;

class Request {

  private $content;
  private $infos;
  private $empty = true;

  public function __construct(RequestBuilder $request) {
    if (!filter_var($request->getUrl(), FILTER_VALIDATE_URL)) {
      throw new RequestException("Please use a valid URL!");
    }

    $this->runRequest($request);
  }

  private function runRequest(RequestBuilder $request) {
    $ch = curl_init($request->getUrl());
    curl_setopt_array($ch, $request->getCurlOptions());
    $this->content = curl_exec($ch);
    if ($this->content === false) {
      $this->content = "";
    } else {
      $this->empty = false;
    }
    $this->infos = curl_getinfo($ch);
    curl_close($ch);

    $code = $this->infos["http_code"];
    if ($code >= 400 && $code < 500 && ini_get('allow_url_fopen')) {
      $this->content = file_get_contents($this->infos["url"]);
      preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $http_response_header[0], $out);
      $this->infos["http_code"] = intval($out[1]);
    }
  }

  public function getContent() {
    return $this->content;
  }

  public function getInfos() {
    return $this->infos;
  }

  public static function fetch(RequestBuilder $request) {
    return (new Request($request))->getContent();
  }

  public static function fetchContent(RequestBuilder $request) {
    return self::fetch($request);
  }

  public static function fetchAll(RequestBuilder $request) {
    return new Request($request);
  }

}
