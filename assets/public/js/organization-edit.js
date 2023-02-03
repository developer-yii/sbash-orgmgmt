$(document).ready(function() {
  var path = window.location.href;

	$(document).on('click','.cancel-btn', function(e){
		e.preventDefault();		
		window.location.reload();
	});	

	$(document).on('keyup','#short_name',function(e){
		e.preventDefault();
		var name = $(this).val();
			
		$.ajax({
      url: checkNameUrl,
      type: "post",
      data: {
        name: name,        
      },
      success: function(result) {
        if (result.status) {
          if(result.available)
          {
          	$('#short_name_available').prop('checked', true).trigger('change');
          	$('.short_name_error').html('');
          }
          else
          {
          	$('#short_name_available').prop('checked', false).trigger('change');
          }
        }
        else{
        	console.warn('Failed');
        }
      },
      error: function(xhr) {        
      }
    })
	});

	$('#logo').change(function(){
	    var cHtml = '<img id="preview-image" src="" alt="preview image" style="max-height: 100px;">';
	    $('.upload_file').remove();
	    $('#img-prv').html(cHtml);
	     
	    let reader = new FileReader();
	    reader.onload = (e) => { 
	      $('#preview-image').attr('src', e.target.result); 
	    }
	    reader.readAsDataURL(this.files[0]);           
	});


	$(document).on('submit', '#setting_form', function(event) {
      event.preventDefault();
      $this = $(this);

      
      if(!($('#short_name_available').is(':checked')))
			{
				$('.short_name_error').html(short_name_error);
				return;
			}		
			$('.short_name_error').html('');
			
      $.ajax({
        url: editUpdateUrl,
        type: 'POST',
        typeData: "JSON",
        data: new FormData(this),
        processData: false,
        contentType: false,

        beforeSend: function() {
            $this.find('button[type="submit"]').prop('disabled', true);
        },
        success: function(res, status) {
          $this.find('button[type="submit"]').prop('disabled', false);
          if (res.status == true) {                                    
          	$('.error').html("");
            toastr.success(res.message, successMsg);
            $('#setting_form')[0].reset();
            setTimeout(function(){               
              window.location.href = path;
            }, 1500);
          }          
          else{            
            first_input = "";
            $('.error').html("");           
            
            $.each(res.message, function(key) {
                if(first_input==""){first_input=key};
                $('#'+key).closest('.form-group').find('.error').html(res.message[key]);
            });
            $('#setting_form').find("#"+first_input).focus();
          }

        },
        error: function(xhr) {
          $this.find('button[type="submit"]').prop('disabled', false);
          toastr.error(xhr.responseJSON.message, errIcon)
        }
      })
    })
});