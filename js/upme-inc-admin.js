jQuery(document).ready(function($) {
    
    $('#upme-display-inc-settings').click(function(){
        $('#upme-invitation-codes-settings').show();
        $('#upme-invitation-codes-create').hide();
        $('#upme-invitation-codes-notifications').hide();
    });
    
    $('#upme-display-inc-codes').click(function(){
        $('#upme-invitation-codes-settings').hide();
        $('#upme-invitation-codes-create').show();
        $('#upme-invitation-codes-notifications').hide();
    });
    
    $('#upme-display-inc-notifications').click(function(){
        $('#upme-invitation-codes-settings').hide();
        $('#upme-invitation-codes-create').hide();
        $('#upme-invitation-codes-notifications').show();
    });
    
    $('#add-upme-invitation-code').click(function(){
        var err = 0
        var err_msg = '';
        
        $('#add-upme-invitation-code').attr("disabled", "disabled");
    	$('#add-upme-invitation-code').val(UPMEINC_ADMIN.messages.savingIncCode);
        
        $('#upme-add-invitation-codes-settings-msg').html(err_msg).removeClass('error').hide();
        
        var upme_inc_code_id    = $('#upme_inc_code_id').val();
        var upme_inc_code_quota = $('#upme_inc_code_quota').val();
        
        var data = $('#upme-invitation-codes-create-form').serialize();
        
        if(upme_inc_code_id == ''){
            err++;
            err_msg += "<p>" + UPMEINC_ADMIN.messages.emptyInvCode + "</p>";
        }
        
        if(upme_inc_code_quota == ''){
            err++;
            err_msg += "<p>" + UPMEINC_ADMIN.messages.emptyInvQuota + "</p>";            
        }else if( ! $.isNumeric(parseInt(upme_inc_code_quota)) ){
            err++;
            err_msg += "<p>" + UPMEINC_ADMIN.messages.numericInvQuota + "</p>";            
        }
        
        if(err == 0){
            $.post(
		        UPMEINC_ADMIN.AdminAjax,
		        {
		            'action': 'upme_inc_save_invitation_codes',
		            'data':   data,
		        },
		        function(response){

		        	if(response.status == 'success'){
		        		$('#upme-modules-settings-saved').show();
	

	                    var htm = $('#upme_inc_codes_titles').clone().wrap('<div>').parent().html() + response.codes;

	                    $('#upme_inc_codes').html(htm);
		        	}else if(response.status == 'fail'){
                        $('#upme-add-invitation-codes-settings-msg').html("<p>"+response.msg+"</p>").addClass('error').show();
                        $('#upme_inc_codes').html("<p>"+htm+"</p>");
                    }

		        	// Reset form after adding a code
		        	$('#upme_inc_code_id').val('');
		        	$('#upme_inc_code_quota').val('');
		        	

		        	$('#add-upme-invitation-code').removeAttr("disabled");
    				$('#add-upme-invitation-code').val(UPMEINC_ADMIN.messages.saveIncCode);

		        },"json"
			);
        }else{
            
            $('#upme-add-invitation-codes-settings-msg').html(err_msg).addClass('error').show();
            
            $('#add-upme-invitation-code').removeAttr("disabled");
    		$('#add-upme-invitation-code').val(UPMEINC_ADMIN.messages.saveIncCode);
        }
    });
    
    $(document.body).on('click', '.upme_inc_delete_code',function(){
		var code_id = $(this).parent().find('#upme_inc_code_id').val();

		$.post(
	        UPMEINC_ADMIN.AdminAjax,
	        {
	            'action': 'upme_inc_delete_code',
	            'code_id':   code_id,
	        },
	        function(response){
	        	
	        	if(response.status == 'success'){
	        		$('#upme-modules-settings-saved').show();

                    var htm = $('#upme_inc_codes_titles').clone().wrap('<div>').parent().html() + response.codes;

                    $('#upme_inc_codes').html(htm);
	        	}
	        },"json"
		);
	});
    
    $('#send-upme-invitation-code').click(function(){
        var err = 0
        var err_msg = '';
        
        $('#send-upme-invitation-code').attr("disabled", "disabled");
    	$('#send-upme-invitation-code').val(UPMEINC_ADMIN.messages.sendingIncCode);
        
        $('#upme-send-invitation-code-settings-msg').html(err_msg).removeClass('error').hide();
        
        var upme_inc_send_code_id    = $('#upme_inc_send_code_id').val();
        var upme_inc_notify_emails = $('#upme_inc_notify_emails').val();
        
        var data = $('#upme-invitation-codes-notification-form').serialize();
        
        if(upme_inc_send_code_id == '0'){
            err++;
            err_msg += "<p>" + UPMEINC_ADMIN.messages.emptyInvCode + "</p>";
        }
        
        if(upme_inc_notify_emails.trim() == ''){
            err++;
            err_msg += "<p>" + UPMEINC_ADMIN.messages.emptyInvEmails + "</p>";            
        }
        
        if(err == 0){
            $.post(
		        UPMEINC_ADMIN.AdminAjax,
		        {
		            'action': 'upme_inc_send_invitation_codes',
		            'data':   data,
		        },
		        function(response){

		        	if(response.status == 'success'){
		        		$('#upme-modules-settings-saved').show();

		        	}else if(response.status == 'fail'){
                        $('#upme-send-invitation-code-settings-msg').html("<p>"+response.msg+"</p>").addClass('error').show();            
                    }

		        	// Reset form after adding a code
		        	$('#upme_inc_send_code_id').val(0).trigger("chosen:updated");
		        	$('#upme_inc_notify_emails').html('');
		        	

		        	$('#send-upme-invitation-code').removeAttr("disabled");
    				$('#send-upme-invitation-code').val(UPMEINC_ADMIN.messages.sendIncCode);

		        },"json"
			);
        }else{
            
            $('#upme-send-invitation-code-settings-msg').html(err_msg).addClass('error').show();
            
            $('#send-upme-invitation-code').removeAttr("disabled");
    		$('#send-upme-invitation-code').val(UPMEINC_ADMIN.messages.sendIncCode);
        }
    });

});