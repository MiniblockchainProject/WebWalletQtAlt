<?php
// call required includes
require_once('lib/common.lib.php');
require_once('inc/config.inc.php');

// start the session
session_start();

// set account id to '' if default
if (isset($_SESSION['account_id'])) {
  if ($_SESSION['account_id'] == 'default') {
    $_SESSION['account_id'] = '';
  }
}

// get current page
if (empty($_GET['page'])) {
  $page = 'portal';
} else {
  $page = urlencode($_GET['page']);
}

// get user login state
$login_state = login_state();

// check user login state
if ($login_state === 'valid') {

  // connect to RPC client
  $_SESSION[$rpc_client] = new RPCclient($rpc_user, $rpc_pass);

  // save any errors to variable
  $rpc_error = $_SESSION[$rpc_client]->error;
}
?>
