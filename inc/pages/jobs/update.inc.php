<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (empty($_POST['old_pass'])) {
  die('error: your current password was not supplied');
}

$acc_dir = "./../../../db/.".$_SESSION['account_id'];
$account = @file_get_contents($acc_dir);

if ($account !== false) {
  $account = json_decode($account, true);
  if (!empty($account)) {
	$pass_hash = hash('sha256', $_POST['old_pass']);
	if ($pass_hash !== $account['pass_hash']) {
	  die('error: current password does not match the one supplied');
	}
  } else {
    die("error: unable to edit account, contact the web master");
  }
} else {
  die("error: not logged into a valid account");
}

if (!empty($_POST['new_email']) && !empty($_POST['rep_email'])) {

  if (is_injected($_POST['new_email']) || !check_email_dns($_POST['new_email'])) {
    die("error: invalid email address detected.");
  } elseif ($_POST['new_email'] !== $_POST['rep_email']) {
    die("error: the email addresses do not match.");
  }
  
  $acc_json = json_encode(array(
    'account_id' => $account['account_id'],
    'email_add' => urlencode($_POST['new_email']),
    'pass_hash' => $account['pass_hash'],
    'ip_address' => get_remote_ip(),
    'fail_count' => $account['fail_count'],
    'lock_state' => $account['lock_state'],
    'lock_time' => $account['lock_time']
  ));
  
  if (file_put_contents($acc_dir, $acc_json)) {
    chmod($acc_dir, 0600);
	$_SESSION['email_add'] = $_POST['new_email'];
    echo "success:".$_POST['new_email'];
  } else {
    echo "error: unable to update account, try again later";
  }
  
} elseif (!empty($_POST['new_pass']) && !empty($_POST['rep_pass'])) {

  if ($_POST['new_pass'] !== $_POST['rep_pass']) {
    die('error: the two passwords do not match');
  }
  
  $pass_str = check_pass_strength($_POST['new_pass']);
  if ($pass_str !== true) { die($pass_str); }
  
  $acc_json = json_encode(array(
    'account_id' => $account['account_id'],
    'email_add' => $account['email_add'],
    'pass_hash' => hash('sha256', $_POST['new_pass']),
    'ip_address' => get_remote_ip(),
    'fail_count' => $account['fail_count'],
    'lock_state' => $account['lock_state'],
    'lock_time' => $account['lock_time']
  ));
  
  if (file_put_contents($acc_dir, $acc_json)) {
    chmod($acc_dir, 0600);
    echo "success";
  } else {
    echo "error: unable to update account, try again later";
  }

} else {
  die('error: one or more form fields left empty');;
}
?>