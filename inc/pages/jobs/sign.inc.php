<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
} elseif (!isset($_POST['address'])) {
  die("error: the address field was left empty");
} elseif (!isset($_POST['message'])) {
  die("error: the message field was left empty");
}

$address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
$addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
rpc_error_check();

if (!in_array($address, $addresses)) {
  die('error: private key for that address is unknown');
}

$sig = $_SESSION[$rpc_client]->signmessage($address, $_POST['message']);
rpc_error_check();

if (!empty($sig)) {
  echo "success:$sig";
} else {
  echo "error: there was a problem signing the message";
}
?>