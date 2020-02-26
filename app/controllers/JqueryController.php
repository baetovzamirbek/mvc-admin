<?php

namespace app\controllers;

use app\core\Controller;

class JqueryController extends Controller
{
  public function deleteAction()
  {
    $this->model->deleteProduct($_POST['id']);
  }

  public function addAction()
  {
    $this->model->addProduct();
  }

  public function getAction()
  {
    $this->model->getProduct();
  }

  public function updateAction()
  {
    $this->model->updateProduct();
  }
}
