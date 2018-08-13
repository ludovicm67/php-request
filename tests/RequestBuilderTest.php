<?php

namespace ludovicm67\Request\Tests;

use PHPUnit\Framework\TestCase;
use ludovicm67\Request\RequestBuilder;

class RequestBuilderTest extends TestCase {

  public function testDefaultOptions() {
    unset($_SERVER['HTTP_USER_AGENT']);
    $builder = new RequestBuilder();
    $options = $builder->getOptions();
    $this->assertEquals('', $builder->getUrl());
    $this->assertTrue($options['followlocation']);
    $this->assertTrue($options['autoreferer']);
    $this->assertFalse($options['post']);
    $this->assertFalse($options['ssl_verifypeer']);
    $this->assertEquals(120, $options['connecttimeout']);
    $this->assertEquals(120, $options['timeout']);
    $this->assertEquals(10, $options['maxredirs']);
    $this->assertEquals(0, $options['ssl_verifyhost']);
    $this->assertNull($options['encoding']);
    $this->assertNull($options['useragent']);
    $this->assertNull($options['postfields']);
    $this->assertFalse($builder->allowedUnsecure());

    // same, but with a definded user agent
    $_SERVER['HTTP_USER_AGENT'] = 'MyUserAgent';
    $builder = new RequestBuilder();
    $options = $builder->getOptions();
    $this->assertEquals('', $builder->getUrl());
    $this->assertTrue($options['followlocation']);
    $this->assertTrue($options['autoreferer']);
    $this->assertFalse($options['post']);
    $this->assertFalse($options['ssl_verifypeer']);
    $this->assertEquals(120, $options['connecttimeout']);
    $this->assertEquals(120, $options['timeout']);
    $this->assertEquals(10, $options['maxredirs']);
    $this->assertEquals(0, $options['ssl_verifyhost']);
    $this->assertNull($options['encoding']);
    $this->assertEquals('MyUserAgent', $options['useragent']);
    $this->assertNull($options['postfields']);
    $this->assertFalse($builder->allowedUnsecure());
    unset($_SERVER['HTTP_USER_AGENT']);
  }

  public function testDefaultCurlOptions() {
    unset($_SERVER['HTTP_USER_AGENT']);
    $builder = new RequestBuilder();
    $options = $builder->getCurlOptions();

    $this->assertContains(
      'cache-control: no-cache',
      $options[CURLOPT_HTTPHEADER]
    );
    $this->assertTrue($options[CURLOPT_RETURNTRANSFER]);
    $this->assertTrue($options[CURLOPT_FOLLOWLOCATION]);
    $this->assertTrue($options[CURLOPT_AUTOREFERER]);
    $this->assertFalse(isset($options[CURLOPT_POST]));
    $this->assertFalse(isset($options[CURLOPT_POSTFIELDS]));
    $this->assertFalse($options[CURLOPT_SSL_VERIFYPEER]);
    $this->assertEquals(120, $options[CURLOPT_CONNECTTIMEOUT]);
    $this->assertEquals(120, $options[CURLOPT_TIMEOUT]);
    $this->assertEquals(10, $options[CURLOPT_MAXREDIRS]);
    $this->assertEquals(0, $options[CURLOPT_SSL_VERIFYHOST]);
    $this->assertNull($options[CURLOPT_ENCODING]);
    $this->assertNull($options[CURLOPT_USERAGENT]);

    // same, but with a definded user agent
    $_SERVER['HTTP_USER_AGENT'] = 'MyUserAgent';
    $builder = new RequestBuilder();
    $options = $builder->getCurlOptions();

    $this->assertContains(
      'cache-control: no-cache',
      $options[CURLOPT_HTTPHEADER]
    );
    $this->assertTrue($options[CURLOPT_RETURNTRANSFER]);
    $this->assertTrue($options[CURLOPT_FOLLOWLOCATION]);
    $this->assertTrue($options[CURLOPT_AUTOREFERER]);
    $this->assertFalse(isset($options[CURLOPT_POST]));
    $this->assertFalse(isset($options[CURLOPT_POSTFIELDS]));
    $this->assertFalse($options[CURLOPT_SSL_VERIFYPEER]);
    $this->assertEquals(120, $options[CURLOPT_CONNECTTIMEOUT]);
    $this->assertEquals(120, $options[CURLOPT_TIMEOUT]);
    $this->assertEquals(10, $options[CURLOPT_MAXREDIRS]);
    $this->assertEquals(0, $options[CURLOPT_SSL_VERIFYHOST]);
    $this->assertNull($options[CURLOPT_ENCODING]);
    $this->assertEquals('MyUserAgent', $options[CURLOPT_USERAGENT]);
    unset($_SERVER['HTTP_USER_AGENT']);
  }

  public function testParameterUrl() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setOptions([])
        ->setUrl($url)
    );

    $this->assertEquals($url, $builder_array->getUrl());
    $this->assertEquals($url, $builder_chained->getUrl());
  }

  public function testOptionFollowlocationSetToTrue() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url, [
      'followlocation' => true
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setFollowLocation(true)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertTrue($options_array['followlocation']);
    $this->assertTrue($options_chained['followlocation']);
    $this->assertTrue($curl_options_array[CURLOPT_FOLLOWLOCATION]);
    $this->assertTrue($curl_options_chained[CURLOPT_FOLLOWLOCATION]);
  }

  public function testOptionFollowlocationSetToFalse() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url, [
      'followlocation' => false
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setFollowLocation(false)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertFalse($options_array['followlocation']);
    $this->assertFalse($options_chained['followlocation']);
    $this->assertFalse($curl_options_array[CURLOPT_FOLLOWLOCATION]);
    $this->assertFalse($curl_options_chained[CURLOPT_FOLLOWLOCATION]);
  }

  public function testOptionEncoding() {
    $url = 'http://example.com';
    $encoding = 'utf-8';

    $builder_array = new RequestBuilder($url, [
      'encoding' => $encoding
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setEncoding($encoding)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals($encoding, $options_array['encoding']);
    $this->assertEquals($encoding, $options_chained['encoding']);
    $this->assertEquals($encoding, $curl_options_array[CURLOPT_ENCODING]);
    $this->assertEquals($encoding, $curl_options_chained[CURLOPT_ENCODING]);
  }

  public function testOptionUseragent() {
    $url = 'http://example.com';
    $user_agent = 'Agent Tester';

    $builder_array = new RequestBuilder($url, [
      'useragent' => $user_agent
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setUserAgent($user_agent)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals($user_agent, $options_array['useragent']);
    $this->assertEquals($user_agent, $options_chained['useragent']);
    $this->assertEquals($user_agent, $curl_options_array[CURLOPT_USERAGENT]);
    $this->assertEquals($user_agent, $curl_options_chained[CURLOPT_USERAGENT]);
  }

  public function testOptionAutorefererSetToTrue() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url, [
      'autoreferer' => true
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setAutoReferer(true)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertTrue($options_array['autoreferer']);
    $this->assertTrue($options_chained['autoreferer']);
    $this->assertTrue($curl_options_array[CURLOPT_AUTOREFERER]);
    $this->assertTrue($curl_options_chained[CURLOPT_AUTOREFERER]);
  }

  public function testOptionAutorefererSetToFalse() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url, [
      'autoreferer' => false
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setAutoReferer(false)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertFalse($options_array['autoreferer']);
    $this->assertFalse($options_chained['autoreferer']);
    $this->assertFalse($curl_options_array[CURLOPT_AUTOREFERER]);
    $this->assertFalse($curl_options_chained[CURLOPT_AUTOREFERER]);
  }

  public function testOptionConnectTimeout() {
    $url = 'http://example.com';
    $time = 42;

    $builder_array = new RequestBuilder($url, [
      'connecttimeout' => $time
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setConnectTimeout($time)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals($time, $options_array['connecttimeout']);
    $this->assertEquals($time, $options_chained['connecttimeout']);
    $this->assertEquals($time, $curl_options_array[CURLOPT_CONNECTTIMEOUT]);
    $this->assertEquals($time, $curl_options_chained[CURLOPT_CONNECTTIMEOUT]);
  }

  public function testOptionTimeout() {
    $url = 'http://example.com';
    $time = 42;

    $builder_array = new RequestBuilder($url, [
      'timeout' => $time
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setTimeout($time)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals($time, $options_array['timeout']);
    $this->assertEquals($time, $options_chained['timeout']);
    $this->assertEquals($time, $curl_options_array[CURLOPT_TIMEOUT]);
    $this->assertEquals($time, $curl_options_chained[CURLOPT_TIMEOUT]);
  }

  public function testOptionMaxredirs() {
    $url = 'http://example.com';
    $nb_redirs = 42;

    $builder_array = new RequestBuilder($url, [
      'maxredirs' => $nb_redirs
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setMaxRedirs($nb_redirs)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals($nb_redirs, $options_array['maxredirs']);
    $this->assertEquals($nb_redirs, $options_chained['maxredirs']);
    $this->assertEquals($nb_redirs, $curl_options_array[CURLOPT_MAXREDIRS]);
    $this->assertEquals($nb_redirs, $curl_options_chained[CURLOPT_MAXREDIRS]);
  }

  public function testOptionsSSL() {
    $url = 'http://example.com';

    $builder_array = new RequestBuilder($url, [
      'ssl_verifyhost' => 2,
      'ssl_verifypeer' => true
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setVerifySSL()
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertEquals(2, $options_array['ssl_verifyhost']);
    $this->assertEquals(2, $options_chained['ssl_verifyhost']);
    $this->assertEquals(2, $curl_options_array[CURLOPT_SSL_VERIFYHOST]);
    $this->assertEquals(2, $curl_options_chained[CURLOPT_SSL_VERIFYHOST]);
    $this->assertTrue($options_array['ssl_verifypeer']);
    $this->assertTrue($options_chained['ssl_verifypeer']);
    $this->assertTrue($curl_options_array[CURLOPT_SSL_VERIFYPEER]);
    $this->assertTrue($curl_options_chained[CURLOPT_SSL_VERIFYPEER]);
  }

  public function testOptionsPost() {
    $url  = 'http://example.com';
    $data = 'Test';

    $builder_array = new RequestBuilder($url, [
      'post' => true,
      'postfields' => $data
    ]);
    $builder_chained = new RequestBuilder();

    $this->assertInstanceOf(
      RequestBuilder::class,
      $builder_chained
        ->setUrl($url)
        ->setPost($data)
    );

    $options_array = $builder_array->getOptions();
    $options_chained = $builder_chained->getOptions();
    $curl_options_array = $builder_array->getCurlOptions();
    $curl_options_chained = $builder_chained->getCurlOptions();

    $this->assertTrue($options_array['post']);
    $this->assertTrue($options_chained['post']);
    $this->assertTrue($curl_options_array[CURLOPT_POST]);
    $this->assertTrue($curl_options_chained[CURLOPT_POST]);
    $this->assertEquals($data, $options_array['postfields']);
    $this->assertEquals($data, $options_chained['postfields']);
    $this->assertEquals($data, $curl_options_array[CURLOPT_POSTFIELDS]);
    $this->assertEquals($data, $curl_options_chained[CURLOPT_POSTFIELDS]);
  }

  public function testAllowUnsecure() {
    $this->assertFalse(
      (new RequestBuilder('http://example.com'))->allowedUnsecure()
    );
    $this->assertTrue(
      ((new RequestBuilder('http://example.com'))->allowUnsecure())
        ->allowedUnsecure()
    );
  }
}
