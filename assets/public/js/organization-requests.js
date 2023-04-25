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
                data: 'username',
                name: 'username'
            },
            {
                data: 'usermail',
                name: 'usermail'
            },            
            {
                data: 'orgname',
                name: 'orgname'
            },
            {
                data: 'created',
                render: function(_,_, full) {
                  var created_at = full['created'];
                  var created_at = moment(created_at).format('DD MMM YYYY hh:mm A');
                  
                  if(created_at){
                    return created_at;                                        
                  }
                  return "";
                }
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'actions',
                name: 'actions'
            },
        ],
    });

    $('#myModal').on('hidden.bs.modal', function(e) {        
        $('.error').html("");        
        $('#forms')[0].reset();
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
                        $('.' + key).closest('.form-group').find('.error').html(res.message[key]);
                    });
                    $('#forms').find("." + first_input).focus();                    
                }
            },
            error: function(xhr) {                
                $this.find('button[type="submit"]').html(buttonSave);
                $this.find('button[type="submit"]').prop('disabled', false);
                toastr.error(xhr.responseJSON.message)
            }
        })
    })

    $(document).on('click', '.view-btn', function() {
        let id = $(this).attr('data-id');                                        
        $('#id_edit').val(id);        
        var status = $(this).attr('data-status-id');       

        $('#status').val(status).trigger('change');

        if(status == 1)
        {
            $('#status').prop('disabled', true);
            $("button[type='submit']").prop("disabled", true);
        }else if(status == 2)
        {
            $('#status').prop('disabled', true);
            $("button[type='submit']").prop("disabled", true);
        }
        else{
            $('#status').prop('disabled', false);
            $("button[type='submit']").prop("disabled", false);
        }        

        $.ajax({
          url: getRequestDetails,
          type: "post",
          data: {
            id: id,        
          },
          success: function(result) {
            if (result.status) {
              $('#user_note').val(result.detail.user_note);
              if(result.detail.owner_note)
                $('#owner_note').val(result.detail.owner_note);
            }
            else{
                console.warn('Failed');
            }
          },
          error: function(xhr) {        
          }
        })

    })
});
