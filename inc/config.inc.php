<?php
// change level of php error reporting
$error_level = E_ALL;

// enable/disable rpc error reporting
$rpc_debug = true;

// install directory ('/' if installed at root)
$install_dir = '/';

// website title
$site_name = 'Cryptonite Web Wallet';

// set to random string then never change
$rand_str = 'CHANGETHISSTRING';

// default time zone used by server
$time_zone = 'US/Central';

// coin currency code
$curr_code = 'XCN';

// number of decimal places
$dec_count = 10;

// confs for available balance
$min_confs = 6;

// time between ajax calls (secs)
$timer_freq = 15;

// max number of login attempts
$fail_limit = 4;

// lock period if limit exceeded (mins)
$lock_timer = 120;

// logout if inactive too long (mins)
$logout_timer = 20;

// initial balance of coinbase account
$total_coin = '1844674407';

// initial block reward
$first_reward = '243.1';

// suggested minimum fee
$min_fee = '0.0000001';

// RPC client name
$rpc_client = 'cryptonited';

// RPC username
$rpc_user = '';

// RPC password
$rpc_pass = '';

// address of coinbase account
$cb_address = 'CGTta3M4t3yXu8uRgkKvaWd2d8DQvDPnpL';

// ignore crap under this line
$inter_prot = (empty($_SERVER['HTTPS'])) ? 'http://' : 'https://';
$base_url = $inter_prot.$_SERVER['HTTP_HOST'].$install_dir;
bcscale($dec_count);
ini_set('display_errors', 1); 
error_reporting($error_level);
date_default_timezone_set($time_zone);
?>
