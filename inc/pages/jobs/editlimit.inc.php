<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid') {
  die("error: unauthorized access");
}

if (isset($_POST['limit'])) {

  $address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
  rpc_error_check();

  if (!in_array($address, $addresses)) {
    die('error: private key for that address is unknown');
  }

  $address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
  $ainfo = $_SESSION[$rpc_client]->listbalances(1, array($address));
  rpc_error_check();
  
  $old_limit = remove_ep($ainfo[0]['limit']);
  $blk_cnt = ($_POST['limit'] < $old_limit) ? '1 block' : '100 blocks';
  
  $new_val = float_format($_POST['limit']).'ep';
  $tx_id = $_SESSION[$rpc_client]->setlimit($new_val, $address);
  rpc_error_check();

  if (!empty($tx_id)) {
    echo "success:$tx_id:$blk_cnt";
  } else {
    echo "error: unable to update withdrawal limit";
  }
  
} elseif (isset($_POST['info'])) {

  list($new_val, $address) = explode(':', $_POST['info']);
  $new_val = float_format($new_val);
  $address = preg_replace("/[^a-z0-9]/i", '', $address);
  
  echo "<p>Confirm the details below are correct:".
  "</p><p><b>Address:</b> $address</p><p><b>New ".
  "withdrawal limit:</b> $new_val $curr_code</p>";

} else {
  die('error: one or more form fields left empty');
}
?>