<?php

namespace appAdmin\view;

class AdminView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct( $data ){
        parent::__construct($data);
    }

    /* Méthode renderHeader
     *
     *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
     */ 
    public function renderHeader(){
        print_r($_SESSION);
        if (!empty($_SESSION['user_login'])){//menu connecté
            $router = new \mf\router\Router();
            $res="<a href='".$router->urlFor('logout')."'>Logout</img></a>";
            return $res;
        }
        return "<h1>LeHangar - Gestion</h1> ";
        
    }
    
    /* Méthode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    public function renderFooter(){
        return 'L\'application a été créée en Licence Pro &copy;2021';
    }

    /* Méthode renderHome
     *
     * Vue de la fonctionalité afficher tous les Tweets. 
     *  
     */
    
    private function renderHome(){
       $resultat="<div class='categorie'>";
       $router = new \mf\router\Router();
       
     
            $categorie = \appClient\model\Categorie::select()->get();    
            //var_dump($categorie);
            foreach($categorie as $cat){
                $resultat =$resultat."<div><a href=\"../\">".$cat->Nom."</a></div>";
            }
           
        
        $resultat=$resultat."</div>";
        return $resultat;
        /*
         * Retourne le fragment HTML qui affiche tous les Tweets. 
         *  
         * L'attribut $this->data contient un tableau d'objets tweet.
         * 
         */
        
        
    }
    
    public function renderBody($selector){

        /*
         * voire la classe AbstractView
         * 
         */
        $header = $this->renderHeader();
        $footer = $this->renderFooter();
        if($selector == 'Login'){
            $section = $this->renderLogin();
        } if($selector == 'HomeProducteur'){
           $section = $this->renderHomeProducteur();
         }if($selector == 'HomeGerant'){
           $section = $this->renderHomeGerant();
         }if($selector == 'HomeCommandes'){
            $section = $this->renderCommandes();
        }
        return "<header>${header}</header><section>${section}</section><footer>${footer}</footer>";
    }

    public function renderLogin(){
        $router = new \mf\router\Router();
        $resultat="<div class='theme-backcolor2'>";
        $resultat=$resultat."<form method='post' action=".$router->urlFor('checklogin').">
        <input type='text' placeholder='Username' name='user_name' id='user_name'></br></br>
        <input type='password' placeholder='Password' name='password' id='password'></br></br>
        <button type='submit'>Login</button>
        </form>";

        return $resultat;
    }



    public function renderHomeProducteur(){
        $router = new \mf\router\Router();
        var_dump($this->data);
        $resultat="<a>";
        $resultat= $resultat."Producteur</div>";

        return $resultat;
    }
    public function renderHomeGerant(){
    $router = new \mf\router\Router();
    $resultat="<div>";
    $resultat= $resultat."GÉRANT</div>";

    return $resultat;
}
    public function renderCommandes(){
        $router = new \mf\router\Router();
        $resultat="<div>";
        $resultat= $resultat."Commandes</div>";
        $resultat =$this->data->produit;
        return $resultat;
    }


}
