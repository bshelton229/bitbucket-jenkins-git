<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;

class GitNotifyTest extends PHPUnit_Framework_TestCase
{

  private function getPayload() {
    return file_get_contents(__DIR__.'/support/payload.json');
  }

  private function getConfig() {
    return json_decode(file_get_contents(__DIR__.'/support/config.json'));
  }

  private function getInstance() {
    return new GitNotifyCommit($this->getConfig(), $this->getPayload());
  }

  public function testConstruct() {
    $git_notify = $this->getInstance();
    $this->assertEquals('http://ci.jenkins-ci.org', $git_notify->config->service_url);
    $this->assertObjectHasAttribute('commits', $git_notify->payload);
  }

  public function testBranches() {
    $git_notify = $this->getInstance();
    $this->assertEquals(array('master'), $git_notify->getBranches());
  }

  public function testServiceUrl() {
    $git_notify = $this->getInstance();
    $this->assertEquals('git@bitbucket.org:bshelton229/test-service-hook.git', $git_notify->getRepoUrl());
  }

  public function testTriggerUrl() {
    $git_notify = $this->getInstance();
    $this->assertEquals(
      'http://ci.jenkins-ci.org/git/commitHook/?url=git%40bitbucket.org%3Abshelton229%2Ftest-service-hook.git&branches=master',
      $git_notify->getTriggerUrl()
    );
  }
}
