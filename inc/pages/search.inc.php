<?php if (login_state() === 'valid') {
  
  if (!empty($_GET['tx'])) {
  
    $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['tx']));
    $tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
    rpc_error_check();
	
	echo "<h1>Transaction Details</h1><br />";
	echo "<p><b>TxID:</b> <a href='./?page=search&amp;rawtx=".
	     $tx['txid']."'>".$tx['txid']."</a></p>";
    if (isset($tx['blockhash'])) {
	  echo "<p><b>Block:</b> <a href='./?page=search&amp;block=".$tx['blockhash']."'>".$tx['blockhash']."</a></p>";
	} else {
	  echo "<p><b>Block:</b> not in a block yet</p>";
	}
	echo "<p><b>Time Sent:</b> ".(isset($tx['time'])?date("Y-m-d h:i A e", $tx['time']):'unknown')."</p>";
	echo "<p><b>Confirmations:</b> ".(isset($tx['confirmations'])?$tx['confirmations']:'0')."</p>";
	echo "<p><b>Lock Height:</b> ".$tx['lockheight']."</p>";
	echo "<p><b>Message:</b> ".(empty($tx['msg'])?'none':$tx['msg'])."</p>";
    echo "<h3>Inputs:</h3>";
	
	if (empty($tx['vin'])) {
	  echo '<p>No Inputs (coinbase genesis transaction)</p>';
	} else {
	  echo '<p>';
	  foreach ($tx['vin'] as $key => $value) {
	    if ($value['coinbase'] == true) {
	      echo "<a href='./?page=search&amp;address=".$value['address']."'>TheCoinbaseAccount".
	           "</a> &rarr; <span class='sad_txt'>".remove_ep($value['value'])."</span> $curr_code (block reward)<br />";
	    } else {
	      echo "<a href='./?page=search&amp;address=".$value['address']."'>".$value['address'].
	           "</a> &rarr; <span class='sad_txt'>".remove_ep($value['value'])."</span> $curr_code<br />";
	    }
	  }
	  echo '</p>';
	}
	
    echo "<h3>Outputs:</h3><p>";
	foreach ($tx['vout'] as $key => $value) {
	  if (isset($tx['limit'])) {
	    echo "Withdrawal limit of input address updated to: <span class='happy_txt'>".
		      remove_ep($tx['limit'])."</span> $curr_code<br />";
	  } else {
	    echo "<a href='./?page=search&amp;address=".$value['address']."'>".$value['address'].
	         "</a> &larr; <span class='happy_txt'>".remove_ep($value['value'])."</span> $curr_code<br />";
	  }
	}
	echo "</p>";
	
  } elseif (!empty($_GET['rawtx'])) {
  
    $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['rawtx']));
    $raw_tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
    rpc_error_check();

	echo "<h1>Raw Transaction</h1><br />";
	echo "<pre>".json_encode($raw_tx, JSON_PRETTY_PRINT)."</pre>";
	
  } elseif (!empty($_GET['address'])) {
  
    $address = preg_replace("/[^a-z0-9]/i", '', $_GET['address']);
	$confs = empty($_GET['confs']) ? 1 : (int)$_GET['confs'];
	$conf_txt = "($confs or more confs)";
    $ainfo = $_SESSION[$rpc_client]->listbalances($confs, array($address));
    rpc_error_check();
	
	echo "<h1>Address Details</h1><br />";
	echo "<p><b>Address:</b> ".$ainfo[0]['address']."</p>";
	echo "<p><b>Balance:</b> ".remove_ep($ainfo[0]['balance'])." $curr_code $conf_txt</p>";
	echo "<p><b>Withdrawal Limit:</b> ".remove_ep($ainfo[0]['limit'])." $curr_code</p>";
	echo "<p><b>Pending Limit:</b> ".remove_ep($ainfo[0]['futurelimit'])." $curr_code</p>";
	echo "<p><b>Last Used:</b> block ".$ainfo[0]['age']."</p>";
	
  } elseif (!empty($_GET['block'])) {
  
    $bhash = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['block']));
    $block = $_SESSION[$rpc_client]->getblock($bhash);
    rpc_error_check();
	
	echo "<div class='pagination float_right'><ul>";
	
	if (isset($block['previousblockhash'])) {
	  echo "<li><a href='./?page=search&amp;block=".$block['previousblockhash']."'><b>Prev Block</b></a></li>";
	} else {
	  echo "<li class='disabled'><a href='#'><b>Prev Block</b></a></li>";
	}
	if (isset($block['nextblockhash'])) {
	  echo "<li><a href='./?page=search&amp;block=".$block['nextblockhash']."'><b>Next Block</b></a>";
	} else {
	  echo "<li class='disabled'><a href='#'><b>Next Block</b></a>";
	}
	
	echo "</ul></div><h1>Block Details</h1>";
	echo "<p><b>Block Hash:</b> ".$block['hash']."</p>";
	echo "<p><b>Master Hash:</b> ".$block['accountroot']."</p>";
	echo "<p><b>Merkle Root:</b> ".$block['merkleroot']."</p>";
	echo "<p><b>Version:</b> ".$block['version']."</p>";
	echo "<p><b>Size:</b> ".round($block['size']/1024, 2)." kB</p>";
	echo "<p><b>Block Height:</b> ".$block['height']."</p>";
	echo "<p><b>Confirmations:</b> ".$block['confirmations']."</p>";
	echo "<p><b>Difficulty:</b> ".$block['difficulty']."</p>";
	echo "<p><b>Nonce:</b> ".$block['nonce']."</p>";
	echo "<p><b>Timestamp:</b> ".date("Y-m-d h:i A e", $block['time'])."</p>";
	echo "<h3>Transactions:</h3><p>";
	
	foreach ($block['tx'] as $key => $value) {
	  echo "<a href='./?page=search&amp;tx=$value'>$value</a><br />";
	}
	echo "</p>";
  } else {
?>

<h1>Search Blockchain</h1><br />
<form name="search_form" class="form-horizontal" method="get" action="./index.php">
  <h3>Find Address <small>input a valid address</small></h3>
  <input type="text" class="long_input" name="address" value="" maxlength="34" />
  <input type="submit" class="btn" value="Search" />
  <input type="hidden" name="page" value="search" />
</form>
<form name="search_form" class="form-horizontal" method="get" action="./index.php">
  <h3>Find Transaction <small>input a valid txid</small></h3>
  <input type="text" class="long_input" name="tx" value="" maxlength="64" />
  <input type="submit" class="btn" value="Search" />
  <input type="hidden" name="page" value="search" />
  </form>
<form name="search_form" class="form-horizontal" method="get" action="./index.php">
  <h3>Find Block <small>input valid block hash</small></h3>
  <input type="text" class="long_input" name="block" value="" maxlength="64" />
  <input type="submit" class="btn" value="Search" />
  <input type="hidden" name="page" value="search" />
</form>

<?php
  }
} else { echo "error: unauthorized access"; } ?>
