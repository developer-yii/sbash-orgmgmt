$(document).ready(function() {
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        fixedColumns: true,
        language: lang,
        order: [[0, 'asc']],
        ajax: {
            url: memberListUrl,
            type: 'GET',
            error: function(xhr, textStatus, error) {            
                if (xhr.status == 401) {
                    alert('Your session has expired. Please refresh the page.');
                } else {
                    alert('An error occurred while processing your request.');
                }
            }
        },
        columns: [{
                data: 'name',
                name: 'users.name',
            },
            {
                data: 'email',
                name: 'users.email'
            },
            {
                data: 'member_type',
                name: 'member_type'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false
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
        $('#myModalLabel').html(editMemberTypeText);
        let id = $(this).attr('id');
        let memType = $(this).attr('data-member');
        
        $('#btn-add').click();
        $('#id_edit').val(id);
        $('#member_type').val(memType).trigger('change');
            
    })

    $(document).on('click', '.remove', function() {
        let id = $(this).attr('id');
        
        Swal.fire({
            title: alert1,
            text: alertsub,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: cancelText,
            confirmButtonText: confirmText
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: removeMemberUrl,
                type: "post",
                data: {
                    id: id,       
                    _token: $('meta[name="_token"]').attr('content')         
                },
                success: function(res, status) {
                    if(res.status == true) {
                        toastr.success(res.message);
                        $("#datatable").DataTable().ajax.reload();                
                    }
                    if(res.status == false)
                    {
                        toastr.error(res.message, ErrorText);
                    }
                },
                error: function(xhr) {        
                    if (xhr.status === 419) {
                        window.location.reload();
                        return;
                    }      
                    if(xhr.status !== 0)
                    {
                        toastr.error(xhr.responseJSON.message, ErrorText);              
                    }
                }
            }) // ajax end
        } // confirm end
        }) // promise end
    }) // click event end

});
