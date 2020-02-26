<?php

namespace app\lib;

use PDO;

class Db
{
  protected $db;

  public function __construct()
  {
    $config = require 'app/config/db.php';
    $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . '', $config['user'], $config['password']);
  }

  public function query($sql, $params = [])
  {
    $stmt = $this->db->prepare($sql);
    if (!empty($params)) {
      foreach ($params as $key => $val) {
        $stmt->bindValue(':' . $key, $val);
      }
    }
    $stmt->execute();
    return $stmt;
  }

  public function row($sql, $params = [])
  {
    $result = $this->query($sql, $params);
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }

  public function column($sql, $params = [])
  {
    $result = $this->query($sql, $params);
    return $result->fetch();
  }

  public function checkTableEmpty()
  {
    $check = $this->row("SELECT * FROM products");
    $check = count($check);
    return $check;
  }

  public function getLastInsId()
  {
    return $this->db->lastInsertId();
  }
}
