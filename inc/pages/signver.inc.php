<?php if (login_state() === 'valid') { ?>

<div class="tabbable" style="width:560px">
  <div class="tab-content">
    <ul class="nav nav-pills float_right">
	  <li class="active"><a href="#tab1" data-toggle="tab">Sign</a></li>
	  <li><a href="#tab2" data-toggle="tab">Verify</a></li>
    </ul>
	<div class="tab-pane active" id="tab1">
	  <h1>Sign Message</h1><br />
	  <div class="alert no_display" id="error_box1">
        <span id='error_msg1'></span>
	  </div>
	  <form name="sign_form" id="sign_form" method="post" action="">
	    <label>Address:</label>
		<input class="long_input" name="address" type="text" value="<?php 
		if (!empty($_GET['address'])) { echo $_GET['address']; } ?>" maxlength="34" required="required" />
		<label>Message:</label>
	    <textarea id="tx_msg" name="message" maxlength="9999" required="required"></textarea>
		<br /><br />
		<input type="submit" class="btn" value="Sign" />
      </form>
	</div>
	<div class="tab-pane" id="tab2">
	  <h1>Verify Message</h1><br />
	  <div class="alert no_display" id="error_box2">
        <span id='error_msg2'></span>
	  </div>
	  <form name="verify_form" id="verify_form" method="post" action="">
	    <label>Address:</label>
		<input class="long_input" name="address" type="text" value="" maxlength="34" required="required" />
		<label>Message:</label>
	    <textarea id="tx_msg" name="message" maxlength="9999" required="required"></textarea>
	    <label>Signature:</label>
		<input class="longer_input" name="signature" type="text" value="" maxlength="99" required="required" />
		<br /><br />
		<input type="submit" class="btn" value="Verify" />
      </form>
	</div>
  </div>
</div>

<script language="JavaScript">
function handle_sign(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box1').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg1').html('<p>Signature:</p><input type="text" value="'+
	  res_arr[1]+'" id="sig_box" style="width:100%" />');
	$("#sig_box").focus(function(){ $(this).select(); });
  } else {
    $('#error_box1').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg1').html(response);
  }
}

function handle_verify(response) {
  if (response == 'success') {
    $('#error_box2').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg2').html('Signature is valid');
  } else {
    $('#error_box2').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg2').html(response);
  }
}

$(document).ready(function() {
  bind_form('sign_form', 'post', './inc/pages/jobs/sign.inc.php', handle_sign);
  bind_form('verify_form', 'post', './inc/pages/jobs/verify.inc.php', handle_verify);
});
</script>
	
<?php } else { echo "error: unauthorized access"; } ?>