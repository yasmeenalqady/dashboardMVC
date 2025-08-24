<?php
namespace App\Controllers;

use App\Models\User;

class HomeController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

   public function index() {
   

    // تمرير قيمة البحث للعرض لتحديث العنوان
    require __DIR__ . '/../Views/home.php';
}



    
}
