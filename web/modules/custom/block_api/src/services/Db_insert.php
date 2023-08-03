<?php

namespace Drupal\block_api\services;

use Drupal\Core\Database\Connection;

class Db_insert {
  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public function setData($form_state) {
    $this->database->insert('customform')
      ->fields([
        'title' => $form_state->getValue('title'),
        'label1' => $form_state->getValue('label1'),
        'label2' => $form_state->getValue('label2'),
        'value1' => $form_state->getValue('value1'),
        'value2' => $form_state->getValue('value2'),
      ])
      ->execute();
  }

  public function getData() {
    // Retrieve data from the database.
    $query = $this->database->select('customform', 'cf');
    $query->fields('cf', ['title', 'label1', 'label2', 'value1', 'value2']);
    $result = $query->execute()->fetchAll();
    return $result;
  }
}
