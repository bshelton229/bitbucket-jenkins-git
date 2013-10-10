<?php
namespace BitBucket\Hook\Jenkins;

class GitNotifyCommit {
  /**
   * Load the class
   *
   * @param $config array()
   *  An array of configuration options
   * @param $payload
   *  A JSON string from BitBucket
   */
  public function __construct($config, $payload) {
    $this->config = $config;
    $this->payload = FALSE;
    if ($payload) {
      $this->payload = json_decode($payload);
    }
  }

  /**
   * Get an array of branches represented in this push
   *
   * @return array<string>
   *  An array of branches
   */
  public function getBranches() {
    $branches = array();
    foreach ($this->payload->commits as $commit) {
      $branches[] = $commit->branch;
    }
    return array_unique($branches);
  }

  /**
   * Trigger the actual request to the
   * Jenkins git notify service given
   * the supplied payload and config
   *
   * @return string
   *  Should return what is returned from the service
   */
  public function trigger() {
    return false;
  }
}
