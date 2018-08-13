<?php

namespace ludovicm67\Request\Tests;

use PHPUnit\Framework\TestCase;
use ludovicm67\Request\Exception\RequestException;
use ludovicm67\Request\Request;
use ludovicm67\Request\RequestBuilder;

class RequestTest extends TestCase {

  public function testUrlCheck() {
    $builder = new RequestBuilder('bad-url');
    $this->expectException(RequestException::class);
    $request = new Request($builder);
  }

  public function testRequest() {
    $builder = new RequestBuilder('https://github.com/ludovicm67/php-request');
    $request = new Request($builder);
    $this->assertFalse($request->isEmpty());
  }

  public function testEmptyRequestResponse() {
    $builder = new RequestBuilder('http://ok');
    $request = new Request($builder);
    $this->assertTrue($request->isEmpty());
  }

  public function testUnAllowedUrl() {
    $builder = new RequestBuilder('file://path/to/my/file.txt');
    $this->expectException(RequestException::class);
    $request = new Request($builder);
  }

}
