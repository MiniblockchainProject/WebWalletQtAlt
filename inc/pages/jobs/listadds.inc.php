<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (isset($_GET['rows'])) {

  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
  $balances = $_SESSION[$rpc_client]->listbalances(1, $addresses);
  $pos_adds = 0;
  
  foreach ($balances as $key => $value) {
	$add_bal = remove_ep($value['balance']);
	if (bccomp($add_bal, '0') === 1) {
	  echo "<tr><td>".$value['address']."</td><td>$add_bal&nbsp;$curr_code</td><td><input type='checkbox'".
	  " name='inputs[]' value='".$value['address']."' onclick='toggle_input($key, $add_bal);' /></td></tr>\n";
	  $pos_adds++;
	}
  }
  
  if ($pos_adds == 0) {
    echo "<tr><td colspan='3'>There are no addresses with a positive balance in your wallet</td></tr>";
  }
  
} elseif (isset($_GET['select'])) {

	$addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);

    foreach ($addresses as $key => $value) {
      $sel_val = ($value == $_GET['address']) ?  " selected='selected'" : '';
      echo "\t<option value='$value'$sel_val>$value</option>";
    }
  
} elseif (isset($_GET['table'])) {
  
  $acc_id = ($_GET['table'] == 1) ? $_SESSION['account_id'] : $_SESSION['account_id'].'-archive';
  $addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($acc_id);
  rpc_error_check();
  
  if (count($addresses) < 1) {
    if ($_GET['table'] == 1) {
      die("<h4>Your active wallet holds no addresses</h4>");
	} else {
      die("<h4>Your archive wallet holds no addresses</h4>");
	}
  }
  $balances = $_SESSION[$rpc_client]->listbalances($min_confs, $addresses); 
  echo "<table id='address_table' class='table table-hover'>
  <tr><th>Address</th><th>Balance</th><th>Withdrawal Limit</th><th>Actions</th></tr>";
  
  foreach ($balances as $key => $value) {
?>

  <tr>
    <td><?php
	  echo '<a href="./?page=search&amp;address='.
	  $value['address'].'">'.$value['address'].'</a>';
	?></td>
	<td><?php echo remove_ep($value['balance'])." $curr_code"; ?></td>
	<td><?php echo remove_ep($value['limit'])." $curr_code"; ?></td>
    <td>
	<?php if ($_GET['table'] == 1) { ?>
      <div class="btn-group">
        <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
        Actions <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#" onclick="arc_address('<?php echo $value['address']; ?>')">Archive Address</a></li>
		  <li><a href="#" onclick="change_limit('<?php echo $value['address']; ?>')">Change Limit</a></li>
	      <li><a href="./?page=receive&amp;address=<?php echo $value['address']; ?>">Show QR Code</a></li>
	      <li><a href="./?page=signver&amp;address=<?php echo $value['address']; ?>">Sign Message</a></li>
        </ul>
      </div>
	<?php } else { ?>
	  <button class="btn btn-mini" href="#" onclick="act_address('<?php echo $value['address']; ?>')">Reactivate</a>
	<?php } ?>
    </td>
  </tr>

<?php
  }
  echo '</table>';

} else {
  die('error: list format was not specified');
}
?>
