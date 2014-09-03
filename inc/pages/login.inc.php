<?php
if (isset($_SESSION['account_id'])) {
  echo '<p>Login successful. Redirecting...</p>';
  redirect('./?page=portal');
} else {
?>

<div class="row">
  <div class="span6">
	<form name="login_form" id="login_form" method="post" action="">
	  <div class="alert no_display" id="error_box">
		<span id='error_msg'></span>
	  </div>
	  <h1>Wallet Login</h1>
	  <table class="form-horizontal" id="form_table" cellpadding="4px">
		<tr>
		  <td>Account&nbsp;ID:&nbsp;</td>
		  <td><input type="text" name="acc" id="acc" size="20" maxlength="50" value="<?php 
		  if (!empty($_GET['id'])) { safe_echo($_GET['id']); } ?>" required="required" /></td>
		</tr>
		<tr>
		  <td>Password:&nbsp;</td>
		  <td><input type="password" name="pass" id="pass" size="20" maxlength="99" required="required" /></td>
		</tr>
	  </table>
	  <label>Logout if IP changes?</label>
	  No: <input type="radio" name="lock" value="0" checked="checked" />&nbsp;
	  Yes: <input type="radio" name="lock" value="1" />
	  <br /><br />
	  <input type="submit" id="login_btn" class="btn" value="login to wallet" />
	</form>
  </div>
  <div class="span6">
    <h3>Create New Account</h3>
	<p>Don't have an account? <a href="./?page=register">Click here</a> to register.</p>
    <h3>Forgot Something?</h3>
	<p>Enter Account ID or the email you registered with:</p>
	<form name="reset_form" id="reset_form" class="form-horizontal" method="post" action="">
	  <input type="text" name="email" id="email" size="20" maxlength="99" required="required" />
	  <input type="submit" id="reset_btn" class="btn" value="send email" />
	</form>
  </div>
</div>

<script language="JavaScript">
function handle_login(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('Credentials verified! Redirecting ...');
	setCookie('account_id', res_arr[1], 3640);
    setTimeout(function(){redirect('./?page=portal');}, 1000);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

function handle_reset(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('Recovery email sent to: '+res_arr[1]);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('login_form', 'post', './inc/pages/jobs/trylogin.inc.php', handle_login);
  bind_form('reset_form', 'post', './inc/pages/jobs/recover.inc.php', handle_reset);
  var acc_id = getCookie('account_id');
  if ($('#acc').val() == '' && acc_id) {
    $('#acc').val(acc_id);
  }
});
</script>

<?php } ?>
