<?php
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../config.inc.php');

session_start();

if (login_state() !== 'valid' || !post_get_check()) {
  die("error: unauthorized access");
}

if (!empty($_GET['delete'])) {

  $add_dir = "./../../../db/.".$_SESSION['account_id'].'-addbook';
  $add_book = @file_get_contents($add_dir);

  if ($add_book === false) {
	die('error: could not locate address book file');
  } else {
    $add_book = json_decode($add_book, true);
  }
  
  if (!empty($add_book)) {
    $address = preg_replace("/[^a-z0-9]/i", '', $_GET['delete']);
	unset($add_book['addresses'][$address]);
	if (file_put_contents($add_dir, json_encode($add_book))) {
      chmod($add_dir, 0600);
      echo "success";
    } else {
      echo  "error: unable to update address book";
    }
  } else {
	die('error: unable to open address book');
  }
  
} elseif (!empty($_POST['address'])) {

  $add_dir = "./../../../db/.".$_SESSION['account_id'].'-addbook';
  $add_book = @file_get_contents($add_dir);

  if ($add_book === false) {
    if (empty($_POST['newlabel'])) {
	  $add_book['addresses'] = $add_book = array();
	} else {
	  die('error: could not locate address book file');
	}
  } else {
    $add_book = json_decode($add_book, true);
  }
  
  if (!empty($add_book)) {
    if (!empty($_POST['addlabel'])) {
      $label = base64_encode($_POST['addlabel']);
    } elseif (!empty($_POST['newlabel'])) {
      $label = base64_encode($_POST['newlabel']);
    } else {
      die('error: label field was left empty');
    }
    $address = preg_replace("/[^a-z0-9]/i", '', $_POST['address']);
	if (!keytools::checkAddress($address)) {
	  die('error: address specified is not valid');
	}
	$add_book['addresses'][$address] = $label;
	if (file_put_contents($add_dir, json_encode($add_book))) {
      chmod($add_dir, 0600);
      echo "success";
    } else {
      echo  "error: unable to update address book";
    }
  } else {
	die('error: unable to open address book');
  }

} else {
  die("error: one or more form fields left empty");
}
?>