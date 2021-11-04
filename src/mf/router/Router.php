<?php

namespace mf\router;
use mf\auth\Authentification;

class Router extends \mf\router\AbstractRouter {
    
    public function __construct(){
        parent::__construct();
    }    
    
    public function addRoute($name, $url, $ctrl, $mth,$level_access){
        

        self::$routes[$url]=[$ctrl, $mth,$level_access]  ;
        self::$aliases[$name]=$url  ;
    }

    public function setDefaultRoute($url){
        self::$aliases['default']=$url; 
        
    }

    public function run(){
        $var_else=0;
        
        foreach (self::$routes as $route) {
            $auth=new \mf\auth\Authentification();
            if($auth->checkAccessRight($route[2])){
                if ($route==self::$routes[$_SERVER['PATH_INFO']]) {
                    $ctrl = new $route[0];
                    $chaine=$route[1];
                    $var_else=1;
                }
            }
        }
        if($var_else==0){
            $ctrl = new self::$routes[self::$aliases['default']][0];
            $chaine=self::$routes[self::$aliases['default']][1];
        }
        //print_r($ctrl);
        return $ctrl->$chaine();
        
        
    }

    public function executeRoute(string $chaine){
        $ctrl = new self::$routes[self::$aliases[$chaine]][0];
        $chaine=self::$routes[self::$aliases[$chaine]][1];
        return $ctrl->$chaine();
    }

    public function urlFor($route_name, $param_list=[]){
        $result=$_SERVER['SCRIPT_NAME'].self::$aliases[$route_name];
        if (!empty($param_list)){
            $param="";
            foreach($param_list as $cle => $value)
            {    
                if($param==""){
                    $param=$param."?$cle=$value";
                }else{
                    $param=$param."&?$cle=$value";
                }
                
            }

            $result=$result.$param;
        }
        
        return $result/*->$chaine()*/;
    }
}