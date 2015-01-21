<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}
?>
<div class="span4">
  <?php
  $balance_c1 = $_SESSION[$rpc_client]->getbalance($_SESSION['account_id']);
  $balance_c2 = $_SESSION[$rpc_client]->getbalance($_SESSION['account_id'], $min_confs);
  $pending_bal = bcsub(remove_ep($balance_c1), remove_ep($balance_c2));
  ?>
  <h3>Wallet</h3>
  <b>Total Balance:</b> <?php echo remove_ep($balance_c1).' '.$curr_code; ?><br />
  <b>Available Balance:</b> <?php echo remove_ep($balance_c2).' '.$curr_code; ?><br />
  <b>Pending Balance:</b> <?php echo $pending_bal.' '.$curr_code; ?><br />
</div>
<div class="span4">
  <?php
  $rpc_result = $_SESSION[$rpc_client]->listbalances(1, array($cb_address));
  $cb_balance = remove_ep($rpc_result[0]['balance']);
  $frac_reman = bcdiv($cb_balance, $total_coin);
  $block_rwrd = bcmul($first_reward, $frac_reman);
  $tx_stats = $_SESSION[$rpc_client]->gettxoutsetinfo();
  $coin_spply = remove_ep($tx_stats['total_amount']);
  ?>
  <h3>Economy</h3>
  <b>Coin supply:</b> <?php echo float_format($coin_spply, 4).' '.$curr_code; ?><br />
  <b>Unmined coins:</b> <?php echo float_format($cb_balance, 0).' '.$curr_code; ?><br />
  <b>Block Reward:</b> <?php echo $block_rwrd.' '.$curr_code; ?>
</div>
<div class="span4">
  <?php
  $rpc_result = $_SESSION[$rpc_client]->getmininginfo();
  ?>
  <h3>Network</h3>
  <b>Block Count:</b> <?php echo $rpc_result['blocks']; ?><br />
  <b>Difficulty:</b> <?php echo float_format($rpc_result['difficulty'], 4); ?><br />
  <b>Hash Rate:</b> <?php echo float_format(bcdiv($rpc_result['networkhashps'], '1000000000'), 4).' GH/s'; ?>
</div>
