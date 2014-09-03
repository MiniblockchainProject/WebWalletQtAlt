<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  echo "error: unauthorized access"; 
}

$balance_c1 = $_SESSION[$rpc_client]->getbalance($_SESSION['account_id']);
$balance_c2 = $_SESSION[$rpc_client]->getbalance($_SESSION['account_id'], $min_confs);
$pending = bcsub(remove_ep($balance_c1), remove_ep($balance_c2));
$balance = remove_ep($balance_c2);
$cc_disp = $curr_code;

if ($balance < 1) {
  $balance = bcmul($balance, '1000000');
  $cc_disp = '&#956;'.$cc_disp;
}

$bal_pts = explode('.', float_format($balance, 4));
echo "<span title='pending: $pending $curr_code'>".
$bal_pts[0].'.<small>'.$bal_pts[1]."</small> $cc_disp</span>";
?>
