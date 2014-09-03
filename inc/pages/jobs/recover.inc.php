<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

if (isset($_POST['id']) && isset($_POST['code'])) {

  if (!post_get_check()) {
    die('error: no post data was detected');
  } elseif (empty($_POST['pass']) || empty($_POST['passr'])) {
    die('error: one or more passwords left empty');
  } elseif ($_POST['pass'] !== $_POST['passr']) {
    die('error: the two passwords do not match');
  }

  $pass_str = check_pass_strength($_POST['pass']);
  if ($pass_str !== true) { die($pass_str); }

  $acc_id = preg_replace("/[^a-z0-9-]/i", '', $_POST['id']);
  $acc_dir = "./../../../db/.$acc_id";
  $account = @file_get_contents($acc_dir);

  if ($account !== false) {
    $account = json_decode($account, true);
    if (!empty($account)) {
	  if (hash('sha256', $account['pass_hash']) === $_POST['code']) {
		$acc_json = json_encode(array(
		  'account_id' => $account['account_id'],
		  'email_add' => $account['email_add'],
		  'pass_hash' => hash('sha256', $_POST['pass']),
		  'ip_address' => get_remote_ip(),
		  'fail_count' => 0,
		  'lock_state' => 0,
		  'lock_time' => time()
		));
		if (file_put_contents($acc_dir, $acc_json)) {
		  chmod($acc_dir, 0600);
		  echo "success:$acc_id";
		} else {
		  echo "error: unable to update account, try again later";
		}
	  } else {
	    echo "error: the code specified is invalid";
	  }
	} else {
	  echo "error: unable to open account, contact the web master";
	}
  } else {
    echo "error: the account specified does not exist";
  }
  
} else {

  if (strpos($_POST['email'], '@') === false) {
    $acc_id = preg_replace("/[^a-z0-9-]/i", '', $_POST['email']);	
	$use_id = true;
  } else {  
    if (empty($_POST['email'])) {
      die("error: email field is empty.");
    } elseif (is_injected($_POST['email']) || !check_email_dns($_POST['email'])) {
      die("error: invalid email address detected");
    }
    $acc_id = hash('ripemd128', $rand_str.$_POST['email']);
    $acc_id = implode('-', str_split($acc_id, 8));
  }
  
  $acc_dir = "./../../../db/.$acc_id";
  $account = @file_get_contents($acc_dir);

  if ($account !== false) {
    $account = json_decode($account, true);
    if (!empty($account)) {
	  $to = urldecode($account['email_add']);
	  $subject = 'account recovery';
	  $message = 'Account ID: '.$account['account_id']."\r\n\r\n";
	  $message .= "Visit the link below to reset your password:\r\n";
	  $message .= $base_url.'?page=reset&id='.$account['account_id'];
	  $message .= '&code='.hash('sha256', $account['pass_hash']);
	  $headers = "From: noreply@".$_SERVER['SERVER_NAME']." \r\n";
	  $headers .= "Reply-To: noreply@".$_SERVER['SERVER_NAME']." \r\n";
	  $headers .= 'X-Mailer: PHP/'.phpversion();
	  if (mail($to, $subject, $message, $headers)) {
        echo "success:$to";
	  } else {
        echo "error: could not send email, please try again later";
	  }
    } else {
      echo "error: unable to open account, contact the web master";
    }
  } else {
    if (isset($use_id)) {
      echo "error: no account with the ID specified could be found";
	} else {
      echo "error: no account registered with that email address";
	}
  }
}
?>