<?php if (login_state() === 'valid') { ?>

<h1>Create Multi-Sig Address</h1><br />
<form name="multiadd_form" id="multiadd_form" class="form-horizontal" method="post" action="">
  <div class="alert no_display" id="error_box">
	<span id='error_msg'></span>
  </div>
  <label>List of authorized addresses (1 per line):</label>
  <textarea name="addlist" class="long_input tall_input" maxlength="9999" required="required"></textarea>
  <div style="margin-top:10px;">
    <label>Signatures required (1 or more):</label>
    <input type="text" name="sigcount" value="1" maxlength="3" required="required" />
	&nbsp;&nbsp;<input type="submit" class="btn" value="Create Address" />
  </div>
</form>

<script language="JavaScript">
function handle_genmulti(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('New multi-sig address created: '+res_arr[1]);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('multiadd_form', 'post', './inc/pages/jobs/genmulti.inc.php', handle_genmulti);
});
</script>
	
<?php } else { echo "error: unauthorized access"; } ?>