<?php if (login_state() === 'valid') { ?>

<div id="info_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Update Address Book</h3>
  </div>
  <div class="modal-body">
	<div class="alert no_display" id="error_box">
	  <span id='error_msg'></span>
	</div>
    <div id="new_entry">
	  <form name="entry_form" id="entry_form" method="post">
	    <label>Address:</label>
		<input type="text" name="address" value="" maxlength="34" required="required" />
	    <label>Label:</label>
		<input type="text" name="addlabel" value="" maxlength="50" required="required" />
		<br /><br />
		<input type="submit" class="btn btn-primary" value="Add New Entry" />
	  </form>
	</div>
    <div id="edit_entry">
	  <form name="edit_form" id="edit_form" method="post">
		<input type="hidden" name="address" id="targ_add" value="" maxlength="34" />
	    <label>New Label:</label>
		<input type="text" name="newlabel" id="newlabel" value="" maxlength="50" required="required" />
		<br /><br />
		<input type="submit" class="btn btn-primary" value="Update Entry" />
	  </form>
	</div>
  </div>
  <div class="modal-footer">
    <button class="btn" onclick="hide_modal();">Close</button>
  </div>
</div>

<button class="btn float_right" onclick="show_modal(1);">Add New Entry</button>
<h1>Address Book</h1><br />
<div id='book_div'>
  <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
</div>

<script language="JavaScript">
var modal_mode = 1;

function show_modal(mode) {
  if (mode == 1) {
    $('#edit_entry').hide();
    $('#new_entry').show();
  } else if (mode == 2) {
    $('#new_entry').hide();
    $('#edit_entry').show();
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

function edit_label(address) {
  $('#targ_add').val(address);
  $('#newlabel').val('');
  show_modal(2);
}

function del_entry(address) {
  if (confirm('Remove this entry from your address book?')) {
    ajax_get('./inc/pages/jobs/editbook.inc.php', update_list, 'delete='+address);
  }
}

function update_list() {
  ajax_get('./inc/pages/jobs/listbook.inc.php', apply_update('book_div'), 'table=1');
}

function handle_update(response) {
  if (response == 'success') {
    $('#error_box').show().removeClass('alert-error').addClass('alert-success');
	if (modal_mode == 1) {
	  update_list();
      $('#error_msg').html('New entry successfully added to address book.');
	} else {
	  update_list();
      $('#error_msg').html('Address book entry sucessfully updated.');
	}
  } else {
    $('#error_box').show().removeClass('alert-success').addClass('alert-error');
    $('#error_msg').html(response);
  }
}

$(document).ready(function() {
  bind_form('entry_form', 'post', './inc/pages/jobs/editbook.inc.php', handle_update);
  bind_form('edit_form', 'post', './inc/pages/jobs/editbook.inc.php', handle_update);
  update_list();
});
</script>

<?php } else { echo "error: unauthorized access"; } ?>