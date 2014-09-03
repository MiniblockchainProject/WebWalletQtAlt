<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
} elseif (!isset($_POST['hex_hash']) || !isset($_SESSION[$_POST['hex_hash']])) {
  die("error: cannot locate transaction data");
}

$tx_info = $_SESSION[$_POST['hex_hash']];
echo "<div id='basic_info'><p>Confirm that the details below are correct:</p><p>";

foreach ($tx_info['outputs'] as $key => $value) {
  echo "send <b>".remove_ep($value)." $curr_code</b> to <b>$key</b><br />";
}

if (!empty($tx_info['msg'])) {
  echo "</p><p><b>Message:</b> ".$tx_info['msg'];
}

echo "</p><p><b>Miners fee:</b> ".float_format($tx_info['fee'])." $curr_code</p>";
echo "<b>Withdrawing:</b> ".float_format($tx_info['total'])." $curr_code</p>";
echo "<a class='float_right' href='#' onclick='toggle_info(1);'>Show Advanced</a></div>";

echo "<div class='no_display' id='adv_info'>";
echo "<p>Confirm that the details below are correct:</p>";
echo "<p><b>Inputs:</b><br />";

foreach ($tx_info['inputs'] as $key => $value) {
  echo "$key <span class='sad_txt'>".remove_ep($value)." $curr_code</span><br />";
}

echo "</p><p><b>Outputs:</b><br />";

foreach ($tx_info['outputs'] as $key => $value) {
  echo "$key <span class='happy_txt'>".remove_ep($value)." $curr_code</span><br />";
}

if (!empty($tx_info['msg'])) {
  echo "</p><p><b>Message:</b> ".$tx_info['msg'];
}

echo "</p><p><b>Miners fee:</b> ".float_format($tx_info['fee'])." $curr_code</p>";
echo "<b>Withdrawing:</b> ".float_format($tx_info['total'])." $curr_code</p>";
echo "<a class='float_right' href='#' onclick='toggle_info(0);'>Show Basic</a></div>";
?>
<script language="JavaScript">
function toggle_info(mode) {
  if (mode == 1) {
    $('#basic_info').hide();
    $('#adv_info').show();
  } else {
    $('#adv_info').hide();
    $('#basic_info').show();
  }
}
</script>