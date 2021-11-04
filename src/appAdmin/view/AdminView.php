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
        $title="<h1>LeHangar - Gestion</h1>";
        print_r($_SESSION);
        if (!empty($_SESSION['user_login'])){//menu connecté
            $router = new \mf\router\Router();
            $res="<a href='".$router->urlFor('logout')."'>Logout</img></a>";
            return $title.$res;
        }
        return $title;
        
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
         }if($selector == 'Commandes'){
            $section = $this->renderCommandes();
        }if($selector == 'TableauDeBord'){
            $section = $this->renderTableauDeBord();
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
        foreach ($this->data as $usr){
            $resultat= "<h2>Bienvenue $usr->Nom</h2>";
            $resultat=$resultat."<article><a href=".$router->urlFor('commandes',['id_producteur'=>$usr->id]).">Mes commandes</a></article>";
        }


        return $resultat;
    }
    public function renderHomeGerant(){
    $router = new \mf\router\Router();
        $resultat= "<h2>Bienvenue Administrateur</h2>";
        $resultat=$resultat."<article><a href=".$router->urlFor('TableauDeBord').">Tableau de Bord</a></article>";

    return $resultat;
    }
    public function renderTableauDeBord(){
        $resultat= "<h2>Tableau de Bord</h2>";

        $com=0;
        $ca=0;
        $user=[];
        foreach ($this->data as $info){
            $com++;
            $ca = $ca + $info->Montant;
            $user[]=$info->Mail_client;
        }
        $UniqueUser = array_unique($user);
        $nbUser=count($UniqueUser);
        $resultat=$resultat."<article><h3>$nbUser Clients</h3></article>";
        $resultat=$resultat."<article><h3>$com Commandes</h3></article>";
        $resultat=$resultat."<article><h3>$ca € De CA </h3></article>";
        $resultat=$resultat."<h2>Chiffre d'affaire par Producteur</h2>";
        $commandes = \appAdmin\model\Commande::select();
        $lignes=$commandes->get();
//        var_dump($lignes);
        $producteurs = \appAdmin\model\User::where('Role', '=','Producteur');
        $tabProducteur =$producteurs->get();

        $IdProduits=[];

        foreach ($tabProducteur as $value){
            $resultat=$resultat."<h3>$value->Nom</h3>";
            $production = \appAdmin\model\Production::where('ID_PRODUCTEUR', '=',$value->id);
            $tabProduction =$production->get();
            $price=0;
            foreach ($tabProduction as $v){


                $quantite = \appAdmin\model\Quantite::where('PRODUIT_ID', '=',$v->ID_PRODUIT);
                $tabQuantite =$quantite->get();
                $produit = \appAdmin\model\Produits::where('id', '=',$v->ID_PRODUIT);
                $tabproduits =$produit->get();

                foreach ($tabproduits as $p){
                    $p->tarif_unitaire  ;
                    foreach ($tabQuantite as $q) {
                       $price=$price+($p->tarif_unitaire*$q->Quantite);
                    }
                }
            }
            $resultat=$resultat."<p>$price €</p>";



        }
//        print_r($tabProducteur);
    return $resultat;
    }

    public function renderCommandes(){
        $router = new \mf\router\Router();
        $resultat="<div>";
        $resultat= $resultat."Commandes</div>";
//        $resultat =$this->data->produit;
        return $resultat;
    }


}
