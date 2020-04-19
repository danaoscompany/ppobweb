<?php

class Admin extends CI_Controller {
  
  public function add_product() {
    $name = $this->input->post('name');
    $config = array(
        'upload_path' => "./userdata/",
        'allowed_types' => "gif|jpg|png|jpeg",
        'overwrite' => TRUE,
        'max_size' => "2048000"
        );
        $this->load->library('upload', $config);
        if($this->upload->do_upload('icon'))
        { 
          $this->db->insert('products', array(
            'name' => $name,
            'icon' => $this->upload->data()['file_name']
          ));
        }
  }
  
  public function add_category() {
    $category = $this->input->post('category');
    $this->db->insert('product_category', array(
      'category' => $category
    ));
  }
  
  public function get_with_length() {
    $name = $this->input->post('name');
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get($name)->result_array());
  }
  
  public function get_news() {
    $start = intval($this->input->post('start'));
    $length = intval($this->input->post('length'));
    $this->db->limit($length, $start);
    echo json_encode($this->db->get('news')->result_array());
  }

  public function add_news() {
    $title = $this->input->post('title');
    $content = $this->input->post('content');
    $date = $this->input->post('date');
    $config = array(
        'upload_path' => "./userdata/",
        'allowed_types' => "gif|jpg|png|jpeg",
        'overwrite' => TRUE,
        'max_size' => "2048000"
        );
        $this->load->library('upload', $config);
        if($this->upload->do_upload('file'))
        { 
          $this->db->insert('news', array(
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'img' => $this->upload->data()['file_name']
          ));
        }
  }
  
  public function edit_news() {
    $newsID = intval($this->input->post('id'));
    $title = $this->input->post('title');
    $content = $this->input->post('content');
    $date = $this->input->post('date');
    $imgChanged = intval($this->input->post('img_changed'));
    echo "Image changed: " . $imgChanged . "<br/>";
    if ($imgChanged == 0) {
      $this->db->where('id', $newsID);
      $this->db->update('news', array(
            'title' => $title,
            'content' => $content,
            'date' => $date
          ));
    } else if ($imgChanged == 1) {
      $config = array(
        'upload_path' => "./userdata/",
        'allowed_types' => "gif|jpg|png|jpeg",
        'overwrite' => TRUE,
        'max_size' => "2048000"
        );
        $this->load->library('upload', $config);
        if($this->upload->do_upload('file'))
        { 
          $fileName = $this->upload->data()['file_name'];
          $this->db->where('id', $newsID);
          $this->db->update('news', array(
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'img' => $fileName
          ));
          echo "File name " . $fileName . "<br/>";
        }
          echo "Error: " . json_encode($this->upload->display_errors());
        
    }
  }
  
  public function delete_news() {
    $newsID = intval($this->input->post('id'));
    $this->db->where('id', $newsID);
    $this->db->delete('news');
  }
  
  public function get_registrations() {
    $start = intval($this->input->post('start'));
    $limit = intval($this->input->post('length'));
    $this->db->order_by('date', 'DESC');
    $this->db->limit($limit, $start);
    echo json_encode($this->db->get('registrations')->result_array());
  }
  
  public function update_fcm_id() {
    $adminID = intval($this->input->post('admin_id'));
    $fcmID = $this->input->post('fcm_id');
    $this->db->where('id', $adminID);
    $this->db->update('admins', array(
      'fcm_id' => $fcmID
    ));
  }
  
  public function login() {
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $results = $this->db->get_where('admins', array(
      'email' => $email
    ))->result_array();
    if (sizeof($results) > 0) {
      $row = $results[0];
      if ($row['password'] != $password) {
        echo json_encode(array(
          'response_code' => -2
        ));
      } else {
        echo json_encode(array(
          'response_code' => 1,
          'data' => array(
            'user_id' => "" . $row['id']
          )
        ));
      }
    } else {
      echo json_encode(array(
          'response_code' => -1
        ));
    }
  }
  
  public function edit_message() {
    $id = intval($this->input->post('id'));
    $title = $this->input->post('title');
    $message = $this->input->post('message');
    $userID = intval($this->input->post('user_id'));
    $date = $this->input->post('date');
    $this->db->where('id', $id);
    $this->db->update('messages', array(
      'user_id' => $userID,
      'title' => $title,
      'message' => $message
    ));
  }
  
  public function add_message() {
    $title = $this->input->post('title');
    $message = $this->input->post('message');
    $userID = intval($this->input->post('user_id'));
    $date = $this->input->post('date');
    $this->db->insert('messages', array(
      'user_id' => $userID,
      'title' => $title,
      'message' => $message,
      'date' => $date
    ));
  }
  
  public function find_user() {
    $keyword = $this->input->post('keyword');
    //$this->db->like(array('name' => $keyword, 'xabber_email' => $keyword));
    $this->db->like('name', $keyword);
    echo json_encode($this->db->get('users')->result_array());
  }
  
  public function add_banner() {
    $config['upload_path'] = './userdata/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 5000;
        $config['max_width'] = 5000;
        $config['max_height'] = 5000;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            echo "Upload success: " . $this->upload->data()['file_name'];
            $this->db->insert('banners', array(
              'img' => $this->upload->data()['file_name']
            ));
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
  
  public function get_messages() {
    echo json_encode($this->db->get('messages')->result_array());
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
