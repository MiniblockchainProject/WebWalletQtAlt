  function logout() {
    redirect('./?page=logout');
  }

  function hide_error() {
    if ($('.warning_well').is(':visible')) {
      $('.warning_well').fadeOut(500);
	}
  }

  function show_error(error_txt) {
    $('.warning_well').html(error_txt);
	if (!($('.warning_well').is(':visible'))) {
      $('.warning_well').fadeIn(500);
	}
  }

  function bind_form(form_id, call_type, targ_url, succ_func) {
    $('#'+form_id).ajaxForm({
      url: targ_url,
	  type: call_type,
	  success: function(response) {
	    succ_func(response);
	    setTimeout(hide_error, 3000);
	  },
	  error: function(e) {
	    show_error('error: cannot connect to the server!');
      }
    });
  }

  function ajax_call(call_type, targ_url, succ_func, call_data) {
	if ($.support.ajax != false) {
		$.ajax({
			url: targ_url,
			type: call_type,
			data: call_data,
			dataType: "html",
			cache: false,
			success: function(response) {
				succ_func(response);
				setTimeout(hide_error, 3000);
			},
			error: function(e) {
				show_error('error: cannot connect to the server!');
			}
		});
	} else {
	  show_error('error: web browser does not support ajax!');
      clearInterval(update_timer);
	}
  }
  
  function ajax_post(targ_url, succ_func, post_data) {
    ajax_call('POST', targ_url, succ_func, post_data);
  }

  function ajax_get(targ_url, succ_func, get_data) {
    ajax_call('GET', targ_url, succ_func, get_data);
  }

  function apply_update(target) {
    return function(response) {
      $('#'+target).html(response);
	}
  }
  
  function update_page() {
    if (login_state == 'valid') {
      ajax_get('./inc/pages/jobs/balance.inc.php', apply_update('balance'), '');
    }
    if (page_name == 'portal') {
      ajax_get('./inc/pages/jobs/overview.inc.php', apply_update('overview'), '');
      ajax_get('./inc/pages/jobs/listtrans.inc.php', apply_update('rectrans'), 'recent=1');
    }
  }