<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

function txtr_type($fee, $amount, $cat, $curr) {
  if ($fee === $amount) {
	$cat = 'set limit';
	$amount = '-';
	echo '<tr class="warning tr_link" ';
  } else {	
	$amount = clean_value($amount);
	if (bccomp($amount, '0') == 0) {
	  $cat = 'set limit';
	  $amount = '-';
	  echo '<tr class="warning tr_link" ';
	} elseif ($cat == 'send') {
	  $amount .= ' '.$curr;
	  echo '<tr class="error tr_link" ';
	} else {
	  $amount .= ' '.$curr;
	  echo '<tr class="success tr_link" ';
	}
  }
  return array('amount'=>$amount,'category'=>$cat);
}

if (isset($_GET['history'])) {

  $page = (int) $_GET['history'];
  $_SESSION[$rpc_client]->listtransactions($_SESSION['account_id'], 15, ($page-1)*15);
  $transactions = array_reverse($_SESSION[$rpc_client]->response['result']);
  rpc_error_check();
  
  if (count($transactions) > 0) {
    echo "<table class='table table-striped table-hover'>\n<tr><th>Address</th>".
	"<th>Type</th><th>Confirmations</th><th>Amount</th><th>Received</th></tr>";
    foreach ($transactions as $key => $value) {
	  $tx_fee = isset($value['fee']) ? $value['fee'] : '0';
	  $tx_vars = txtr_type($tx_fee, $value['amount'], $value['category'], $curr_code);
      echo 'onclick="redirect(\'./?page=search&tx='.$value['txid'].'\')">';
      echo '<td>'.$value['address'].'</td>';
      echo '<td>'.$tx_vars['category'].'</td>';
      echo '<td>'.$value['confirmations'].'</td>';
      echo '<td>'.$tx_vars['amount'].'</td>';
      echo '<td>'.date('Y-m-d h:i:s A', $value['timereceived']).'</td>';
      echo '</tr>';
    }
	echo '</table>';
  } else {
    $ftran = $_SESSION[$rpc_client]->listtransactions($_SESSION['account_id'], 1);
	if (count($ftran) > 0) {
      echo "<p>There are no transactions past this point in your history.</p>";
	} else {
      echo "<p>There are no transactions associated with this account.</p>";
	}
  }

} elseif (isset($_GET['recent'])) {

  $_SESSION[$rpc_client]->listtransactions($_SESSION['account_id'], 5);
  $transactions = array_reverse($_SESSION[$rpc_client]->response['result']);
  rpc_error_check();
  
  if (count($transactions) > 0) {
    echo "<table class='table table-striped table-hover'>\n<tr><th>Address</th>".
    "<th>Type</th><th>Confirmations</th><th>Amount</th><th>Received</th></tr>";
    foreach ($transactions as $key => $value) {
	  $tx_fee = isset($value['fee']) ? $value['fee'] : '0';
	  $tx_vars = txtr_type($tx_fee, $value['amount'], $value['category'], $curr_code);
      echo 'onclick="redirect(\'./?page=search&tx='.$value['txid'].'\')">';
      echo '<td>'.$value['address'].'</td>';
      echo '<td>'.$tx_vars['category'].'</td>';
      echo '<td>'.$value['confirmations'].'</td>';
      echo '<td>'.$tx_vars['amount'].'</td>';
      echo '<td>'.date('Y-m-d h:i:s A', $value['timereceived']).'</td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo "<p>There are no transactions associated with this account.</p>";
  }

} else {
  die("error: no command was specified");
}
?>
