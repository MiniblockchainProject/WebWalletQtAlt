<?php if (login_state() === 'valid') { ?>
<div class="row">
  <div class="span6">
    <h1>Receive Payment</h1><br />
    <label>Your receiving addresses:</label>
    <select id="addresses" class="long_input"></select>
    <label>Amount (optional):</label>
    <div class="input-append">
      <input type="text" id="amount" value="0.0" maxlength="21" />
      <span class="add-on"><?php echo $curr_code; ?></span>
    </div>
    <br /><br />
    <button class="btn" onclick="gen_qrcode();">Create QR Code</button>
  </div>
  <div class="span6">
    <div id="qrbox" class="well"><div id="qrcode"></div></div>
  </div>
</div>

<script language="JavaScript">
var get_add = '<?php echo (empty($_GET['address'])) ? 'n/a' : safe_str($_GET['address']); ?>';

function draw_qrcode(address, amount) {
  $('#qrcode').html('');
  if (amount > 0) {
    $('#qrcode').qrcode('cryptonite:'+address+'?amount='+amount);
  } else {
    $('#qrcode').qrcode('cryptonite:'+address);
  }
  $('#qrbox').css('display', 'inline-block');
}

function gen_qrcode() {
  draw_qrcode($('#addresses').val(), $('#amount').val());
}

function handle_load(response) {
  $('#addresses').html(response);
  if (get_add != 'n/a') {
    gen_qrcode();
  }
}

function get_addresses() {
  ajax_get('./inc/pages/jobs/listadds.inc.php', handle_load, 'select=1&address='+get_add);
}

$(document).ready(function() {
  get_addresses();
});
</script>

<?php } else { echo "error: unauthorized access"; } ?>