<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
} elseif (!isset($_POST['hex_hash']) || !isset($_SESSION[$_POST['hex_hash']])) {
  die("error: cannot locate transaction data");
}

$hex_hash = preg_replace("/[^a-z0-9]/i", '', $_POST['hex_hash']);

if ($_SESSION[$hex_hash]['sent'] === false) {
  $tx_id = $_SESSION[$rpc_client]->sendrawtransaction($_SESSION[$hex_hash]['hex']);
  rpc_error_check();
  if (empty($tx_id)) {
	echo "error: no txid returned, check transaction history";
  } else {
    $_SESSION[$hex_hash]['sent'] = true;
	echo "success:$tx_id";
  }
} else {
  die("error: transaction was already sent");
}
?>