$(document).ready(function() {
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        fixedColumns: true,
        language: lang,
        ajax: {
            url: memberListUrl,
            type: 'GET'
        },
        columns: [{
                data: 'name',
                name: 'users.name'
            },
            {
                data: 'email',
                name: 'users.email'
            },
            {
                "searching": false,
                data: 'member_type',
                name: 'access_type'
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
        var buttonLoading = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> '+btnProcessing+'...';
        var buttonSave = 'Change';

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
                $this.find('button[type="submit"]').html(buttonSave);
                $this.find('button[type="submit"]').prop('disabled', false);
                toastr.error(xhr.responseJSON.message)
            }
        })
    })

    $(document).on('click', '.edit', function() {
        $('#forms').attr('action', changeMemTypeUrl);
        $('#myModalLabel').html('Edit Member type');
        let id = $(this).attr('id');
        let memType = $(this).attr('data-member');
        
        $('#btn-add').click();
        $('#id_edit').val(id);
        $('#member_type').val(memType).trigger('change');
            
    })


});
