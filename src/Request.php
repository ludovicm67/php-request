<?php

namespace ludovicm67\Request;

use ludovicm67\Request\Exception\RequestException;

class Request {

  private $content;
  private $infos;
  private $empty = true;

  public function __construct(RequestBuilder $request) {
    if (!filter_var($request->getUrl(), FILTER_VALIDATE_URL)) {
      throw new RequestException('Please use a valid URL!');
    }

    if (!$request->allowedUnsecure()) {
      $parsedUrl = parse_url($request->getUrl());
      if (!isset($parsedUrl['scheme'])
        || (!in_array($parsedUrl['scheme'], ['http', 'https']))
      ) {
        throw new RequestException('Request not allowed!');
      }
    }

    $this->runRequest($request);
  }

  private function runRequest(RequestBuilder $request) {
    $ch = curl_init($request->getUrl());
    curl_setopt_array($ch, $request->getCurlOptions());
    $this->content = curl_exec($ch);
    if ($this->content === false) {
      $this->content = '';
    } else {
      $this->empty = false;
    }
    $this->infos = curl_getinfo($ch);
    curl_close($ch);
  }

  public function getContent() {
    return $this->content;
  }

  public function getInfo($key) {
    return $this->infos[$key];
  }

  public function getInfos() {
    return $this->infos;
  }

  public function isEmpty() {
    return $this->empty;
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
