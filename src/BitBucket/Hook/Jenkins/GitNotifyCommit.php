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
   * Get the repository URL from the payload
   * NOTE: This is too simple. We eventually may need
   * to deal with https:// schemes, and check private/public
   * for a better URL
   *
   * @return string
   *  Return the repository url
   */
  public function getRepoUrl() {
    return sprintf('git@bitbucket.org:%s/%s.git', $this->payload->repository->owner, $this->payload->repository->slug);
  }

  /**
   * Return the service URL stripping any trailing slashes
   *
   * @return string
   *  The service url as a string
   */
  public function getServiceUrl() {
    return preg_replace('#(/)$#', '', $this->config->service_url);
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
    return $this->getServiceUrl() . '/git/commitHook/?' . $arg_string;
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
