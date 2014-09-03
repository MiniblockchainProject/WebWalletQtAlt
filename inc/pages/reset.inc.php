<form name="reset_form" id="reset_form" method="post" action="">
  <div class="alert no_display" id="error_box">
	<span id='error_msg'></span>
  </div>
  <h1>Reset Password</h1>
  <p>Account ID: <?php safe_echo($_GET['id']); ?></p>
  <table class="form-horizontal" id="form_table" cellpadding="4px">
	<tr>
	  <td>New Password:&nbsp;</td>
	  <td><input type="password" name="pass" size="20" maxlength="99" required="required" /></td>
	</tr>
	<tr>
	  <td>Repeat Pass:&nbsp;</td>
	  <td><input type="password" name="passr" size="20" maxlength="99" required="required" /></td>
	</tr>
  </table>
  <input type="hidden" name="id" value="<?php safe_echo($_GET['id']); ?>" />
  <input type="hidden" name="code" value="<?php safe_echo($_GET['code']); ?>" />
  <input type="submit" id="reset_btn" class="btn" value="change password" />
</form>

<script language="JavaScript">
function handle_reset(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('Password successfully reset! Redirecting ...');
	setCookie('account_id', res_arr[1], 3640);
    setTimeout(function(){redirect('./?page=login&id='+res_arr[1]);}, 1000);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('reset_form', 'post', './inc/pages/jobs/recover.inc.php', handle_reset);
});
</script>
