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
} elseif (!isset($_POST['signature'])) {
  die("error: the signature field was left empty");
}

$address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
$result = $_SESSION[$rpc_client]->verifymessage($address, $_POST['signature'], $_POST['message']);
rpc_error_check();

if ($result === true) {
  echo "success";
} elseif ($result === false) {
  echo "error: signature is invalid";
} else {
  echo "error: there was a problem verifying the message";
}
?>