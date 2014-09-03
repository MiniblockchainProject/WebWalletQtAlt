<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

$add_dir = "./../../../db/.".$_SESSION['account_id'].'-addbook';
$add_book = @file_get_contents($add_dir);

if ($add_book !== false) {
  $add_book = json_decode($add_book, true);
  if (!empty($add_book)) {
    if (isset($_GET['table'])) {
	  echo "<table id='book_table' class='table table-hover'>\n".
	       "<tr><th>Address</th><th>Label</th><th>Actions</th></tr>";
	  foreach($add_book['addresses'] as $key => $value) {
?>
  <tr>
    <td><?php echo "<a href='./?page=search&amp;address=$key'>$key</a>"; ?></td>
    <td><?php safe_echo(base64_decode($value)); ?></td>
	<td>
      <div class="btn-group">
        <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
        Actions <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#" onclick="edit_label('<?php echo $key; ?>')">Edit Label</a></li>
		  <li><a href="#" onclick="del_entry('<?php echo $key; ?>')">Remove Entry</a></li>
        </ul>
      </div>
    </td>
  </tr>
<?php
	  }
	  echo "</table>";
	} else {
	  echo "<select id='book_select' class='expand'>\n";
	  foreach($add_book['addresses'] as $key => $value) {
	    echo "<option value='$key'>".safe_str(base64_decode($value))." - $key</option>\n";
	  }
	  echo "</select>";
	}
  } else {
    echo "<p>Unable to open address book</p>";
  }
} else {
  echo "<p>Address book is still empty</p>";
}
?>
