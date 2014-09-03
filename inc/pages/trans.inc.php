<?php if (login_state() === 'valid') {
  $page = (empty($_GET['p'])) ? 1 : (int)$_GET['p'];
  if ($page < 1) { $page = 1; }
?>
<div class="pagination float_right">
  <ul>
    <?php
	  $dclass = ($page < 2) ? ' class="disabled"' : '';
	  echo "<li$dclass><a href='./?page=trans&amp;p=".($page-1)."'>Prev</a></li>";
	  $pcount = 0;
	  for ($i=$page-2;$i<$page;$i++) {
	    if ($i > 0) {
		  $class = ($i==$page) ? " class='active'" : '';
		  echo "<li$class><a href='./?page=trans&amp;p=$i'>$i</a></li>";
		  $pcount++;
		}
	  }
	  for ($i=$page;$pcount<5;$i++) {
	    $class = ($i==$page) ? " class='active'" : '';
	    echo "<li$class><a href='./?page=trans&amp;p=$i'>$i</a></li>";
		$pcount++;
	  }
	?>
    <li><a href="./?page=trans&amp;p=<?php echo $page+1; ?>">Next</a></li>
  </ul>
</div>
  
<h1>Transaction History</h1><br />
<div id="tran_list">
  <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
</div>
  
<script language="JavaScript">
var page = <?php echo $page; ?>;

function handle_load(response) {
  $('#tran_list').html(response);
}

function get_history() {
  ajax_get('./inc/pages/jobs/listtrans.inc.php', handle_load, 'history='+page);
}

$(document).ready(function() {
  get_history();
});
</script>
  
<?php } else { echo "error: unauthorized access"; } ?>