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
                data: 'organization_name',
                name: 'organization_name'
            },
            {
                data: 'short_name',
                name: 'organizations.short_name'
            },
            {
                data: 'email',
                name: 'organizations.email'
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

    $(document).on('click', '.request-btn', function() {
        let id = $(this).attr('data-id');                                        
        $('#id_edit').val(id);        
    })

});
