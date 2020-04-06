<?php


class User extends CI_Controller {
  
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
    $message = $this->input->post('message');
    $date = $this->input->post('date');
    $transaction_n = 1;
    $transactions = $this->db->get_where('transactions', array(
        'phone' => $phone,
        'nominal' => $nominal
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
        'message' => $message,
        'date' => $date
      ));
  }
}