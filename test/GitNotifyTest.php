<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;

class GitNotifyTest extends PHPUnit_Framework_TestCase
{

  private function getPayload() {
    return file_get_contents(__DIR__.'/support/payload.json');
  }

  private function getConfig() {
    return (object) array('name' => 'myname');
  }

  private function getInstance() {
    return new GitNotifyCommit($this->getConfig(), $this->getPayload());
  }

  public function testConstruct() {
      $git_notify = $this->getInstance();
      $this->assertEquals($this->getConfig(), $git_notify->config);
      $this->assertObjectHasAttribute('commits', $git_notify->payload);
  }

  public function testBranches() {
    $git_notify = $this->getInstance();
    $this->assertEquals(array('master'), $git_notify->getBranches());
  }
}
