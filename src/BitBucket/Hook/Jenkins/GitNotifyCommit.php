<?php
namespace BitBucket\Hook\Jenkins;

class GitNotifyCommit {
  /**
   * Load the class
   *
   * @param $jenkins_url string
   *  The URL jenkins is located at
   * @param $payload
   *  A JSON string from BitBucket
   */
  public function __construct($jenkins_url, $payload) {
    $this->jenkins_url = preg_replace('#(/+)$#', '', $jenkins_url);
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
   * Get the repository URL from the payload
   * NOTE: This is too simple. We eventually may need
   * to deal with https:// schemes, and check private/public
   * for a better URL
   *
   * @return string
   *  Return the repository url
   */
  public function getRepoUrl() {
    return sprintf(
      'git@bitbucket.org:%s/%s.git',
      $this->payload->repository->owner,
      $this->payload->repository->slug
    );
  }

  /**
   * Get the trigger URL
   *
   * @return string
   *  The trigger url to hit
   */
  public function getTriggerUrl() {
    $arg_string = http_build_query(array(
      'url' => $this->getRepoUrl(),
      'branches' => implode(',',$this->getBranches())
    ));
    return $this->jenkins_url . '/git/notifyCommit/?' . $arg_string;
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
    return file_get_contents($this->getTriggerUrl());
  }
}
