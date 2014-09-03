<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (!empty($_POST['address'])) {

  $address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
  rpc_error_check();

  if (!in_array($address, $addresses)) {
    die('error: private key for that address is unknown');
  }

  $result = $_SESSION[$rpc_client]->dumpprivkey($address);
  rpc_error_check();

} elseif (isset($_POST['dump'])) {

  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
  rpc_error_check();

  $wallet_json = "{ \"keys\" : [\n\t";
  foreach ($addresses as $key => $value) {
    $priv_key = $_SESSION[$rpc_client]->dumpprivkey($value);
    $wallet_json .= " {\n\t\"addr\" : \"$value\",\n\t\"priv\" : \"$priv_key\"\n\t},";
  }
  $result = base64_encode(rtrim($wallet_json, ',')."\n]}");

} else {
  die("error: one or more form fields left empty");
}

if (!empty($result)) {
  echo "success:$result";
} else {
  echo "error: there was a problem exporting your keys";
}
?>