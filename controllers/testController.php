<?php 
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . "/../models/testModel.php";

class TestController extends Controller
{
    public function __construct()
    {
        parent::__construct(new TestModel());
    }

    
}