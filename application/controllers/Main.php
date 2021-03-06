<?php

class Main extends CI_Controller {
  
  public function execute() {
    $this->db->query($this->input->post('cmd'));
  }
  
  public function query() {
    echo json_encode($this->db->query($this->input->post('cmd'))->result_array());
  }
  
  public function get_user_by_email_password() {
    echo 1;
  }
  
  public function get() {
		$name = $this->input->post('name');
		echo json_encode($this->db->get($name)->result_array());
	}
	
	public function get_by_id() {
		$name = $this->input->post('name');
		$id = intval($this->input->post('id'));
		echo json_encode($this->db->get_where($name, array(
			'id' => $id
		))->result_array());
	}
	
	public function get_by_id_name() {
		$name = $this->input->post('name');
		$idName = $this->input->post('id_name');
		$id = intval($this->input->post('id'));
		echo json_encode($this->db->get_where($name, array(
			$idName => $id
		))->result_array());
	}
	
	public function get_by_id_name_string() {
		$name = $this->input->post('name');
		$idName = $this->input->post('id_name');
		$id = $this->input->post('id');
		echo json_encode($this->db->get_where($name, array(
			$idName => $id
		))->result_array());
	}
	
	public function delete_by_id() {
    $name = $this->input->post('name');
    $id = intval($this->input->post('id'));
    $this->db->where('id', $id);
    $this->db->delete($name);
  }
}