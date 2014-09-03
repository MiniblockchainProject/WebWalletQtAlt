<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
} elseif (!isset($_POST['addlist'])) {
  die("error: the address list was left empty");
} elseif (!isset($_POST['sigcount'])) {
  die("error: the signature count was left empty");
} elseif ($_POST['sigcount'] < 1) {
  die("error: the signature count is too small");
}

$addresses = explode("\n", $_POST['addlist']);
foreach ($addresses as $key => $value) {
  $addresses[$key] = trim($value);
}
if (count($addresses) < 2) {
  die("error: at least 2 addresses are required");
}

$address = $_SESSION[$rpc_client]->createmultisig((int)$_POST['sigcount'], $addresses);
rpc_error_check();

if (!empty($address)) {
  echo "success:".$address['address'];
} else {
  echo "error: there was a problem generating the address";
}
?>