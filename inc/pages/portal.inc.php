<?php if (login_state() === 'valid') { ?>

  <h1>Overview</h1>

  <div id="overview" class="row">
    <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
  </div>

  <hr />
  <h1>Recent Transactions</h1>
  <br />

  <div id="rectrans">
    <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
  </div>
  
<?php } else { echo "error: unauthorized access"; } ?>