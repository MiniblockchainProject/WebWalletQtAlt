<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
} elseif (!isset($_POST['outputs']) || !isset($_POST['output_vals'])) {
  die("error: empty output value and/or address");
} elseif (count($_POST['outputs']) != count($_POST['output_vals'])) {
  die("error: unexpected output format detected");
} elseif (!isset($_POST['fee_val']) || !is_numeric($_POST['fee_val'])) {
  die("error: fee must be a number greater than 0");
} elseif (isset($_POST['tx_msg']) && strlen($_POST['tx_msg']) > 64) {
  die('error: message cannot be more than 64 characters');
} elseif (bccomp($_POST['fee_val'], '0') !== 1) {
  die('error: miners fee must be greater than 0');
}

$total_output = '0';
$output_array = array();
	
if (count($_POST['outputs']) < 256) {
  foreach ($_POST['outputs'] as $key => $value) {
    if (!empty($value)) {
	  if (is_numeric($_POST['output_vals'][$key])) {
	    if (bccomp($_POST['output_vals'][$key], '0') === 1) {
		  if (!isset($output_array[$value])) {
		    $total_output = bcadd($total_output, $_POST['output_vals'][$key]);
		    $output_array[$value] = float_format($_POST['output_vals'][$key]).'ep';
		  } else {
		    die('error: same output used more than once: '.safe_str($value));
		  }
	    } else {
		  die('error: all output values must be greater than 0');
	    }
	  } else {
	    die('error: '.safe_str($_POST['output_vals'][$key]).' is not a valid output value');
	  }
	} else {
	  die('error: one or more output address is empty');
	}
  }
} else {
  die('error: cannot have more than 255 outputs');
}

$addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
$total_output = bcadd($total_output, $_POST['fee_val']);
$reman_input = $total_output;
$input_array = array();
rpc_error_check();
	
if (isset($_POST['inputs'])) {
  if (count($_POST['inputs']) < 256) {
	$balances = $_SESSION[$rpc_client]->listbalances($min_confs, $_POST['inputs']);
	rpc_error_check();
	foreach ($_POST['inputs'] as $key => $value) {
	  if (!in_array($value, $addresses)) {
		die('error: unknown inputs used');
	  } elseif (in_array($value, $_POST['outputs'])) {
		die('error: input same as output: '.$value);
	  }
	  $cln_balance = remove_ep($balances[$key]['balance']);
	  $bal_comp = bccomp($reman_input, $cln_balance);
	  if ($bal_comp === 1) {
		$input_array[$value] = $balances[$key]['balance'];
		$reman_input = bcsub($reman_input, $cln_balance);
	  } elseif ($bal_comp === 0) {
		$input_array[$value] = $balances[$key]['balance'];
		$reman_input = 0;
		break;
	  } else {
		$input_array[$value] = float_format($reman_input).'ep';
		$reman_input = 0;
		break;
	  }
	}
	if (bccomp($reman_input, '0') !== 0) {
	  die('error: selected inputs lack sufficient funds');
	}
  } else {
	die('error: cannot have more than 255 inputs');
  }
} else {
  foreach ($addresses as $key => $value) {
    if (in_array($value, $_POST['outputs'])) { continue; }
    $add_balance = $_SESSION[$rpc_client]->listbalances($min_confs, array($value));
	rpc_error_check();
    $cln_balance = remove_ep($add_balance[0]['balance']);
    $bal_comp = bccomp($reman_input, $cln_balance);
    if ($bal_comp === 1) {
	  $input_array[$value] = $add_balance[0]['balance'];
	  $reman_input = bcsub($reman_input, $cln_balance);
    } elseif ($bal_comp === 0) {
	  $input_array[$value] = $add_balance[0]['balance'];
	  $reman_input = 0;
	  break;
    } else {
	  $input_array[$value] = float_format($reman_input).'ep';
	  $reman_input = 0;
	  break;
    }
  }
  if (bccomp($reman_input, '0') !== 0) {
    die('error: account balance is insufficient');
  }
}

if (empty($_POST['tx_msg'])) {
  $raw_tx = $_SESSION[$rpc_client]->createrawtransaction($input_array, $output_array);
  $_POST['tx_msg'] = '';
} else {
  $raw_tx = $_SESSION[$rpc_client]->createrawtransaction($input_array, $output_array, 0, $_POST['tx_msg']);
}
rpc_error_check();
$signed_tx = $_SESSION[$rpc_client]->signrawtransaction($raw_tx);
rpc_error_check();

if ($signed_tx['complete'] == true) {
  $hex_hash = hash('sha256', $signed_tx['hex']);
  $_SESSION[$hex_hash]['sent'] = false;
  $_SESSION[$hex_hash]['hex'] = $signed_tx['hex'];
  $_SESSION[$hex_hash]['fee'] = $_POST['fee_val'];
  $_SESSION[$hex_hash]['msg'] = $_POST['tx_msg'];
  $_SESSION[$hex_hash]['total'] = $total_output;
  $_SESSION[$hex_hash]['inputs'] = $input_array;
  $_SESSION[$hex_hash]['outputs'] = $output_array;
  echo "success:$hex_hash";
} else {
  die("error: transaction could not be fully signed");
}
?>