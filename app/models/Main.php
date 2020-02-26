<?php

namespace app\models;

use app\core\Model;

class Main extends Model
{
  public function getData()
  {
    $result = $this->db->row("SELECT products.id, products.name, category.name AS cat_name , products.price, GROUP_CONCAT(descriptions.description SEPARATOR '</br></br>') AS descr
                                    FROM products
                                    LEFT JOIN category ON products.category_id = category.id
                                    LEFT JOIN descriptions ON products.id = descriptions.product_id
                                    GROUP BY products.id");
    return $result;
  }

  public function getCategory()
  {
    $result = $this->db->row("SELECT * FROM category");
    return $result;
  }
}
