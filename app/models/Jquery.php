<?php

namespace app\models;

use app\core\Model;

class Jquery extends Model
{
  public function deleteProduct()
  {
    $this->db->row("DELETE descriptions
                    FROM products
                    INNER JOIN descriptions ON descriptions.product_id = products.id               
                    WHERE products.id= :id", ['id' => $_POST['id']]);
    $this->db->row("DELETE products FROM products WHERE id= :id", ['id' => $_POST['id']]);
    $output['empty'] = $this->db->checkTableEmpty();
    $output['message'] = "ok";
    echo json_encode($output);
  }

  public function addProduct()
  {
    $params = [
      'name' => $_POST['name'],
      'price' => $_POST['price'],
      'date' => date("Y-m-d"),
      'category_id' => $_POST['category_id'],
    ];
    $this->db->row("INSERT INTO products
                        (name, price, date_view, category_id) VALUES
                        (:name, :price, :date, :category_id)", $params);
    $last_id = $this->db->getLastInsId(); //Get last inserted product id

    foreach ($_POST['description'] as $data) {
      $this->db->row("INSERT INTO descriptions 
                          (description, product_id) VALUES 
                          (:desc, :product_id)", ['desc' => $data, 'product_id' =>  $last_id]);
    }
    $description = implode("</br></br>", $_POST['description']);
    $output['message'] = 'Item added to Database';
    $output['code'] = '<tbody id="qty_' . $last_id . '">
                      <tr>
                        <td>' . $params['name'] . '</td>
                        <td>$ ' . $params['price'] . '</td>
                        <td>' . $description . '</td>
                        <td>' . $_POST['category_name'] . '</td>
                        <td><button type="button" class="btn btn-default btn-flat p-0"><i class="fa fa-lg fa-pencil" id="edit" data-id="' . $last_id . '"></i></button></td>
                        <td><button type="button" class="btn btn-default btn-flat p-0"><i class="fa fa-lg fa-close" id="delete" data-id="' . $last_id . '" ></i></button></td>
                      </tr>
                    </tbody>';
    $output['message'] = "ok";
    echo json_encode($output);
  }

  public function getProduct()
  {
    $stmt = $this->db->column("SELECT products.id, products.name, category.id AS category_id, category.name AS cat_name , products.price, GROUP_CONCAT(descriptions.description SEPARATOR '~') AS description, GROUP_CONCAT(descriptions.id)
                    FROM products
                    INNER JOIN category ON products.category_id = category.id
                    INNER JOIN descriptions ON descriptions.product_id = products.id 
                    WHERE products.id= :id
                    ", ['id' => $_POST['id']]);
    $output['name'] = $stmt['name'];
    $output['price'] = $stmt['price'];
    $output['description'] = explode('~', $stmt['description']);
    $output['id'] = $_POST['id'];
    $output['desc_id'] = explode(',', $stmt['GROUP_CONCAT(descriptions.id)']);
    $output['category_id'] = $stmt['category_id'];
    echo json_encode($output);
  }

  public function updateProduct()
  {
    $params = [
      'name' => $_POST['name'],
      'price' => $_POST['price'],
      'date' => date("Y-m-d"),
      'category_id' => $_POST['category_id'],
      'id' => $_POST['id'],
    ];
    $this->db->row("UPDATE products SET name=:name, price=:price, date_view=:date, category_id=:category_id WHERE id=:id", $params);

    foreach (array_combine($_POST['description'], $_POST['desc_id']) as $data => $d_id) {
      $this->db->row("UPDATE descriptions SET description=:data WHERE id=:id", ['data' => $data, 'id' => $d_id]);
    }

    $description = implode("</br></br>", $_POST['description']);
    $output['message'] = 'ok';
    $output['code'] = '<tr>
                        <td>' . $params['name'] . '</td>
                        <td>$ ' . $params['price'] . '</td> 
                        <td>' . $description . '</td>
                        <td>' . $_POST['category_name'] . '</td>
                        <td><button type="button" class="btn btn-default btn-flat p-0"><i class="fa fa-lg fa-pencil" id="edit" data-id="' . $_POST['id'] . '"></i></button></td>
                        <td><button type="button" class="btn btn-default btn-flat p-0"><i class="fa fa-lg fa-close" id="delete" data-id="' . $_POST['id'] . '" ></i></button></td>
                      </tr>';
    $output['message'] = "ok";
    echo json_encode($output);
  }
}
