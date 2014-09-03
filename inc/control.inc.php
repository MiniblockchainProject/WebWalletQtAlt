<?php
if (isset($index_call)) {
  if ($login_state === 'valid') {
    if (!empty($page)) {
      if (file_exists("./inc/pages/$page.inc.php")) {
        require_once("./inc/pages/$page.inc.php");
      } else {
	    echo "<p>The requested page was not found, sorry! :(</p>";  
      }
    } else {
      require_once('./inc/pages/portal.inc.php');
    }
  } else {
	$page = isset($page) ? $page : 'login';
	$valid_pages = array('login', 'register', 'reset');
	if (!in_array($page, $valid_pages)) { $page = 'login'; }
    require_once("./inc/pages/$page.inc.php");
  }
} else {
  echo "error: invalid page access";
}
?>
