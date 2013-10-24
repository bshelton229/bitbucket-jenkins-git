<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;
use BitBucket\Hook\Jenkins\Config;

class ConfigTest extends PHPUnit_Framework_TestCase {
  public function testGetFromParam() {
    $_GET['jenkins_url'] = 'http://mytest.com';
    $config = new Config();
    print_r($config->get('bryan'));
    $this->assertEquals('http://mytest.com', $config->get('jenkins_url'));
  }

  public function testGetFromConfigFile() {
    $config = new Config(__DIR__.'/support/test_config.json');
    $this->assertEquals('http://mytest.com', $config->get('jenkins_url'));
  }

  public function testGetFromEnv() {
    putenv('JENKINS_URL=http://mytest.com');
    $config = new Config();
    $this->assertEquals('http://mytest.com', $config->get('jenkins_url'));
  }

  public function testConfigPreference() {
    putenv('JENKINS_URL=http://env.com');
    $_GET['jenkins_url'] = 'http://param.com';
    $config = new Config(__DIR__.'/support/test_config.json');
    $this->assertEquals('http://param.com', $config->get('jenkins_url'));
  }

  public function testMissingConfigFile() {
    putenv('JENKINS_URL=http://env.com');
    $config = new Config(__DIR__.'/missing.json');
    $this->assertEquals('http://env.com', $config->get('jenkins_url'));
  }
}
