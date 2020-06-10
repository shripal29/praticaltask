"use strict";

function get_all_users() {
    $.ajax({
        type: 'GET',
        url: users_url + "get_all_users",
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            for (i = 0; i < data.length; i++) {
                html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><input type="checkbox" class="delete_multiple" value="' + data[i].u_id + '"></td>' +
                        '<td>' + data[i].u_name + '</td>' +
                        '<td>' + data[i].u_contactno + '</td>' +
                        '<td>' + data[i].u_hobbies + '</td>' +
                        '<td>' + data[i].c_name + '</td>' +
                        '<td><img src="' + data[i].u_url + '" height="50" width="50" /></td>' +
                        '<td style="text-align:right;">' +
                        '<a href="javascript:;" class="btn btn-primary user_edit" data-id="' + data[i].u_id + '">Edit</a>' + ' ' +
                        '<a href="javascript:;" class="btn btn-danger user_delete" data-id="' + data[i].u_id + '">Delete</a>' +
                        '</td>' +
                        '</tr>';
            }
            $('#show_data').html(html);
        }

    });
}


$(document).ready(function () {
    get_all_users();
    $('#mydata').dataTable({
        "bSort": false,
        "searching": false,
        "paging": false,
        "info": false
    });

    //Show user form
    $('.btn_add').on('click', function () {
        $("#u_name").val('');
        $("#u_contactno").val('');
        $("#u_category_id").val('');
        $("#u_image").val('');
        $(".hobbies").prop('checked', false);
        $('#btn_submit').prop('disabled', false);

        $(".users_listing").css("display", "none");
        $(".user_form").css("display", "block");
    });

    //Show user listing
    $('.btn_cancel').on('click', function () {
        $(".user_form").css("display", "none");
        $(".users_listing").css("display", "block");
    });

    jQuery.validator.methods.matches = function (value, element, params) {
        var re = new RegExp(params);
        return this.optional(element) || re.test(value);
    }

    jQuery.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 1 MB');

    $(document).find("#user_add_form").validate({
        rules: {
            u_name: {
                required: true
            },
            u_contactno: {
                required: true,
                matches: "[0-9]",
                minlength: 10,
                maxlength: 12
            },
            u_category_id: {
                required: true
            },
            'u_hobbies[]': {
                required: true
            },
            u_image: {
                required: true,
                extension: "jpg|jpeg|png",
                filesize: 1000000
            }
        },
        messages: {
            u_name: "Please enter name",
            u_contactno: "Please enter valid contact no",
            u_category_id: "Please select category",
            'u_hobbies[]': "Please select hobby",
            u_image: "Please select valid image and less than 1 MB"
        },
        highlight: function (label) {
            $(label).closest('.form-group').addClass('error');
        },
        success: function (label) {
            $(label).closest('.form-group').removeClass('error');
        }
    });

    $(document).find("#user_edit_form").validate({
        rules: {
            edit_u_name: {
                required: true
            },
            edit_u_contactno: {
                required: true,
                matches: "[0-9]",
                minlength: 10,
                maxlength: 12
            },
            edit_u_category_id: {
                required: true
            },
            'edit_u_hobbies[]': {
                required: true
            },
            edit_u_image: {
                extension: "jpg|jpeg|png",
                filesize: 1000000
            }
        },
        messages: {
            edit_u_name: "Please enter name",
            edit_u_contactno: "Please enter valid contact no",
            edit_u_category_id: "Please select category",
            'edit_u_hobbies[]': "Please select hobby",
            edit_u_image: "Please select valid image and less than 1 MB"
        },
        highlight: function (label) {
            $(label).closest('.form-group').addClass('error');
        },
        success: function (label) {
            $(label).closest('.form-group').removeClass('error');
        }
    });
    
    $(document).on('click', '#btn_submit', function (e) {
        e.preventDefault();
        if ($("#user_add_form").valid()) {
            $(this).prop('disabled', true);
            var form_data = new FormData($('#user_add_form')[0]);
            $.ajax({
                type: 'POST',
                url: users_url + "add_user",
                processData: false,
                contentType: false,
                async: false,
                cache: false,
                data: form_data,
                dataType: 'json',
                success: function (response) {
                    $(this).prop('disabled', false);
                    if (response.status == false) {
                        swal(response.message, {
                            icon: 'error',
                        });
                    }

                    if (response.status == true) {
                        swal(response.message, {
                            icon: 'success',
                        });

                        setTimeout(function () {
                            get_all_users();
                            $(".user_form").css("display", "none");
                            $(".users_listing").css("display", "block");
                        }, 1000);
                    }
                },
                error: function () {
                    $(this).prop('disabled', false);
                    swal("Problem while performing your action", {
                        icon: 'info',
                    });
                }
            });
        }
    });

    $(document).on('click', '.user_edit', function () {
        var id = jQuery(this).attr('data-id');
        if (!isNaN(id)) {

            $.ajax({
                url: users_url + "get_user_data",
                type: "POST",
                data: {
                    'id': id,
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.status == true) {
                        $("#edit_user_modal").modal('show');
                        $("#edit_u_id").val(id);
                        $("#edit_u_name").val(response.data.u_name);
                        $("#edit_u_contactno").val(response.data.u_contactno);
                        $("#edit_u_category_id").val(response.data.u_category_id);
                        $("#edit_u_image").val('');
                        $(".edit_hobbies").prop('checked', false);
                        $('#btn_update').prop('disabled', false);

                        $.each(response.data.u_hobbies, function (index, value) {
                            if ($.trim(value) == "Programming") {
                                $(".hobbies_1").prop('checked', true);
                            }

                            if ($.trim(value) == "Games") {
                                $(".hobbies_2").prop('checked', true);
                            }

                            if ($.trim(value) == "Reading") {
                                $(".hobbies_3").prop('checked', true);
                            }

                            if ($.trim(value) == "Photography") {
                                $(".hobbies_4").prop('checked', true);
                            }
                        });

                    } else {
                        swal(response.message, {
                            icon: 'error',
                        });
                    }
                },
                error: function () {
                    swal("Problem in performing your action.", {
                        icon: 'info',
                    });
                }

            });
        }
        else {
            swal("Problem in performing your action.", {
                icon: 'info',
            });
        }
    });

    //update data
    $(document).on('click', '#btn_update', function (e) {
        e.preventDefault();
        if ($(document).find("#user_edit_form").valid()) {
            $(this).prop('disabled', true);
            var form_data = new FormData($('#user_edit_form')[0]);
            $.ajax({
                type: 'POST',
                url: users_url + "update_user",
                processData: false,
                contentType: false,
                async: false,
                cache: false,
                data: form_data,
                dataType: 'json',
                success: function (response) {
                    $(this).prop('disabled', false);
                    if (response.status == false) {
                        swal(response.message, {
                            icon: 'error',
                        });
                    }

                    if (response.status == true) {
                        swal(response.message, {
                            icon: 'success',
                        });

                        setTimeout(function () {
                            $("#edit_user_modal").modal('hide');
                            get_all_users();
                        }, 1000);
                    }
                },
                error: function () {
                    $(this).prop('disabled', false);
                    swal("Problem while performing your action", {
                        icon: 'info',
                    });
                }
            });
        }
    });

    // delete data
    $(document).on('click', '.user_delete', function () {
        var id = jQuery(this).attr('data-id');
        if (!isNaN(id)) {
            swal({
                title: 'Are you sure you want to delete this user?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(function (willDelete) {
                if (willDelete) {
                    jQuery.ajax({
                        "url": users_url + 'delete_user',
                        type: "POST",
                        data: {
                            'id': id,
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            if (response.status == true) {
                                swal(response.message, {
                                    icon: 'success',
                                });

                                setTimeout(function () {
                                    get_all_users();
                                }, 1000);
                            } else {
                                swal(response.message, {
                                    icon: 'error',
                                });
                            }
                        },
                        error: function () {
                            swal("Problem in performing your action.", {
                                icon: 'info',
                            });
                        }
                    });
                }
            });
        }
        else {
            swal("Problem in performing your action.", {
                icon: 'info',
            });
        }
    });

    $(document).on('click', '.btn_bulk_delete', function () {
        var selected_ids = [];
        $(".delete_multiple").each(function () {
            if (jQuery(this).is(":checked")) {
                selected_ids.push(jQuery(this).val());
            }
        });

        if (selected_ids.length > 0) {
            swal({
                title: 'Are you sure you want to delete selected record?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(function (willDelete) {
                if (willDelete) {
                    jQuery.ajax({
                        "url": users_url + 'delete_user',
                        type: "POST",
                        data: {
                            'id': selected_ids,
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            if (response.status == true) {
                                swal(response.message, {
                                    icon: 'success',
                                });
                                setTimeout(function () {
                                    get_all_users();
                                }, 1000);

                            } else {
                                swal(response.message, {
                                    icon: 'error',
                                });
                            }
                        },
                        error: function () {
                            swal("Problem in performing your action.", {
                                icon: 'info',
                            });
                        }
                    });
                }
            });
        }
        else {
            swal("Please select at least one user for delete users.", {
                icon: 'info',
            });
        }
    });
});		