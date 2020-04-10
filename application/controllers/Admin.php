<?php

class Admin extends CI_Controller {
  
  public function get_users() {
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get('users')->result_array());
  }
}