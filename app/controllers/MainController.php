<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller
{
  public function indexAction()
  {
    $result = $this->model->getData();
    $result_category = $this->model->getCategory();
    $vars = [
      'product' => $result,
    ];
    $vars_cat = [
      'category_data' => $result_category,
    ];
    $this->view->render('Главная страница', $vars, $vars_cat);
  }
}
