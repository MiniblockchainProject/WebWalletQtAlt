<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Install Web Wallet</title>

	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/boilerplate.css" />
	<link rel="stylesheet" href="../css/bootstrap.min.css" />
	<link rel="stylesheet" href="../css/darkstrap.min.css" />
	<link rel="stylesheet" href="../css/custom.css" />
	
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery-1.11.1.min.js"></script>
</head>
<body style="padding:20px;text-align:center;">
<?php
require_once('../inc/config.inc.php');
require_once('../lib/common.lib.php');

if (isset($_POST) && post_get_check()) {

  if (empty($_POST['dpass'])) {
    $error = 'default password was left empty';
  } elseif (empty($_POST['idir'])) {
    $error = 'install directory was left empty';
  } elseif (empty($_POST['rpcuser'])) {
    $error = 'rpc username was left empty';
  } elseif (empty($_POST['rpcpass'])) {
    $error = 'rpc password was left empty';
  } else {
  
    $new_config = array(
	  'rpc_user' => $_POST['rpcuser'], 'rpc_pass' => $_POST['rpcpass'],
	  'install_dir' => $_POST['idir'], 'rand_str' => rand_str()
    );

    if (update_config($new_config)) {
  
      $user_data = array(
        'account_id' => 'default',
        'email_add' => 'default@default.com',
        'pass_hash' => hash('sha256', $_POST['dpass']),
        'ip_address' => get_remote_ip(),
        'fail_count' => 0,
        'lock_state' => 0,
        'lock_time' => time()
      );

      $fn = '../db/.default';
      $acc_json = json_encode($user_data);
      if (file_put_contents($fn, $acc_json)) {
        chmod($fn, 0600);
		$inst_done = true;
      } else {
        $error = 'could not create default account';
      }
	} else {
	  $error = "unable to update config file";
	}
  }
}

if (isset($inst_done)) {
?>

<h1>Install Successful</h1><br />

<p>All the critical settings have been configured and you can now use the web wallet, however you should check the /inc/config.inc.php<br />file first and make sure everything else is configured the way you want. Also make sure to delete the install folder.</p>

<p>You can log into the default account by using 'default' as the username and the password you just set up. You can change<br /> the password of the default account at a later time by visiting Account->Settings when logged into the default account.</p>

<p><a href="../">GO TO LOGIN PAGE</a></p>

<?php
} else {
  echo "<h1>Install Script</h1><br />";
  if (isset($error)) { echo "<p class='sad_txt'>$error</p>"; }
?>

<form name="install_form" method="post" action="./install.php">
  <label title="the location where you have installed this script ('/' if at root)">Current installation directory:</label>
  <input type="text" name="idir" required="required" value="<?php if (!empty($_POST['idir'])) { echo $_POST['idir']; } ?>" />
  <label title="the rpcusername value stored in your cryptonite.conf file">Cryptonite RPC Username:</label>
  <input type="text" name="rpcuser" required="required" value="<?php if (!empty($_POST['rpcuser'])) { echo $_POST['rpcuser']; } ?>" />
  <label title="the rpcpassword value stored in your cryptonite.conf file">Cryptonite RPC Password:</label>
  <input type="password" name="rpcpass" required="required" value="<?php if (!empty($_POST['rpcpass'])) { echo $_POST['rpcpass']; } ?>" />
  <label title="the default account ('') has full knowledge of all other accounts">Password for default account:</label>
  <input type="password" name="dpass" required="required" value="<?php if (!empty($_POST['dpass'])) { echo $_POST['dpass']; } ?>" />
  <br /><br />
  <input type="submit" class="btn" value="Install" />
</form>

<?php } ?>
</body>
</html>