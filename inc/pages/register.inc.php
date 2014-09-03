<?php
if (isset($_SESSION['account_id'])) {
  echo '<p>Already logged in. Redirecting...</p>';
  redirect('./?page=portal');
} else {
?>

<div class="row">
  <div class="span6">
	<form name="join_form" id="join_form" method="post" action="">
	  <div class="alert no_display" id="error_box">
		<span id='error_msg'></span>
	  </div>
	  <h1>Create New Account</h1>
	  <table class="form-horizontal" id="form_table" cellpadding="4px">
		<tr>
		  <td>Email:&nbsp;</td>
		  <td><input type="email" name="email" size="20" maxlength="99" required="required" /></td>
		</tr>
		<tr>
		  <td>Repeat Email:&nbsp;</td>
		  <td><input type="email" name="emailr" size="20" maxlength="99" required="required" /></td>
		</tr>
		<tr>
		  <td>Password:&nbsp;</td>
		  <td><input type="password" name="pass" size="20" maxlength="99" required="required" /></td>
		</tr>
		<tr>
		  <td>Repeat Pass:&nbsp;</td>
		  <td><input type="password" name="passr" size="20" maxlength="99" required="required" /></td>
		</tr>
	  </table>
	  <input type="submit" id="join_btn" class="btn" value="register" />
	</form>
  </div>
  <div class="span6">
    <h3>Password Requirements</h3>
	<p>Your password must be at least 10 characters long and no more than 99 characters long. It should also contain a mixture of numbers and letters.</p>
    <h3>Already Registered?</h3>
	<p>If you already have an account you may proceed to the <a href="./?page=login">login page</a>.</p>
  </div>
</div>

<script language="JavaScript">
function handle_join(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('Account successfully created! Redirecting ...');
	setCookie('account_id', res_arr[1], 3640);
    setTimeout(function(){redirect('./?page=login&id='+res_arr[1]);}, 1000);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('join_form', 'post', './inc/pages/jobs/tryjoin.inc.php', handle_join);
});
</script>

<?php } ?>
