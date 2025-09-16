<?php 
require_once __DIR__ . "./API.php";
require_once __DIR__ . './../controllers/testController.php';

class test extends API
{
    public function __construct() {
        parent::__construct(new testController());
    }
    public function index() {

        $this->get(); // Call the get method from the API class
    }
}
$api = new test();
$api->index(); // Call the index method to trigger the API response