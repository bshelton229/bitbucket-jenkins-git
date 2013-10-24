<?php
require_once __DIR__.'/../vendor/autoload.php';
use BitBucket\Hook\Jenkins\GitNotifyCommit;
use BitBucket\Hook\Jenkins\Config;

$config = new Config(__DIR__.'/../config.json');
$jenkins_url = $config->get('jenkins_url');

if ($jenkins_url && isset($_POST['payload'])) {
  $git_notify = new GitNotifyCommit($jenkins_url, $_POST['payload']);
  // Trigger a url hit
  $git_notify->trigger();
}
else {
  print 'Bitbucket Jenkins Git commitHook';
}
