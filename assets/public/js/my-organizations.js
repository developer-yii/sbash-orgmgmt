$(document).ready(function() {
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        fixedColumns: true,
        language: lang,
        ajax: {
            url: organizationGetUrl,
            type: 'GET'
        },
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'short_name',
                name: 'short_name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'email_forward',
                name: 'email_forward'
            },            
            {
                data: 'created_at',
                render: function(_,_, full) {
                  var created_at = full['created_at'];
                  var created_at = moment(created_at).format('DD MMM YYYY hh:mm A');
                  
                  if(created_at){
                    return created_at;                                        
                  }
                  return "";
                }
            },
            {
                data: 'actions',
                name: 'actions'
            },
        ],
    });

    $('#myModal').on('hidden.bs.modal', function(e) {        
        $('.error').html("");        
        $('#edit_id').val(0);
        $('#setting_form')[0].reset();
        $('#myModalLabel').html('Add Organization');
        CKEDITOR.instances.default_footer.setData('');
        $('#uploadImages').html('');
        $('#img-prv').html('');
        $('#public_page').html('');
        $('#email').html('');
    })

    $(document).on('submit', '#forms', function(event) {
        event.preventDefault();
        var $this = $(this);
        var buttonLoading = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> '+invitingText+'...';
        var buttonSave = inviteText;

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            typeData: "JSON",
            data: $('#forms').serialize(),            
            beforeSend: function() {
              $this.find('button[type="submit"]').html(buttonLoading);
              $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(res) {
                $this.find('button[type="submit"]').html(buttonSave);
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (res.status == true) {
                    $('#forms')[0].reset();
                    $('#btn-close').click();
                    toastr.success(res.message);
                    $("#datatable").DataTable().ajax.reload();
                    $('.error').html("");
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(res.message, function(key) {
                        if (first_input == "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(res.message[key]);
                    });
                    $('#forms').find("#" + first_input).focus();
                }
            },
            error: function(xhr) {
                if (xhr.status === 419) {
                    window.location.reload();
                    return;
                }
                if (xhr.status === 554) {
                    toastr.error("Email address not verified. Please check your email address and try again.");
                    return;
                }
                $this.find('button[type="submit"]').html(buttonSave);
                $this.find('button[type="submit"]').prop('disabled', false);
                toastr.error(xhr.responseJSON.message)
            }
        })
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
            if (xhr.status === 419) {
                window.location.reload();
                return;
            }      
          }
        })
    });

    $('#logo').change(function(){

        var file = this.files[0];
    
        // Check if the selected file is an image
        if (!file.type.startsWith('image/')) {
            // Handle the case where the selected file is not an image
            $(this).closest('.form-group').find('.error').html(select_image_file);

            // Reset the file input
            $('#logo').val('');
            
            // Remove the file name from the input field
            $('.custom-file-label').text('Choose file');
            
            return;            
        }
        // clear error if any
        $(this).closest('.form-group').find('.error').html('');

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
            url: addUpdateUrl,
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
                $('#myModal').modal('hide');
                $("#datatable").DataTable().ajax.reload();
                if(!$('#edit_id').val())
                {                    
                    setTimeout(function(){ location.reload() }, 1500);
                }
              }          
              else{            
                first_input = "";
                $('.error').html("");           
                
                $.each(res.message, function(key) {
                    if(first_input==""){first_input=key};
                    $('#'+key).closest('.form-group').find('.error').html(res.message[key]);
                });
                // $('#setting_form').find("#"+first_input).focus();
              }

            },
            error: function(xhr) {
              $this.find('button[type="submit"]').prop('disabled', false);
              toastr.error(xhr.responseJSON.message, errIcon)
            }
          })
    })

    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#edit_id').val(id);
        $('#myModalLabel').html('Edit Organization');

        $.ajax({
            url: detailUrl+'?id='+id,
            type: 'GET',
            dataType: 'json',
            success: function(result) {                
                if(result.status){                            
                    $('#setting_form').find('#name').val(result.detail.name);
                    $('#setting_form').find('#short_name').val(result.detail.short_name);
                    var publicpages = url + '/events/' + result.detail.short_name;                    
                    $('#public_page').html(publicpages);
                    $('#email').html(result.detail.email);
                    $('#email_forward').val(result.detail.email_forward);
                    $('#default_footer').val(result.detail.default_footer);
                    CKEDITOR.instances.default_footer.setData( result.default_footer );
                    if(result.detail.logo)
                    {
                        var htm = '';
                        var src = logoUrl + '/' + result.detail.logo;
                        htm += '<img id="preview-image" src="'+src+'" alt="preview image" style="max-height: 100px;">';
                        $('#img-prv').html(htm);
                    }
                    if(result.detail.double_optin)
                    {
                        $('#double_optin').prop('checked',true).trigger('change');
                    }                    

                    $('#myModal').modal('show');
                }
            },
            error: function(xhr) {                
                toastr.error(xhr.responseJSON.message)
            }
        });

    })

});
