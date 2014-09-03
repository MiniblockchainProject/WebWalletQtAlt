<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (!empty($_POST['priv_key'])) {

  $priv_key = preg_replace("/[^a-z0-9]/i", '', $_POST['priv_key']);
  $result = $_SESSION[$rpc_client]->importprivkey($priv_key, $_SESSION['account_id']);
  rpc_error_check();

} elseif (!empty($_POST['wallet'])) {

  $wallet_json = json_decode($_POST['wallet'], true);
  $fail_count = $succ_count = 0;
  foreach ($wallet_json['keys'] as $key => $value) {
    $priv_key = preg_replace("/[^a-z0-9]/i", '', $value['priv']);
    $result = $_SESSION[$rpc_client]->importprivkey($priv_key, $_SESSION['account_id']);
	if ($result === true) { $succ_count++; } else { $fail_count++; }
  }
  if ($succ_count < 1) {
    die("error: could not import any of the private keys");
  } else {
    $total_keys = $succ_count + $fail_count;
    die("success: $succ_count of $total_keys private keys imported into wallet");
  }

} else {
  die("error: one or more form fields left empty");
}

if ($result === true) {
  echo "success";
} else {
  echo "error: there was a problem importing your key(s)";
}
?>