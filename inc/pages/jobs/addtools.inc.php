<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (isset($_GET['newadd'])) {

  $address = $_SESSION[$rpc_client]->getnewaddress($_SESSION['account_id']);
  rpc_error_check();

  if (!empty($address)) {
    echo "success:$address";
  } else {
    echo "error: unable to generate new address";
  }
  
} elseif (isset($_POST['moveadd'])) {

  list($move_mode, $address) = explode(':', $_POST['moveadd']);
  
  $address = preg_replace("/[^a-z0-9]/i", '', $address);
  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
  rpc_error_check();

  if (!in_array($address, $addresses)) {
    die('error: private key for that address is unknown');
  }

  $acc_to = ($move_mode == 1) ? $_SESSION['account_id'] : $_SESSION['account_id'].'-archive';
  $acc_fm = ($move_mode == 2) ? $_SESSION['account_id'] : $_SESSION['account_id'].'-archive';
  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($acc_fm);
  
  if (count($addresses) > 1) {
    $_SESSION[$rpc_client]->setaccount($address, $acc_to, 1);
  } else {
    $_SESSION[$rpc_client]->setaccount($address, $acc_to);
  }

  rpc_error_check();
  echo "success";
  
} else {
  die("error: no command was specified");
}
?>