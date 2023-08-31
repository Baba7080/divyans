<?php

require_once 'controllers/MyController.php';

class BaseController {
    public function handleRequest() {

        if($_SERVER['REQUEST_URI'] == BASE_PATH)
        {
            $this->index();
        }
        else
        {
            if(isset($_GET['c'] , $_GET['a']))
            {
                $controller = $_GET['c'] . "Controller";
                $action = $_GET['a'];
                $newCon = new $controller();
                $newCon->$action();
            }
        }

    }

    public function model($model)
    {
        //Require model file
        require_once 'models/' . $model . '.php';
        //Instantiate model
        return new $model();
    }

    public function index()
    {
        $this->pujaType = $this->model('PujaModel');
        $data = $this->pujaType->getBlogs();
        $this->loadView('front' , $data);
    }

    public function loadView($viewName, $data = []) {
        extract($data);
        include "views/$viewName.php";
    }
}
?>
