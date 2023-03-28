$(document).ready(function() {
	$(document).on('submit', '#invite_form', function(event) {
      event.preventDefault();
      $this = $(this);
      var buttonLoading = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>'+invitingText;
      var buttonSave = inviteText;
		
      $.ajax({
        url: inviteUrl,
        type: 'POST',
        data: $('#invite_form').serialize(),
        dataType: 'json',       

        beforeSend: function() {
        	$this.find('button[type="submit"]').html(buttonLoading);
            $this.find('button[type="submit"]').prop('disabled', true);
        },
        success: function(result) {
          $this.find('button[type="submit"]').html(buttonSave);
          $this.find('button[type="submit"]').prop('disabled', false);
          if (result.status == true) {                                    
          	$('.error').html("");
            toastr.success(result.message, successMsg);
            $('#invite_form')[0].reset();            
          }          
          else{            
            first_input = "";
            $('.error').html("");           
            
            $.each(result.message, function(key) {
                if(first_input==""){first_input=key};
                $('#'+key).closest('.form-group').find('.error').html(result.message[key]);
            });
            $('#invite_form').find("#"+first_input).focus();
          }
        },
        error: function(error) {
          if (error.status === 419) {
              window.location.reload();
              return;
          }
        	$this.find('button[type="submit"]').html(buttonSave);
          	$this.find('button[type="submit"]').prop('disabled', false);
            toastr.error(error.responseJSON.message, errIcon)          	
        }
      })
    })
});