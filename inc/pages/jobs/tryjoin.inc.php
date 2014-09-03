<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

if (!post_get_check()) {
  die('error: no post data was detected');
} elseif (empty($_POST['email']) || empty($_POST['emailr']) || empty($_POST['pass'])) {
  die("error: one or more form fields are empty");
} elseif (is_injected($_POST['email']) || !check_email_dns($_POST['email'])) {
  die("error: invalid email address detected");
} elseif ($_POST['email'] !== $_POST['emailr']) {
  die("error: the email addresses do not match");
} elseif ($_POST['pass'] !== $_POST['passr']) {
  die("error: the passwords do not match");
}

$pass_str = check_pass_strength($_POST['pass']);
if ($pass_str !== true) { die($pass_str); }

$acc_id = hash('ripemd128', $rand_str.$_POST['email']);
$acc_id = implode('-', str_split($acc_id, 8));
$acc_dir = "./../../../db/.$acc_id";

if (file_exists($acc_dir)) {
  die("error: the email specified already exists");
}

$acc_json = json_encode(array(
  'account_id' => $acc_id,
  'email_add' => urlencode($_POST['email']),
  'pass_hash' => hash('sha256', $_POST['pass']),
  'ip_address' => get_remote_ip(),
  'fail_count' => 0,
  'lock_state' => 0,
  'lock_time' => time()
));

$rpc_obj = new RPCclient($rpc_user, $rpc_pass);
$address = $rpc_obj->getaccountaddress($acc_id);
rpc_error_check();

if (!empty($address) && file_put_contents($acc_dir, $acc_json)) {
  chmod($acc_dir, 0600);
  echo "success:$acc_id";
} else {
  echo "error: unable to create account, try again later";
}
?>