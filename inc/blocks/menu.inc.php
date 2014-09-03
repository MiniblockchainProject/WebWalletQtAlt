    <div class="well" id="menu_box">
        <div class="navbar navbar-default" style="margin-bottom:0px;">

            <div class="container" style="width: auto;">
                <div class="navbar-collapse">
                    <ul class="nav navbar-nav">
				        <li class="dropdown">
				        	<a href="#" class="dropdown-toggle" 
							data-toggle="dropdown">Account <b class="caret"></b></a>
							<?php if ($login_state === 'valid') { ?>
				        	<ul class="dropdown-menu">
				            	<li><a href="./">Overview</a></li>
				            	<li><a href="./?page=config">Settings</a></li>
				            	<li><a href="./?page=logout">Logout</a></li>
				        	</ul>
							<?php } ?>
				        </li>
				        <li class="dropdown">
				        	<a href="#" class="dropdown-toggle" 
							data-toggle="dropdown">Transfer <b class="caret"></b></a>
							<?php if ($login_state === 'valid') { ?>
				        	<ul class="dropdown-menu">
				            	<li><a href="./?page=send">Send</a></li>
				            	<li><a href="./?page=receive">Receive</a></li>
				            	<li><a href="./?page=trans">History</a></li>
				        	</ul>
							<?php } ?>
				        </li>
				        <li class="dropdown">
				        	<a href="#" class="dropdown-toggle" 
							data-toggle="dropdown">Addresses <b class="caret"></b></a>
							<?php if ($login_state === 'valid') { ?>
				        	<ul class="dropdown-menu">
				            	<li><a href="./?page=addresses">My Addresses</a></li>
				            	<li><a href="./?page=addbook">Address Book</a></li>
				            	<li><a href="./?page=impexp">Import / Export</a></li>
				        	</ul>
							<?php } ?>
				        </li>
				        <li class="dropdown">
				        	<a href="#" class="dropdown-toggle" 
							data-toggle="dropdown">Utilities <b class="caret"></b></a>
							<?php if ($login_state === 'valid') { ?>
				        	<ul class="dropdown-menu">
				            	<li><a href="./?page=search">Search Blockchain</a></li>
				            	<li><a href="./?page=multisig">Multi-Sig Tools</a></li>
				            	<li><a href="./?page=signver">Sign / Verify</a></li>
				        	</ul>
							<?php } ?>
				        </li>
                    </ul>
					<?php if ($login_state === 'valid') { ?>
					<div class="pull-right" id="logout_btn">
					  <a href="./?page=logout" title="logout"><img src="./img/logout.png" alt="logout" /></a>
					</div>
                    <div class="pull-right" id="balance"></div>
                    <?php } else { ?>
                    <div class="pull-right" id="user_links">
						<a href="./?page=login">login</a> | <a href="./?page=register">register</a>
					</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
