<?php


class User extends CI_Controller {
  
  public function purchase() {
    $userID = intval($this->input->post('user_id'));
    $phone = $this->input->post('phone');
    $nominal = intval($this->input->post('nominal'));
    $product = $this->input->post('product');
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
        'nominal' => $nominal,
        'product' => $product,
        'transaction_n' => $transaction_n,
        'message' => $message,
        'date' => $date
      ));
  }
}