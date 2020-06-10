<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    var $user_image_path;
    var $user_image_url;

    function __construct() {
        parent::__construct();
        $this->user_image_path = realpath(APPPATH . '../uploads');
        $this->user_image_url = base_url() . 'uploads/';
        $this->load->model('user_model');
        $this->load->model('category_model');
    }

    public function index() {
        $categories = $this->category_model->categories_list();
        $data['categories'] = $categories;
        $this->load->view('users_view', $data);
    }

    function get_all_users() {
        $data = $this->user_model->users_list();
        foreach ($data as $datakey => $datarow) {
            $user_image = $this->user_image_path . "/users/" . $datarow->u_id . "/" . $datarow->u_image;
            if (file_exists($user_image) && !empty($datarow->u_image)) {
                $data[$datakey]->u_url = $this->user_image_url . "users/" . $datarow->u_id . "/" . $datarow->u_image;
            } else {
                $data[$datakey]->u_url = $this->user_image_url . "user.png";
            }
        }
        echo json_encode($data);
    }

    function add_user() {

        if ($this->input->post()) {
            $this->form_validation->set_rules('u_name', 'Name', 'required|max_length[255]');
            $this->form_validation->set_rules('u_contactno', 'Contact no', 'required|min_length[10]|max_length[12]');
            $this->form_validation->set_rules('u_hobbies[]', 'Hobby', 'required');
            $this->form_validation->set_rules('u_category_id', 'Category', 'required');
            if (empty($_FILES['u_image']['name'])) {
                $this->form_validation->set_rules('u_image', 'Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->msg['status'] = false;
                $this->msg['message'] = strip_tags(validation_errors());
                echo json_encode($this->msg);
                exit;
            } else {

                if (!empty($_FILES['u_image']['name'])) {
                    $file_name = $_FILES['u_image']['name'];

                    $allowed_file_types = array('image/jpeg', 'image/jpg', 'image/png');
                    $file_type = $_FILES['u_image']['type'];

                    if (!in_array($file_type, $allowed_file_types)) {
                        $this->msg['status'] = false;
                        $this->msg['message'] = 'This file type is not allowed';
                        echo json_encode($this->msg);
                        exit;
                    }

                    if ($_FILES['u_image']['size'] > 1048576) {
                        $this->msg['status'] = false;
                        $this->msg['message'] = 'Image size Exeeds';
                        echo json_encode($this->msg);
                        exit;
                    }
                }

                $u_name = $this->input->post("u_name");
                $u_contactno = "+" . $this->input->post("u_contactno");
                $u_category_id = $this->input->post("u_category_id");
                $u_hobbies = implode(", ", $this->input->post("u_hobbies"));
                $u_hobbies = rtrim($u_hobbies, ", ");

                $insert_array = array(
                    'u_name' => $u_name,
                    'u_contactno' => $u_contactno,
                    'u_category_id' => $u_category_id,
                    'u_hobbies' => $u_hobbies,
                    'u_created_at' => date("Y-m-d H:i:s")
                );
                $inserted_id = $this->user_model->insert('users', $insert_array);

                if ($inserted_id) {
                    if (!empty($_FILES['u_image']['name'])) {

                        //check user folder is exists or not
                        $user_folder_path = $this->user_image_path . "/users";
                        if (!file_exists($user_folder_path)) {
                            mkdir($user_folder_path, 0777, true);
                            chmod($user_folder_path, 0777);
                        }

                        $userimage_folder_path = $this->user_image_path . "/users/" . $inserted_id;
                        if (!file_exists($userimage_folder_path)) {
                            mkdir($userimage_folder_path, 0777, true);
                            chmod($userimage_folder_path, 0777);
                        }

                        $config['upload_path'] = $userimage_folder_path;
                        $config['file_name'] = $_FILES['u_image']['name'];
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'jpg|jpeg|png';
                        $config["max_size"] = 1024;
                        $this->load->library('upload', $config);

                        if ($this->upload->do_upload('u_image')) {
                            $update_array = array(
                                'u_image' => $_FILES['u_image']['name'],
                            );

                            $where_array = array(
                                'u_id' => $inserted_id
                            );

                            $is_update = $this->user_model->update('users', $update_array, $where_array);
                        }
                    }
                }

                $this->msg['status'] = true;
                $this->msg['message'] = "User added successfully.";
                echo json_encode($this->msg);
                exit;
            }
        } else {
            $this->msg['status'] = false;
            $this->msg['message'] = "Something error occur.";
            echo json_encode($this->msg);
            exit;
        }
    }
    
    function update_user() {

        if ($this->input->post()) {
            $this->form_validation->set_rules('edit_u_id', 'ID', 'required');
            $this->form_validation->set_rules('edit_u_name', 'Name', 'required|max_length[255]');
            $this->form_validation->set_rules('edit_u_contactno', 'Contact no', 'required|min_length[10]|max_length[12]');
            $this->form_validation->set_rules('edit_u_hobbies[]', 'Hobby', 'required');
            $this->form_validation->set_rules('edit_u_category_id', 'Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->msg['status'] = false;
                $this->msg['message'] = strip_tags(validation_errors());
                echo json_encode($this->msg);
                exit;
            } else {

                if (!empty($_FILES['edit_u_image']['name'])) {
                    $file_name = $_FILES['edit_u_image']['name'];

                    $allowed_file_types = array('image/jpeg', 'image/jpg', 'image/png');
                    $file_type = $_FILES['edit_u_image']['type'];

                    if (!in_array($file_type, $allowed_file_types)) {
                        $this->msg['status'] = false;
                        $this->msg['message'] = 'This file type is not allowed';
                        echo json_encode($this->msg);
                        exit;
                    }

                    if ($_FILES['edit_u_image']['size'] > 1048576) {
                        $this->msg['status'] = false;
                        $this->msg['message'] = 'Image size Exeeds';
                        echo json_encode($this->msg);
                        exit;
                    }
                }

                $user_id = $this->input->post("edit_u_id");
                $u_name = $this->input->post("edit_u_name");
                $u_contactno = "+" . $this->input->post("edit_u_contactno");
                $u_category_id = $this->input->post("edit_u_category_id");
                $u_hobbies = implode(", ", $this->input->post("edit_u_hobbies"));
                $u_hobbies = rtrim($u_hobbies, ", ");
                
                $get_user = $this->user_model->getuser($user_id);
                if (count($get_user) == 0) {
                    $this->msg['status'] = false;
                    $this->msg['message'] = 'User not found.';
                    echo json_encode($this->msg);
                    exit;
                }

                $update_array = array(
                    'u_name' => $u_name,
                    'u_contactno' => $u_contactno,
                    'u_category_id' => $u_category_id,
                    'u_hobbies' => $u_hobbies,
                    'u_created_at' => date("Y-m-d H:i:s")
                );

                $where_array = array(
                    'u_id' => $user_id
                );

                $this->user_model->update('users', $update_array, $where_array);
                if ($user_id) {
                    if (!empty($_FILES['edit_u_image']['name'])) {

                        //check user folder is exists or not
                        $user_folder_path = $this->user_image_path . "/users";
                        if (!file_exists($user_folder_path)) {
                            mkdir($user_folder_path, 0777, true);
                            chmod($user_folder_path, 0777);
                        }

                        $userimage_folder_path = $this->user_image_path . "/users/" . $user_id;
                        if (!file_exists($userimage_folder_path)) {
                            mkdir($userimage_folder_path, 0777, true);
                            chmod($userimage_folder_path, 0777);
                        }

                        $config['upload_path'] = $userimage_folder_path;
                        $config['file_name'] = $_FILES['edit_u_image']['name'];
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'jpg|jpeg|png';
                        $config["max_size"] = 1024;
                        $this->load->library('upload', $config);

                        if ($this->upload->do_upload('edit_u_image')) {
                            $update_array = array(
                                'u_image' => $_FILES['edit_u_image']['name'],
                            );

                            $where_array = array(
                                'u_id' => $user_id
                            );

                            $is_update = $this->user_model->update('users', $update_array, $where_array);
                        
                            //delete old image
                            $old_image = $get_user[0]->u_image;
                            $olduserimage_path = $this->user_image_path . "/users/" . $user_id."/".$old_image;                        
                            
                            if(file_exists($olduserimage_path)){
                                unlink($olduserimage_path);
                            }
                        }
                    }
                }

                $this->msg['status'] = true;
                $this->msg['message'] = "User updated successfully.";
                echo json_encode($this->msg);
                exit;
            }
        } else {
            $this->msg['status'] = false;
            $this->msg['message'] = "Something error occur.";
            echo json_encode($this->msg);
            exit;
        }
    }

    function delete_user() {
        $id = $this->input->post("id");
        $response = array();
        if (!is_array($id)) {
            if (empty($id)) {
                if ($this->input->is_ajax_request()) {
                    $this->msg['status'] = false;
                    $this->msg['message'] = "Something error occur.";
                }
            } else {
                $where = array();
                $where['u_id'] = $id;

                $request_data = array();
                $request_data['u_status'] = 9;
                $request_data['u_deleted_at'] = date("Y-m-d H:i:s");

                $update_state = $this->user_model->update("users", $request_data, $where);
                if ($update_state > 0) {
                    $response['status'] = true;
                    $response['message'] = "User deleted successfully.";
                } else {
                    $response['status'] = false;
                    $response['message'] = "Problem while delete user.";
                }
            }
        } else {

            //transaction begin;
            $this->db->trans_begin();

            foreach ($id as $single) {
                $where = array();
                $where['u_id'] = $single;

                $request_data = array();
                $request_data['u_status'] = 9;
                $request_data['u_deleted_at'] = date("Y-m-d H:i:s");

                $update_state = $this->user_model->update("users", $request_data, $where);
            }

            //Checck if Transaction ended properly
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['status'] = false;
                $response['message'] = "Problem while delete users.";
            } else {
                $this->db->trans_commit();
                $response['status'] = true;
                $response['message'] = "Users deleted successfully.";
            }
        }
        echo json_encode($response);
        exit;
    }
    
    function get_user_data(){
        $id = $this->input->post("id");
        if (empty($id)) {
            if ($this->input->is_ajax_request()) {
                $this->msg['status'] = false;
                $this->msg['message'] = "Something error occur.";
            }
        } else {
            
            $get_user = $this->user_model->getuser($id);
            if (count($get_user) > 0) {
                $get_user_data = $get_user[0];
                $get_user_data->u_contactno = ltrim($get_user_data->u_contactno,"+"); 
                $get_user_data->u_hobbies = explode(",", $get_user_data->u_hobbies); 
                $response['data'] = $get_user_data;
                $response['status'] = true;
                $response['message'] = "User found successfully.";
            } else {
                $response['status'] = false;
                $response['message'] = "Problem while fetch user.";
            }
        }
        echo json_encode($response);
        exit;
    }

}
