<?php

class User extends CI_Controller {
  
  public function update_jabber_password() {
        $userID = intval($this->input->post('user_id'));
        $password = $this->input->post('password');
        $this->db->where('id', $userID);
        $this->db->update('users', array(
            'xabber_password' => $password
        ));
    }
    
    public function update_jabber_email() {
        $userID = intval($this->input->post('user_id'));
        $email = $this->input->post('email');
        $this->db->where('id', $userID);
        $this->db->update('users', array(
            'xabber_email' => $email
        ));
    }
  
  public function add_user() {
    $name = $this->input->post('name');
    $phone = $this->input->post('phone');
    $pin = $this->input->post('pin');
    $city = $this->input->post('city');
    $markup = $this->input->post('markup');
    $this->db->insert('users', array(
      'name' => $name,
      'phone' => $phone,
      'pin' => $pin,
      'city' => $city,
      'markup' => $markup
    ));
  }
  
  public function update_xabber_email() {
    $phone = $this->input->post('phone');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $this->db->where('phone', $phone);
    $this->db->update('users', array(
      'xabber_email' => $email,
      'xabber_password' => $password
    ));
  }
  
  public function register() {
    $format = $this->input->post('format');
    $admins = $this->db->get('admins')->result_array();
    $date = $this->input->post('date');
    $this->db->insert('registrations', array(
      'format' => $format,
      'date' => $date
    ));
    for ($i=0; $i<sizeof($admins); $i++) {
      $admin = $admins[$i];
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = $admin['fcm_id'];
    $serverKey = 'AAAAzpxcHnA:APA91bETrty9E8Fbxg4E2-VJkXPM9-PslVeYJvhmc_Onq98ZS0n7_WyS-XkpL7_Ub6gNnDIhd8kKEPRkNadnk-So4kVRbZTDi-Cg2pHgUb1PmKx_-musYRuNCnN7uGKiZdQWLrkaPwpG';
    $title = "Registrasi Pengguna Baru";
    $body = "Ada pengguna baru yang mendaftar. Klik untuk menyetujui.";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    }
    curl_close($ch);
    }
  }
  
  public function test() {
    echo "Halo dunia";
  }
  
  public function get_inbox() {
    $userID = intval($this->input->post('user_id'));
    $this->db->where('user_id', $userID)->or_where('user_id', -1);
    $this->db->order_by('date', 'DESC');
    echo json_encode($this->db->get('messages')->result_array());
  }
  
  public function get_pin() {
    $userID = intval($this->input->post('user_id'));
    $results = $this->db->get_where('users', array(
      'id' => $userID
    ))->result_array();
    if (sizeof($results) > 0) {
      $row = $results[0];
      echo $row['pin'];
    }
  }
  
  public function set_pin() {
    $userID = intval($this->input->post('user_id'));
    $oldPIN = $this->input->post('old_pin');
    $pin = $this->input->post('pin');
    if ($this->db->get_where('users', array(
      'id' => $userID
    ))->row_array()['pin'] != $oldPIN) {
      echo -1;
      return;
    }
    $this->db->where('id', $userID);
    $this->db->update('users', array(
      'pin' => $pin
    ));
    echo 1;
  }

  public function set_access_code() {
    $userID = intval($this->input->post('user_id'));
    $accessCode = $this->input->post('access_code');
    $this->db->where('id', $userID);
    $this->db->update('users', array(
      'access_code' => $accessCode
    ));
  }
  
  public function user_have_access_code() {
    $userID = intval($this->input->post('user_id'));
    $results = $this->db->get_where('users', array(
      'id' => $userID
    ))->result_array();
    if (sizeof($results) > 0) {
      $row = $results[0];
      if ($row['access_code'] == NULL || $row['access_code'] == '') {
        echo -1;
      } else {
        echo 1;
      }
    } else {
      echo -1;
    }
  }
  
  public function login() {
    $phone = $this->input->post('phone');
    $pin = $this->input->post('pin');
    $results = $this->db->get_where('users', array(
        'phone' => $phone
    ))->result_array();
    if (sizeof($results) > 0) {
      $row = $results[0];
      if ($row['pin'] == $pin) {
        echo json_encode(array(
          'response_code' => 1,
          'data' => array(
            'user_id' => "" . $row['id'],
            'access_code' => $row['access_code'],
            'email' => $row['xabber_email'],
            'password' => $row['xabber_password']
          )
        ));
      } else {
        echo json_encode(array(
          'response_code' => -2
        ));
      }
    } else {
      echo json_encode(array(
          'response_code' => -1
        ));
    }
  }
  
  public function signup() {
    $name = $this->input->post('name');
    $city = $this->input->post('city');
    $phone = $this->input->post('phone');
    $pin = $this->input->post('pin');
    $xabberEmail = $this->input->post('xabber_email');
    $xabberPassword = $this->input->post('xabber_password');
    if ($this->db->get_where('users', array(
        'phone' => $phone
      ))->num_rows() > 0) {
        echo -1;
        return;
      }
    $this->db->insert('users', array(
      'name' => $name,
      'phone' => $phone,
      'city' => $city,
      'pin' => $pin,
      'xabber_email' => $xabberEmail,
      'xabber_password' => $xabberPassword
    ));
    $this->db->insert('registrations', array(
      "REG#master#" . $nama . "#" . $phone . "#" . $city . "#" . $xabberEmail . "#" . $xabberPassword;
    ));
    $admins = $this->db->get('admins');
    for ($i=0; $i<sizeof($admins); $i++) {
      $admin = $admins[$i];
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = $admin['fcm_id'];
    $serverKey = 'AAAAzpxcHnA:APA91bETrty9E8Fbxg4E2-VJkXPM9-PslVeYJvhmc_Onq98ZS0n7_WyS-XkpL7_Ub6gNnDIhd8kKEPRkNadnk-So4kVRbZTDi-Cg2pHgUb1PmKx_-musYRuNCnN7uGKiZdQWLrkaPwpG';
    $title = "Registrasi Pengguna Baru";
    $body = "Ada pengguna baru yang mendaftar. Klik untuk info selengkapnya.";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    }
    curl_close($ch);
    }
    echo 1;
  }
  
  public function check_user_exists() {
    $phone = $this->input->post('phone');
    if ($this->db->get_where('users', array(
        'phone' => $phone
      ))->num_rows() > 0) {
        echo 1;
      } else {
        echo -1;
      }
  }
  
  public function get_transaction_n() {
    $phone = $this->input->post('phone');
    $productCode = $this->input->post('product_code');
    $transaction_n = 0;
    $transactions = $this->db->get_where('transactions', array(
        'phone' => $phone,
        'product_code' => $productCode
      ))->result_array();
    if (sizeof($transactions) > 0) {
      $transaction = $transactions[sizeof($transactions)-1];
      echo $transaction['transaction_n'];
    } else {
      echo 0;
    }
  }
  
  
  public function purchase() {
    $userID = intval($this->input->post('user_id'));
    $phone = $this->input->post('phone');
    $product = $this->input->post('product_code');
    $command = $this->input->post('command');
    $message = $this->input->post('message');
    $date = $this->input->post('date');
    $transaction_n = 1;
    $transactions = $this->db->get_where('transactions', array(
        'phone' => $phone,
        'product_code' => $product
      ))->result_array();
    if (sizeof($transactions) > 0) {
      $transaction = $transactions[sizeof($transactions)-1];
      $transaction_n = intval($transaction['transaction_n'])+1;
    }
    $this->db->insert('transactions', array(
        'user_id' => $userID,
        'phone' => $phone,
        'product_code' => $product,
        'transaction_n' => $transaction_n,
        'command' => $command,
        'message' => $message,
        'date' => $date
      ));
  }
}