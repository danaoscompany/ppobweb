<?php

class Admin extends CI_Controller {
  
  public function add_banner() {
    $config['upload_path'] = './userdata/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 5000;
        $config['max_width'] = 5000;
        $config['max_height'] = 5000;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            echo "Upload success: " . json_encode($this->upload->data());
            /*$this->db->insert('banners', array(
              'img' => $this->upload->data()
            ));*/
        } else {
          echo "Upload failed: " . json_encode($this->upload->display_errors());
        }
  }
  
  public function get_users() {
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get('users')->result_array());
  }
  
  public function get_admins() {
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get('admins')->result_array());
  }
  
  public function add_user() {
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $phone = $this->input->post('phone');
    $pin = $this->input->post('pin');
    $city = $this->input->post('city');
    $accessCode = $this->input->post('access_code');
    $this->db->insert('users', array(
      'name' => $name,
      'xabber_email' => $email,
      'xabber_password' => $password,
      'phone' => $phone,
      'pin' => $pin,
      'city' => $city,
      'access_code' => $accessCode
    ));
  }
  
  public function save_user() {
    $userID = intval($this->input->post('user_id'));
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $phone = $this->input->post('phone');
    $pin = $this->input->post('pin');
    $city = $this->input->post('city');
    $accessCode = $this->input->post('access_code');
    $this->db->where('id', $userID);
    $this->db->update('users', array(
      'name' => $name,
      'xabber_email' => $email,
      'xabber_password' => $password,
      'phone' => $phone,
      'pin' => $pin,
      'city' => $city,
      'access_code' => $accessCode
    ));
  }
  
  public function add_admin() {
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $this->db->insert('admins', array(
      'name' => $name,
      'email' => $email,
      'password' => $password
    ));
  }
  
  public function save_admin() {
    $userID = intval($this->input->post('user_id'));
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $this->db->where('id', $userID);
    $this->db->update('admins', array(
      'name' => $name,
      'email' => $email,
      'password' => $password
    ));
  }
}