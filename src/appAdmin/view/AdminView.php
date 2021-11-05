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
            $res="<nav><a href='".$router->urlFor('logout')."'><img src='../../html/icons/logout.png'></a>";
            if($_SESSION['access_level']==1){
                $res=$res."<a href='".$router->urlFor('homeProducteur')."'><img src='../../html/icons/home.png'></a></nav>";
            }if($_SESSION['access_level']==2){
                $res=$res."<a href='".$router->urlFor('homeGerant')."'><img src='../../html/icons/home.png'></a></nav>";
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
        }if($selector == 'TheCommande'){
            $section = $this->renderTheCommande();
        }if($selector == 'MesProduits'){
            $section = $this->renderMesProduits();
        }if($selector == 'modifProduit'){
            $section = $this->rendermodifProduit();
        }
        return "<header>${header}</header><section>${section}</section><footer>${footer}</footer>";
    }

    public function renderLogin(){
        $router = new \mf\router\Router();
        $resultat="<div class='main'>";
        $resultat=$resultat."<form method='post' action=".$router->urlFor('checklogin').">
        <h2>Connectez-vous</h2>
        <label for='user_name'>Email</label>
        <input type='text' name='user_name' id='user_name'><br><br>
        <label for='password'>Mot de passe</label>
        <input type='password' name='password' id='password'><br><br>
        <div id='valide'><button type='submit'>Connexion</button></div>
        </form><svg style='visibility: hidden; position: absolute;' width='0' height='0' xmlns='http://www.w3.org/2000/svg' version='1.1'>
                    <defs>
                        <filter id='goo'><feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />    
                            <feColorMatrix in='blur' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9' result='goo' />
                            <feComposite in='SourceGraphic' in2='goo' operator='atop'/>
                        </filter>
                    </defs>
                </svg></div>";

        return $resultat;
    }



    public function renderHomeProducteur(){
        $router = new \mf\router\Router();
        foreach ($this->data as $usr){
            $resultat= "<h2>Bienvenue $usr->Nom</h2>";
            $resultat=$resultat."<div class='main'><article><a href=".$router->urlFor('commandes',['id_producteur'=>$usr->id]).">Mes commandes</a></article><article><a href=".$router->urlFor('MesProduits').">Mes produits</a></article><article><a href=".$router->urlFor('NewProduit').">Nouveau produit</a></article></div>";
        }


        return $resultat;
    }
    public function renderMesProduits(){
        $router = new \mf\router\Router();
        foreach ($this->data as $usr){
            $resultat= "<h2>Mes Produits</h2><a href=".$router->urlFor('')."></a><div id='MesProduits'>";
            $production= \appAdmin\model\Production::where('ID_PRODUCTEUR','=',$usr->id);
            $ligneProduction=$production->get();
            $resultat=$resultat."<aside><h3>Nom</h3><h3>Prix</h3><h3>Description</h3><h3>Quantité</h3><h3>Catégorie</h3></aside>";
            foreach ($ligneProduction as $productionL){
                $produitBd = \appAdmin\model\Produits::where('id','=',$productionL->ID_PRODUIT);
                $produits=$produitBd->get();
                foreach ($produits as $produitL){
                    $resultat=$resultat."<article><p>$produitL->nom</p><p>$produitL->tarif_unitaire €</p><p>$produitL->description</p><p>$produitL->Quantite</p>";
                    $categorie = \appAdmin\model\Categorie::where('id','=',$produitL->ID_categorie)->first();
                    $resultat=$resultat."<p>$categorie->Nom</p><a href=".$router->urlFor('modifProduit',['id'=>$produitL->id]).">Modifier</a></article>";
                }
            }
        }


        return $resultat."</div>";
    }
    public function rendermodifProduit(){
        $router = new \mf\router\Router();

        foreach ($this->data as $prod){
            $resultat= "<h2>Modifier $prod->nom</h2><div id='MesProduits'>";
            $resultat=$resultat."<form method='post' action=".$router->urlFor('ValidmodifProduit').">
        <aside><label for='nom'>Nom</label><label for='prix'>Prix</label><label for='description'>Description</label><label for='quantite'>Quantité</label><label for='categorie'>Catégorie</label></aside>
        <article>
        <input type='hidden' name='id' id='id' value=".$prod->id.">
        <input type='text' name='nom' id='nom' value=".$prod->nom.">
        <input type='text' name='prix' id='prix' value=".$prod->tarif_unitaire.">
        <textarea type='text' rows='3' name='description' id='description'>$prod->description</textarea>
        <input type='text' name='quantite' id='quantite' value=".$prod->Quantite.">
        <select name='categorie' id='categorie'>";
        $categories = \appAdmin\model\Categorie::select();
        $categoriesL=$categories->get();
        foreach ($categoriesL as $res){
            if ($res->id == $prod->ID_categorie){
                $resultat=$resultat."<option selected value='$res->id'>$res->Nom</option>";
            }else{
                $resultat=$resultat."<option value='$res->id'>$res->Nom</option>";
            }

        }
        $resultat=$resultat."</select>
        <button type='submit'>Valider la modification</button></article>
        </form><svg style='visibility: hidden; position: absolute;' width='0' height='0' xmlns='http://www.w3.org/2000/svg' version='1.1'>
                    <defs>
                        <filter id='goo'><feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />    
                            <feColorMatrix in='blur' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9' result='goo' />
                            <feComposite in='SourceGraphic' in2='goo' operator='atop'/>
                        </filter>
                    </defs>
                </svg>";
        }


        return $resultat."</div>";
    }
    public function renderHomeGerant(){
    $router = new \mf\router\Router();
        $resultat= "<h2>Bienvenue Administrateur</h2>";
        $resultat=$resultat."<div class='main'><article><a href=".$router->urlFor('AllCommandes').">Commandes</a></article>";
        $resultat=$resultat."<article><a href=".$router->urlFor('TableauDeBord').">Tableau de Bord</a></article></div>";

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
            $resultat=$resultat."<a href=".$router->urlFor('TheCommande',['id'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p>$livre</p><p>$prop->Montant €</p><p class='error'>$paye</p></article></a>"."\n";
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
                $resultat=$resultat."<a href=".$router->urlFor('TheCommande',['id'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p class='error'>$livre</p><p>$prop->Montant €</p><p class='error'>$paye</p></article></a>"."\n";
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
                $resultat=$resultat."<a href=".$router->urlFor('TheCommande',['id'=>$prop->id])."><article><p>$prop->Nom_client</p><p>$prop->Tel_client</p><p>$nbArticles Article(s)</p><p>$livre</p><p>$prop->Montant €</p><p>$paye</p></article></a>"."\n";
            }
        }



    return $resultat."</div>";
    }


    public function renderTheCommande()
    {
        $router = new \mf\router\Router();
        foreach ($this->data as $val){
            $resultat="<h2>Commande n°$val->id par $val->Nom_client</h2>";
            $resultat=$resultat."<div id='Commande'><aside><span>Coordonées :</span><span>$val->Mail_client</span><span>$val->Tel_client</span></aside>";

            $producteurs=\appAdmin\model\User::select();
            $ProducteursLines=$producteurs->get();
            $InfosProducteur=[];
            $quantiteTotalFull=0;

            foreach ($ProducteursLines as $prod){
                $quantite = \appAdmin\model\Quantite::where('COMMANDE_ID', '=',$val->id);
                $lines=$quantite->get();
                foreach ($lines as $q){
                    $production = \appAdmin\model\Production::where('ID_PRODUIT', '=',$q->PRODUIT_ID);
                    $productionLignes = $production->get();
                    $produit = \appAdmin\model\Produits::where('id', '=',$q->PRODUIT_ID);
                    $produitLine =$produit->get();
                        foreach ($productionLignes as $pTion){
                            if ($prod->id == $pTion->ID_PRODUCTEUR){
                                $InfosProducteur['nom']=$prod->Nom;
                                $article=[];
                                foreach ($produitLine as $p){
                                    $article['nom']=$p->nom;
                                    $article['prix']=$p->tarif_unitaire;
                                    $article['quantite']=$q->Quantite;

                                    $InfosProducteur['articles'][]=$article;
                                }
                            }
                        }
                }
                $quantiteTotal=0;
                if(!empty($InfosProducteur)){
                    foreach ($InfosProducteur['articles'] as $articles){
                       $quantiteTotal= $quantiteTotal+$articles['quantite'];

                    }
                    $resultat=$resultat."<h3> ".$InfosProducteur['nom']." : ".count($InfosProducteur['articles']) ." Produit(s) / ".$quantiteTotal." Article(s) </h3>";
                    foreach ($InfosProducteur['articles'] as $articles){
                        $resultat=$resultat."<article><p>".$articles['nom']."</p><p>X".$articles['quantite']."</p><p>".$articles['quantite']*$articles['prix']." €</p></article>";
                    }

                }
                $quantiteTotalFull=$quantiteTotal+$quantiteTotalFull;
                $InfosProducteur=[];
            }


            $resultat=$resultat."<aside id='Total'><h3>Total</h3><p>X$quantiteTotalFull</p><p>$val->Montant €</p></aside>";
            if($val->Etat=='livré'){
                $resultat=$resultat."<div id='valide'><a href=".$router->urlFor('ValidPaiement',['id'=>$val->id]).">Valider le paiement</a></div><svg style='visibility: hidden; position: absolute;' width='0' height='0' xmlns='http://www.w3.org/2000/svg' version='1.1'>
                    <defs>
                        <filter id='goo'><feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />    
                            <feColorMatrix in='blur' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9' result='goo' />
                            <feComposite in='SourceGraphic' in2='goo' operator='atop'/>
                        </filter>
                    </defs>
                </svg>";
            }if($val->Etat=='en_cours'){
                $resultat=$resultat."<div id='valide' ><a href=".$router->urlFor('ValidLivraison',['id'=>$val->id]).">Valider la livraison</a></div><svg style='visibility: hidden; position: absolute;' width='0' height='0' xmlns='http://www.w3.org/2000/svg' version='1.1'>
                    <defs>
                        <filter id='goo'><feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />    
                            <feColorMatrix in='blur' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9' result='goo' />
                            <feComposite in='SourceGraphic' in2='goo' operator='atop'/>
                        </filter>
                    </defs>
                </svg>";
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
        $resultat=$resultat."<div id='tableauBordInfo'><article><img src='../../html/icons/customer.png' alt='Client'><h3>$nbUser Clients</h3></article>";
        $resultat=$resultat."<article><img src='../../html/icons/orders.png' alt='Commandes'><h3>$com Commandes</h3></article>";
        $resultat=$resultat."<article><img src='../../html/icons/money.png' alt='Money'><h3>$ca € De CA </h3></article></div>";
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
        return $resultat;
    }


}
