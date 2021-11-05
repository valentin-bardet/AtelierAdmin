<?php

namespace appAdmin\control;

class AdminController extends \mf\control\AbstractController {

    public function __construct(){
        parent::__construct();
        
    }
    public function viewLogin(){
        $vue= new \appAdmin\view\AdminView(null);
        $vue->setAppTitle("LeHangar Gestion - Connexion");
        return $vue->render('Login');
    }
    public function viewProducteurHome(){
        $user = \appAdmin\model\User::where('Mail','=',$_SESSION['user_login']);
        $ligne = $user->get();
        $vue= new \appAdmin\view\AdminView($ligne);
        $vue->setAppTitle("LeHangar Gestion - Home");
        return $vue->render('HomeProducteur');
    }
    public function viewGerantHome(){
        $vue= new \appAdmin\view\AdminView(null);
        $vue->setAppTitle("LeHangar Gestion - Home");
        return $vue->render('HomeGerant');
    }
    public function viewAllCommandes(){
        $commandes = \appAdmin\model\Commande::select();
        $lignes = $commandes->get();
        $vue= new \appAdmin\view\AdminView($lignes);
        $vue->setAppTitle("LeHangar Gestion - Commandes");
        return $vue->render('AllCommandes');

    }
    public function viewTheCommande(){
        $commande_id=$_GET['id'];
        $commande = \appAdmin\model\Commande::where('id','=',$commande_id);;
        $lignes=$commande->get();
        $vue= new \appAdmin\view\AdminView($lignes);
        $vue->setAppTitle("LeHangar Gestion - Commande n°$commande_id");
        return $vue->render('TheCommande');
    }
    public function viewTableauDeBord(){
        $commandes = \appAdmin\model\Commande::select();
        $lignes=$commandes->get();
        $vue= new \appAdmin\view\AdminView($lignes);
        $vue->setAppTitle("LeHangar Gestion - Tableau de Bord");
        return $vue->render('TableauDeBord');
    }

    public function checkLogin(){
        // echo "test";
        $username=$_POST['user_name'];
        $mdp=$_POST['password'];
        $auth=new \appAdmin\auth\AdminAuthentification;
        $auth->loginUser($username, $mdp);
    }

    public function log_out(){
        $auth=new  \mf\auth\Authentification;
        $auth->logout();
        \mf\router\Router::executeRoute('login');
    }

    public function viewCommandes(){
        $vues=new \appAdmin\view\AdminView(null);
        return $vues->render('Commandes');

    }
    public function ValidLivraison(){
        $commandeID=$_GET['id'];
        $Commande=\appAdmin\model\Commande::where('id','=',$commandeID)->first();
        $Commande->Etat ='livré';
        $Commande->save();
        $router = new \mf\router\Router();
        header("Location: ".$router->urlFor('HomeGerant'));
    }
    public function ValidPaiement(){
        $commandeID=$_GET['id'];
        $Commande=\appAdmin\model\Commande::where('id','=',$commandeID)->first();
        $Commande->Etat ='payé';
        $Commande->save();
        $router = new \mf\router\Router();
        header("Location: ".$router->urlFor('HomeGerant'));
    }
    public function ViewNewProduit(){
    $usermail=$_SESSION['user_login'];
    $user=\appAdmin\model\User::where('Mail','=',$usermail);
    $userL=$user->get();
    $vue= new \appAdmin\view\AdminView($userL);
    return $vue->render('NewProduit');
    }
    public function ValidNewProduit(){
        $userID=$_POST['id'];
        $produitNom=$_POST['nom'];
        $produitPrix=$_POST['prix'];
        $produitDesc=$_POST['description'];
        $produitQuantite=$_POST['quantite'];
        $produitCategorie=$_POST['categorie'];
        $newProduct = new \appAdmin\model\Produits();
        $newProduct->nom=$produitNom;
        $newProduct->description=$produitDesc;
        $newProduct->Quantite=$produitQuantite;
        $newProduct->tarif_unitaire=$produitPrix;
        $newProduct->ID_categorie=$produitCategorie;
        $newProduct->Image='../../html/img/default.png';
        $newProduct->save();
        $production= new \appAdmin\model\Production();
        $production->ID_PRODUCTEUR=$userID;
        $production->ID_PRODUIT=$newProduct->id;
        $production->save();
        $router = new \mf\router\Router();
        header("Location: ".$router->urlFor('MesProduits'));
    }
    public function modifProduit(){
        $produitID=$_GET['id'];
        $produit=\appAdmin\model\Produits::where('id','=',$produitID);
        $produitLine=$produit->get();
        $vue= new \appAdmin\view\AdminView($produitLine);
        return $vue->render('modifProduit');
    }
    public function ValidmodifProduit(){
        $produitID=$_POST['id'];
        $produitNom=$_POST['nom'];
        $produitPrix=$_POST['prix'];
        $produitDesc=$_POST['description'];
        $produitQuantite=$_POST['quantite'];
        $produitCategorie=$_POST['categorie'];
        $produit=\appAdmin\model\Produits::where('id','=',$produitID)->first();
        $produit->nom=$produitNom;
        $produit->description=$produitDesc;
        $produit->tarif_unitaire=$produitPrix;
        $produit->ID_categorie=$produitCategorie;
        $produit->Quantite=$produitQuantite;
        $produit->save();
        $router = new \mf\router\Router();
        header("Location: ".$router->urlFor('MesProduits'));
    }
    public function viewMesProduits(){
        $mail=$_SESSION['user_login'];
        $userL=\appAdmin\model\User::where('Mail','=',$mail);
        $user=$userL->get() ;
        $vue= new \appAdmin\view\AdminView($user);
        $vue->setAppTitle("LeHangar Gestion - Mes Produits");
        return $vue->render('MesProduits');

    }
}
