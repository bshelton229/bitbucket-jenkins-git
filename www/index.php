<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;

$jenkins_url = FALSE;

// NOTE: Needs to be moved into a library so it can be tested
if (isset($_GET['jenkins_url'])) {
  $jenkins_url = $_GET['jenkins_url'];
}
elseif (getenv('JENKINS_URL')) {
  $jenkins_url = getenv('JENKINS_URL');
}
elseif (file_exists(__DIR__.'/../config.json')) {
  $config = json_decode(file_get_contents(__DIR__.'/../config.json'));
  if (isset($config->jenkins_url)) {
    $jenkins_url = $config->jenkins_url;
  }
}

print $jenkins_url;

if (isset($_GET['jenkins_url']) && isset($_POST['payload'])) {
  $git_notify = new GitNotifyCommit($_GET['jenkins_url'], $_POST['payload']);
  // Trigger a url hit
  $git_notify->trigger();
}
else {
  print 'Bitbucket Jenkins Git commitHook';
}
