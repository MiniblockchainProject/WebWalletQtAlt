<?php if (login_state() === 'valid') { ?>

<div id="info_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Update Account Settings</h3>
  </div>
  <div class="modal-body">
	<div class="alert no_display" id="error_box">
	  <span id='error_msg'></span>
	</div>
    <div id="new_email">
	  <form name="email_form" id="email_form" method="post" action="">
	    <label>Current Password:</label>
		<input type="password" name="old_pass" value="" maxlength="99" required="required" />
	    <label>New Email:</label>
		<input type="text" name="new_email" value="" maxlength="99" required="required" />
	    <label>Repeat Email:</label>
		<input type="text" name="rep_email" value="" maxlength="99" required="required" />
		<br /><br />
		<input type="submit" class="btn btn-primary" value="Update" />
	  </form>
	</div>
	<div id="new_pass">
	  <form name="pass_form" id="pass_form" method="post" action="">
	    <label>Old Password:</label>
		<input type="password" name="old_pass" value="" maxlength="99" required="required" />
	    <label>New Password:</label>
		<input type="password" name="new_pass" value="" maxlength="99" required="required" />
	    <label>Repeat Password:</label>
		<input type="password" name="rep_pass" value="" maxlength="99" required="required" />
		<br /><br />
		<input type="submit" class="btn btn-primary" value="Update" />
	  </form>
	</div>
  </div>
  <div class="modal-footer">
    <button class="btn" onclick="hide_modal();">Close</button>
  </div>
</div>

<h1>Account Settings</h1><br />
<p><b>Account ID:</b> <?php echo $_SESSION['account_id']; ?></p>
<p><b>Email:</b> <span id="email"><?php echo $_SESSION['email_add']; ?></span> 
(<a href="#" onclick="show_modal(1);">change</a>)</p>
<p><b>Password:</b> ********** (<a href="#" onclick="show_modal(2);">change</a>)</p>

<script language="JavaScript">
var modal_mode = 1;

function show_modal(mode) {
  if (mode == 1) {
    $('#new_pass').hide();
    $('#new_email').show();
  } else if (mode == 2) {
    $('#new_email').hide();
    $('#new_pass').show();
  } else {
    return false;
  }
  modal_mode = mode;
  $('#error_box').hide();
  $('#info_modal').modal('show');
}

function hide_modal() {
  $('#info_modal').modal('hide');
}

function handle_update(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
	if (modal_mode == 1) {
	  $('#email').html(res_arr[1]);
      $('#error_msg').html('Email address sucessfully updated.');
	} else {
      $('#error_msg').html('Password sucessfully updated.');
	}
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('email_form', 'post', './inc/pages/jobs/update.inc.php', handle_update);
  bind_form('pass_form', 'post', './inc/pages/jobs/update.inc.php', handle_update);
});
</script>

<?php } else { echo "error: unauthorized access"; } ?>