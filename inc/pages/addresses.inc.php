<?php if (login_state() === 'valid') { ?>

<div id="info_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Change Withdrawal Limit</h3>
  </div>
  <div class="modal-body">
    <div id="limit_div">
	  <form name="limit_form" id="limit_form" method="post">
	    <label>Address:</label>
	    <select id="addresses" class="long_input"></select>
	    <label>New Withdrawal Limit:</label>
	    <div class="input-append">
		  <input type="text" id="limit" value="" maxlength="21" required="required" />
		  <span class="add-on"><?php echo $curr_code; ?></span>
	    </div>
	  </form>
	</div>
	<div id="info_div"></div>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button class="btn" onclick="hide_modal();">Cancel</button>
      <button class="btn btn-primary" id='modal_btn'>Update</button>
	</div>
  </div>
</div>

<div class="alert no_display" id="error_box">
  <span id='error_msg'></span>
</div>
<button class="btn float_right" onclick="new_address();">New Address</button>
<h1>My Addresses</h1><br />

<div class="tabbable expand">
  <div class="tab-content">
    <ul class="nav nav-tabs no_margin">
	  <li class="active"><a href="#tab1" data-toggle="tab">Active</a></li>
	  <li><a href="#tab2" data-toggle="tab">Archived</a></li>
    </ul>
	<div class="tab-pane active" id="tab1">
	  <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
	</div>
	<div class="tab-pane" id="tab2">
	  <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
	</div>
  </div>
</div>

<script language="JavaScript">
var move_mode = 0;

function hide_modal() {
  $('#info_modal').modal('hide');
}

function handle_change(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html("New withdrawal limit will take effect in "+res_arr[2]+". Txid: "+
	"<a style='color:#FFFFFF;' href='./?page=search&tx="+res_arr[1]+"'>"+res_arr[1]+'</a>');
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
  hide_modal();
}

function handle_info(response) {
  $('#info_div').html(response);
  $('#limit_div').hide();
  $('#info_div').show();
  $('#modal_btn').html('Confirm');
  
  $('#modal_btn').click(function() {
    var new_limit = $('#limit').val();
	var targ_addr = $('#addresses').val();
    ajax_post('./inc/pages/jobs/editlimit.inc.php', handle_change, {limit:new_limit,address:targ_addr});
  });
}

function show_modal() {
  $('#info_div').hide();
  $('#limit_div').show();
  $('#error_box').hide();
  $('#modal_btn').html('Update');
  $('#info_modal').modal('show');
  
  $('#modal_btn').click(function() {
    var new_limit = $('#limit').val();
	var address = $('#addresses').val();
    ajax_post('./inc/pages/jobs/editlimit.inc.php', handle_info, {info:new_limit+':'+address});
  });
}

function get_addresses() {
  ajax_get('./inc/pages/jobs/listadds.inc.php', apply_update('tab1'), 'table=1');
}

function get_archived() {
  ajax_get('./inc/pages/jobs/listadds.inc.php', apply_update('tab2'), 'table=2');
}

function refresh_lists() {
  get_addresses();
  get_archived();
}

function handle_load(response) {
  $('#addresses').html(response);
  show_modal();
}

function handle_move(response) {
  if (response == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
	if (move_mode == 1) {
      $('#error_msg').html('Address was successfully reactivated');
	} else {
      $('#error_msg').html('Address was successfully archived');
	}
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
  refresh_lists();
}

function handle_newadd(response) {
  var res_arr = response.split(':');
  if (res_arr[0] == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
    $('#error_msg').html('New address added to wallet: '+res_arr[1]);
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
  refresh_lists();
}

function change_limit(address) {
  ajax_get('./inc/pages/jobs/listadds.inc.php', handle_load, 'select=1&address='+address);
}

function act_address(address) {
  move_mode = 1;
  if (confirm('Are you sure you want to reactivate this address?')) {
    ajax_post('./inc/pages/jobs/addtools.inc.php', handle_move, {moveadd:move_mode+':'+address});
  }
}

function arc_address(address) {
  move_mode = 2;
  if (confirm('Are you sure you want to archive this address?')) {
    ajax_post('./inc/pages/jobs/addtools.inc.php', handle_move, {moveadd:move_mode+':'+address});
  }
}

function new_address() {
  if (confirm('Are you sure you want to generate a new address?')) {
    ajax_get('./inc/pages/jobs/addtools.inc.php', handle_newadd, 'newadd=1');
  }
}

$(document).ready(function() {
  refresh_lists();
});
</script>
<?php } else { echo "error: unauthorized access"; } ?>