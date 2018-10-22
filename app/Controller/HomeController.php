<?php

namespace App\Controller;

use Sys\Core\View;

class HomeController
{

    public function index()
    {
        $siteSettingModel = new SiteSettingModel;
        $data['setting'] = $siteSettingModel->find(1);
        $modules = new ModuleModel;
        $data['featured'] = $modules->featured(28);
        $data['latest'] = $modules->latest();
        $categoryModel = new CategoryModel;
        $data['menuCategories'] = $categoryModel->menuCategories();
        $data['isCloseMailchimp'] = isset($_COOKIE['chimp'])??true;
        $data['isLogged'] = false;
        $data['title'] = 'Basic Mvc';
        $data['slogan'] = 'he he he :)';
        print View::to('home', $data);
    }

}
