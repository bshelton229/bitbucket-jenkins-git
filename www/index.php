<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;

// Load the config JSON file
$config = json_decode(file_get_contents(__DIR__.'/../config.json'));

// Parse the payload if we have one
$payload = isset($_POST['payload']) ? $_POST['payload'] : false;

if ($payload && $config && isset($config->service_url)) {
  $git_notify = new GitNotifyCommit($config, FALSE);
  // Trigger a url hit
  $git_notify->trigger();
}
else {
  print 'Bitbucket Jenkins Git commitHook';
}
