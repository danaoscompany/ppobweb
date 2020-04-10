<?php

class Admin extends CI_Controller {
  
  public function get_users() {
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get('users')->result_array());
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
}