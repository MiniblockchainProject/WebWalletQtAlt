<?php if (login_state() === 'valid') { ?>
<div id="info_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Transaction Details</h3>
  </div>
  <div class="modal-body">
    <div id="tran_info"></div>
	<div id="book_info">
	  <label>Select recipient from your address book:</label>
	  <div id="book_list"></div>
	</div>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button class="btn" onclick="hide_modal();">Cancel</button>
      <button class="btn btn-primary" id='modal_btn'>Send Transaction</button>
	</div>
  </div>
</div>

<form name="send_form" id="send_form" method="post" action="">
  <div class="alert no_display" id="error_box">
    <span id='error_msg'></span>
  </div>
  <p class="float_right"><a href="#" id="mode" onclick="toggle_advmode();">Advanced Mode</a></p>
  <h1>Send Payment</h1><br />
  <div id="input_con" class="no_display">
    <label>Source addresses (select 1 or more)</label>
    <table class="table table-condensed" id="input_table">
	  <thead><tr>
		<th>Address</th>
		<th>Balance</th>
		<th><input type='checkbox' onclick="select_all(this);" /></th>
	  </tr></thead>
      <tbody id='in_tb'></tbody>
	  <tfoot><tr>
		<td><b>Selected total:</b></td>
		<td colspan="2"><b><span id="in_total">0.0000000000</span> 
		<?php echo $curr_code; ?></b></td>
	  </tr></tfoot>
    </table>
  </div>
  <label>Destination address (<a href="#" onclick="add_output();">add another</a>)</label>
  <div id="output_list"></div>
  <label>Message (optional)</label>
  <textarea name="tx_msg" id="tx_msg" maxlength="64" placeholder="This message will be visible in the blockchain unless you manually encrypt it."></textarea>
  <label>Miners fee</label>
  <div class="input-append">
    <input class="amount_txtbox span2" type="text" value="<?php echo $min_fee; ?>" name="fee_val" id="fee_val" maxlength="21" />&nbsp;<span class="add-on"><?php echo $curr_code; ?></span>
  </div>
  <input type="submit" class="btn float_right" name="create_tx" value="Review Transaction" />
</form>

<script language="JavaScript">
var out_count = 0;
var in_total = 0;
var check_states = [];
var adv_mode = false;

function toggle_advmode() {
  if (adv_mode == true) {
    $("#input_con").hide();
	$('#mode').html('Advanced Mode');
	adv_mode = false;
  } else {
    $("#input_con").show();
	$('#mode').html('Basic Mode');
	adv_mode = true;
  }
}

function toggle_input(key, balance) {
  if (check_states[key] == null || check_states[key] == 0) {
    in_total += balance;
	check_states[key] = 1;
  } else {
    in_total -= balance;
	check_states[key] = 0;
  }
  if (in_total < 0.0000000001) { in_total = 0; }
  $("#in_total").html(in_total.toFixed(<?php echo $dec_count; ?>));
}

function add_output() {
  if (out_count < 255) {
    $('#output_list').append('<div id="output'+out_count+'"><div class="input-append"><input id="addout'+out_count+'" class="address_txtbox" type="text" value="" placeholder="e.g. CHWnXQ9icLVoVKxgw49riHo6EwLC1fCR64" name="outputs[]" maxlength="34" />&nbsp;<button class="btn book_btn" onclick="show_addbook('+out_count+'); return false;"><i class="icon-book icon-white"></i></button></div>&nbsp;<div class="input-append"><input class="amount_txtbox" class="span2" type="text" value="0.0" name="output_vals[]" maxlength="21" />&nbsp;<span class="add-on"><?php echo $curr_code; ?></span></div>&nbsp;<input type="button" class="btn remove_output" onclick="remove_output('+out_count+');" value="X" /></div>');
    out_count++;
  }
}

function remove_output(out_targ) {
  if (out_count > 1) {
    $("#output"+out_targ).remove();
    out_count--;
  }
}

function select_all(source) {
  checkboxes = document.getElementsByName('inputs[]');
  for (var i=0,n=checkboxes.length;i<n;i++) {
    if (checkboxes[i].checked != source.checked) {
	  checkboxes[i].click();
	}
  }
}

function get_inputs() {
  ajax_get('./inc/pages/jobs/listadds.inc.php', apply_update('in_tb'), 'rows=1');
}

function show_modal(mode) {
  if (mode == 1) {
    $('#book_info').hide();
    $('#tran_info').show();
	$('#modal_btn').html('Send Transaction');
  } else {
    $('#tran_info').hide();
    $('#book_info').show();
	$('#modal_btn').html('Add Recipient');
  } 
  $('#info_modal').modal('show');
}

function hide_modal() {
  $('#info_modal').modal('hide');
}

function show_txinfo(response) {
  $('#tran_info').html(response);
  show_modal(1);
}

function show_addbook(out_targ) {
  ajax_get('./inc/pages/jobs/listbook.inc.php', apply_update('book_list'), 'select=1');
  $('#modal_btn').click(function() {
    $('#addout'+out_targ).val($('#book_select').val());
	hide_modal();
  });
  show_modal(2);
}

function handle_send(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html("Transaction sent! Txid: <a style='color:#FFFFFF;' "+
	"href='./?page=search&tx="+res_arr[1]+"'>"+res_arr[1]+'</a>');
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
  hide_modal();
  update_page();
}

function handle_create(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {    
	ajax_post('./inc/pages/jobs/txinfo.inc.php', show_txinfo, {hex_hash: res_arr[1]});
	$('#modal_btn').click(function() {
	  $('#tran_info').html("<p>Attempting to broadcast transaction ...</p>");
      ajax_post('./inc/pages/jobs/sendtx.inc.php', handle_send, {hex_hash: res_arr[1]});
    });
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  add_output();
  get_inputs();
  bind_form('send_form', 'post', './inc/pages/jobs/createtx.inc.php', handle_create);
});
</script>
<?php } else { echo "error: unauthorized access"; } ?>