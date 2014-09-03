<?php if (login_state() === 'valid') { ?>

<div class="tabbable" style="width:560px">
  <div class="tab-content">
    <ul class="nav nav-pills float_right">
	  <li class="active"><a href="#tab1" data-toggle="tab">Import</a></li>
	  <li><a href="#tab2" data-toggle="tab">Export</a></li>
    </ul>
	<div class="tab-pane active" id="tab1">
	  <h1>Import Tools</h1><br />
	  <div class="alert no_display" id="error_box1">
        <span id='error_msg1'></span>
	  </div>
	  <form name="import_form" id="import_form" class="form-horizontal" method="post">
	    <h3>Import Private Key</h3>
	    <input type="text" name="priv_key" class="long_input" value="" maxlength="60" placeholder="Input a private key" />
		<input type="submit" class="btn" value="Import Key" />
	  </form>
	  <h3>Import JSON Wallet</h3>
	  <textarea id='wallet_in' class='longer_input tall_input'></textarea>
	  <br /><button class="btn" onclick="import_wallet();">Import Wallet</a></button>
	</div>
	<div class="tab-pane" id="tab2">
	  <h1>Export Tools</h1><br />
	  <div class="alert no_display" id="error_box2">
        <span id='error_msg2'></span>
	  </div>
	  <form name="export_form" id="export_form" class="form-horizontal" method="post">
	    <h3>Dump Private Key</h3>
		<select name="address" id="address" class="long_input">
		<?php
		$addresses = $_SESSION[$rpc_client]->getaddressesbyaccount($_SESSION['account_id']);
		foreach ($addresses as $key => $value) {
		  echo "\t<option value='$value'>$value</option>";
		}
		?>
		</select>
		<input type="submit" class="btn" value="Show Private Key" />
	  </form>
	  <h3>Dump All Private Keys</h3>
	  <button class="btn" onclick="export_wallet();">Dump Entire Wallet</a></button>
	  <div id="dump_div" class="no_display">
	    <br /><label>Wallet in JSON format:</label>
	    <textarea id='wallet_out' class='longer_input tall_input'></textarea>
	  </div>
	</div>
  </div>
</div>

<script language="JavaScript">
function handle_import(response) {
  if (response == 'success') {
    $('#error_box1').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg1').html('Successfully imported private key into wallet');
	update_page();
  } else {
    $('#error_box1').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg1').html(response);
  }
}

function handle_export(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box2').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg2').html('Private key:<br />'+res_arr[1]);
  } else {
    $('#error_box2').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg2').html(response);
  }
}

function handle_impwal(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box1').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg1').html(res_arr[1]);
	update_page();
  } else {
    $('#error_box1').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg1').html(response);
  }
}

function handle_expwal(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box2').hide();
	$('#dump_div').show();
    $('#wallet_out').html(Base64.decode(res_arr[1]));
  } else {
    $('#error_box2').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg2').html(response);
  }
}

function import_wallet() {
  ajax_post('./inc/pages/jobs/import.inc.php', handle_impwal, "wallet="+$('#wallet_in').val());
}

function export_wallet() {
  ajax_post('./inc/pages/jobs/export.inc.php', handle_expwal, 'dump=wallet');
}

$(document).ready(function() {
  bind_form('import_form', 'post', './inc/pages/jobs/import.inc.php', handle_import);
  bind_form('export_form', 'post', './inc/pages/jobs/export.inc.php', handle_export);
});
</script>

<?php } else { echo "error: unauthorized access"; } ?>