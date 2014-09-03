<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

if (!post_get_check()) {
  die('error: no post data was detected');
} elseif (!empty($_POST['acc']) && !empty($_POST['pass'])) {
  $acc_id = preg_replace("/[^a-z0-9-]/i", '', $_POST['acc']);
  $acc_dir = "./../../../db/.$acc_id";
  $account = @file_get_contents($acc_dir);
  if ($account !== false) {
    $account = json_decode($account, true);
    $pass_hash = hash('sha256', $_POST['pass']);
    if (!empty($account)) {
	  $time_diff = get_time_difference(date('Y-m-d H:i:s', $account['lock_time']), date('Y-m-d H:i:s'));
	  if ($account['lock_state'] == 1) {
		$time_left = $lock_timer - $time_diff['minutes'];
		if ($time_left <= 0) {
		  $account['lock_state'] = 0;
		  $account['fail_count'] = 0;
		}
	  }
	  if ($account['lock_state'] == 0) {
	    if ($pass_hash == $account['pass_hash']) {
	      session_regenerate_id();
		  session_start();
	      $_SESSION['account_id'] = $acc_id;
	      $_SESSION['email_add'] = urldecode($account['email_add']);
	      $_SESSION['ip_address'] = get_remote_ip();
	      $_SESSION['lock_ip'] = (bool) $_POST['lock'];
		  $account['ip_address'] = $_SESSION['ip_address'];
	      file_put_contents($acc_dir, json_encode($account));
	      chmod($acc_dir, 0600);
		  echo "success:$acc_id";
        } else {
          echo "error: incorrect password!";
		  if ($time_diff['minutes'] > $lock_timer) {
		    $account['fail_count'] = 1;
		  } else {
		    $account['fail_count']++;
		  }
		  if ($account['fail_count'] > $fail_limit) {
		    $account['lock_state'] = 1;
		    echo " account temporarily locked, please try again in $lock_timer minutes";
		  }
		  $account['lock_time'] = time();
	      file_put_contents($acc_dir, json_encode($account));
	      chmod($acc_dir, 0600);
        }
	  } else {
        echo "error: account temporarily locked, please try again in $time_left minutes";
	  }
	} else {
      echo "error: unable to open account, contact the web master";
	}
  } else {
    echo "error: the account specified does not exist";
  }
}
?>