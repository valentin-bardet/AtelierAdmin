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
        if (!empty($_SESSION['user_login'])){//menu connecté

            $router = new \mf\router\Router();
            $res="<nav><a href='".$router->urlFor('logout')."'><img src='https://valentinbardet.fr/atelier/html/icons/logout.png'></a>";
            if($_SESSION['access_level']==1){
                $res=$res."<a href='".$router->urlFor('homeProducteur')."'><img src='https://valentinbardet.fr/atelier/html/icons/home.png'></a></nav>";
            }if($_SESSION['access_level']==2){
                $res=$res."<a href='".$router->urlFor('homeGerant')."'><img src='https://valentinbardet.fr/atelier/html/icons/home.png'></a></nav>";
            }
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
        }if($selector == 'AllCommandes'){
            $section = $this->renderAllCommandes();
        }
        return "<header>${header}</header><section>${section}</section><footer>${footer}</footer>";
    }

    public function renderLogin(){
        $router = new \mf\router\Router();
        $resultat="<div>";
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
        $resultat=$resultat."<main><article><a href=".$router->urlFor('AllCommandes').">Commandes</a></article>";
        $resultat=$resultat."<article><a href=".$router->urlFor('TableauDeBord').">Tableau de Bord</a></article></main>";

    return $resultat;
    }

    public function renderAllCommandes(){
    $router = new \mf\router\Router();
        $resultat= "<h2>Gestion des Commandes</h2>";
        $nbCommandes=0;
        foreach ($this->data as $prop){
            $nbCommandes++;
        }
        $resultat=$resultat."<div id='ALlCommandes'><aside><h3>$nbCommandes Commandes</h3><form><input type='text'></form></aside>";

        foreach ($this->data as $prop){

            if($prop->Etat == 'livré'){$livre = "Livré";$paye="Non-Payé";
                $commande= \appAdmin\model\Quantite::where('COMMANDE_ID', '=',$prop->id);
                $lines=$commande->get();
                $nbArticles=0;
                foreach ($lines as $q){
                    $nbArticles=$nbArticles + $q->Quantite;
                }
            $resultat=$resultat."<a href=".$router->urlFor('commandes',['id_Commande'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p>$livre</p><p>$prop->Montant €</p><p class='error'>$paye</p></article></a>"."\n";
            }
        }
        foreach ($this->data as $prop){
            if($prop->Etat == 'en_cours'){
                $livre = "Non-Livré";$paye="Non-Payé";
                $commande= \appAdmin\model\Quantite::where('COMMANDE_ID', '=',$prop->id);
                $lines=$commande->get();
                $nbArticles=0;
                foreach ($lines as $q){
                    $nbArticles=$nbArticles + $q->Quantite;
                }
                $resultat=$resultat."<a href=".$router->urlFor('commandes',['id_Commande'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p class='error'>$livre</p><p>$prop->Montant €</p><p class='error'>$paye</p></article></a>"."\n";
            }
        }
        foreach ($this->data as $prop){
            if($prop->Etat == 'payé'){
                $livre = "Livré";$paye="Payé";
                $commande= \appAdmin\model\Quantite::where('COMMANDE_ID', '=',$prop->id);
                $lines=$commande->get();
                $nbArticles=0;
                foreach ($lines as $q){
                    $nbArticles=$nbArticles + $q->Quantite;
                }
                $resultat=$resultat."<a href=".$router->urlFor('commandes',['id_Commande'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p>$livre</p><p>$prop->Montant €</p><p>$paye</p></article></a>"."\n";
            }
        }



    return $resultat."</div>";
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
        $resultat=$resultat."<div id='tableauBordInfo'><article><img src='https://valentinbardet.fr/atelier/html/icons/customer.png' alt='Client'><h3>$nbUser Clients</h3></article>";
        $resultat=$resultat."<article><img src='https://valentinbardet.fr/atelier/html/icons/orders.png' alt='Commandes'><h3>$com Commandes</h3></article>";
        $resultat=$resultat."<article><img src='https://valentinbardet.fr/atelier/html/icons/money.png' alt='Money'><h3>$ca € De CA </h3></article></div>";
        $resultat=$resultat."<h2>Chiffre d'affaire par Producteur</h2>";
        $commandes = \appAdmin\model\Commande::select();
        $lignes=$commandes->get();
        $producteurs = \appAdmin\model\User::where('Role', '=','Producteur');
        $tabProducteur =$producteurs->get();

        $IdProduits=[];
        $resultat=$resultat."<div id='Producteurs_CA'>";
        foreach ($tabProducteur as $value){
            $resultat=$resultat."<article><img src='$value->Image' alt='$value->Nom'><h3>$value->Nom</h3>";
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
            $resultat=$resultat."<p>$price €</p></article>";
        }
    return $resultat."</div>";
    }

    public function renderCommandes(){
        $router = new \mf\router\Router();
        $resultat="<div>";
        $resultat= $resultat."Commandes</div>";
//      $resultat =$this->data->produit;
        return $resultat;
    }


}
