<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;

class GitNotifyTest extends PHPUnit_Framework_TestCase
{

  private $jenkins_url = 'https://ci.jenkins-ci.org/';

  private function getPayload($name='default') {
    return file_get_contents(__DIR__.'/support/'.$name.'_payload.json');
  }

  private function getInstance() {
    return new GitNotifyCommit($this->jenkins_url, $this->getPayload());
  }

  public function testConstruct() {
    $git_notify = $this->getInstance();
    $this->assertEquals('https://ci.jenkins-ci.org', $git_notify->jenkins_url);
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
      'https://ci.jenkins-ci.org/git/notifyCommit/?url=git%40bitbucket.org%3Abshelton229%2Ftest-service-hook.git&branches=master',
      $git_notify->getTriggerUrl()
    );
  }
}
