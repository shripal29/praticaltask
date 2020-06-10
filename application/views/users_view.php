<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Users List</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/bootstrap.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/jquery.dataTables.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/style.css' ?>">
    </head>
    <body>
        <div class="container">
            <div class="row users_listing">
                <!-- Page Heading -->
                <div class="row">
                    <h1 class="page-header">Users
                        <div class="pull-right">
                            <a href="javascript:void(0);" class="btn btn-success btn_add">Add New</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn_bulk_delete">Bulk Delete</a>
                        </div>
                    </h1>
                </div>
                <div id="reload">
                    <table class="table table-striped" id="mydata">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Contact No</th>
                                <th>Hobby</th>
                                <th>Category</th>
                                <th>Profile Pic</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="show_data">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 user_form" style="display:none">
                <h3>Add User</h3>
                <form class="form-horizontal" id="user_add_form" class="user_add_form" autocomplete="off">
                    <div class="form-group">
                        <label class="control-label col-xs-3">Name</label>
                        <div class="col-xs-9">
                            <input name="u_name" id="u_name" class="form-control" type="text" placeholder="Name" required="" maxlength="255" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-3">Contact no</label>
                        <div class="col-xs-9">
                            <input name="u_contactno" id="u_contactno" class="form-control" type="text" placeholder="Contact no" required="" minlength="12" maxlength="12" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-3">Hobby</label>
                        <div class="col-xs-9 controls">
                            <input type='checkbox' name='u_hobbies[]' value='Programming' class="hobbies" /> Programming
                            <br><input type='checkbox' name='u_hobbies[]' value='Games' class="hobbies" /> Games
                            <br><input type='checkbox' name='u_hobbies[]' value='Reading' class="hobbies" /> Reading
                            <br><input type='checkbox' name='u_hobbies[]' value='Photography' class="hobbies" /> Photography
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-3">Category</label>
                        <div class="col-xs-9">
                            <select name="u_category_id" id="u_category_id" class="form-control" required="">
                                <option value="">Select Category</option>
                                <?php
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category->c_id . '">' . $category->c_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-3">Profile pic</label>
                        <div class="col-xs-9">
                            <input name="u_image" id="u_image" type="file" accept="image/*" />
                        </div>
                    </div>

                    <div class="pull-right">
                        <button class="btn btn-primary btn_submit" id="btn_submit">Save</button>
                        <button type="button" class="btn btn_cancel" id="btn_cancel">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="form-horizontal" id="user_edit_form" class="user_edit_form" autocomplete="off">
                        <div class="modal-body">
                            <input name="edit_u_id" id="edit_u_id" type="hidden" />
                            <div class="form-group">
                                <label class="control-label col-xs-3">Name</label>
                                <div class="col-xs-9">
                                    <input name="edit_u_name" id="edit_u_name" class="form-control" type="text" placeholder="Name" required="" maxlength="255" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3">Contact no</label>
                                <div class="col-xs-9">
                                    <input name="edit_u_contactno" id="edit_u_contactno" class="form-control" type="text" placeholder="Contact no" required="" minlength="12" maxlength="12" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3">Hobby</label>
                                <div class="col-xs-9 controls">
                                    <input type='checkbox' name='edit_u_hobbies[]' value='Programming' class="edit_hobbies hobbies_1" /> Programming
                                    <br><input type='checkbox' name='edit_u_hobbies[]' value='Games' class="edit_hobbies hobbies_2" /> Games
                                    <br><input type='checkbox' name='edit_u_hobbies[]' value='Reading' class="edit_hobbies hobbies_3" /> Reading
                                    <br><input type='checkbox' name='edit_u_hobbies[]' value='Photography' class="edit_hobbies hobbies_4" /> Photography
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3">Category</label>
                                <div class="col-xs-9">
                                    <select name="edit_u_category_id" id="edit_u_category_id" class="form-control" required="">
                                        <option value="">Select Category</option>
                                        <?php
                                        foreach ($categories as $category) {
                                            echo '<option value="' . $category->c_id . '">' . $category->c_name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3">Profile pic</label>
                                <div class="col-xs-9">
                                    <input name="edit_u_image" id="edit_u_image" type="file" accept="image/*" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn_update">Update</button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>

        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.js' ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.validate.js' ?>"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.dataTables.js' ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/page-js/users/index.js' ?>"></script>

        <script>
            var users_url = "<?php echo base_url() ?>users/";
        </script>

    </body>
</html>