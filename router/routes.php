<?php 

use Sys\Core\Router;
use App\Model\AppRouteModel;

if(isset( $_GET['p'])) {
    $appRouteModel = new AppRouteModel;
    $route =  explode('/',$_GET['p']);
    $roteData = $appRouteModel->where('keyword', end($route))->get()->row();
    if($roteData) {
        Router::add($_GET['p'], $roteData->app_name,'index',[$roteData->app_id]);
    }
}
Router::add('iletisim','cart','iletisim');
Router::add('markalar','Manufacturer','index');
