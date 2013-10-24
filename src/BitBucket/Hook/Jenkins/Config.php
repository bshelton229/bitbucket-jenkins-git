<?php
namespace BitBucket\Hook\Jenkins;

class Config {
  private $config_file_data = Array();

  public function __construct($config_file=FALSE) {
    if ($config_file && file_exists($config_file)) {
      $this->config_file_data = json_decode(file_get_contents($config_file), TRUE);
    }
  }

  public function get($key) {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    }
    elseif (isset($this->config_file_data[$key])) {
      return $this->config_file_data[$key];
    }
    elseif (getenv(strtoupper($key))) {
      return getenv(strtoupper($key));
    }
    else {
      return FALSE;
    }
  }
}
