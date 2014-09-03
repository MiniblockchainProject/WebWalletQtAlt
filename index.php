<?php
$index_call = true;
require_once('inc/common.inc.php');

// log out admin user
if ($page == 'logout') {

  // clear the session and cookies
  session_unset();
  session_destroy();
  
  // NOW LOGGED OUT - goto login page
  redirect('./?page=login');
  exit;
}

require_once('inc/blocks/meta.inc.php');
?>
<body>

  <?php
  //include persistent menu
  require_once('inc/blocks/menu.inc.php');
  ?>
	
  <div id="wrapper">
  
    <?php
    //include persistent header
    require_once('inc/blocks/head.inc.php');

	// include page controller
	require_once('inc/control.inc.php');

	// include persistent footer
	require_once('inc/blocks/foot.inc.php');
	?>
	
  </div>

</body>
</html>
