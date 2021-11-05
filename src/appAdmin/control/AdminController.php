<?php

namespace appAdmin\control;

class AdminController extends \mf\control\AbstractController {

    public function __construct(){
        parent::__construct();
        
    }
    public function viewLogin(){
        $vue= new \appAdmin\view\AdminView(null);
        return $vue->render('Login');
    }
    public function viewProducteurHome(){
        $user = \appAdmin\model\User::where('Mail','=',$_SESSION['user_login']);
        $ligne = $user->get();
        $vue= new \appAdmin\view\AdminView($ligne);
        return $vue->render('HomeProducteur');
    }
    public function viewGerantHome(){
        $vue= new \appAdmin\view\AdminView(null);
        return $vue->render('HomeGerant');
    }
    public function viewAllCommandes(){
        $commandes = \appAdmin\model\Commande::select();
        $lignes = $commandes->get();
        $vue= new \appAdmin\view\AdminView($lignes);
        return $vue->render('AllCommandes');
    }
    public function viewTheCommande(){
        $commande_id=$_GET['id'];
        $commande = \appAdmin\model\Commande::where('id','=',$commande_id);;
        $lignes=$commande->get();
        $vue= new \appAdmin\view\AdminView($lignes);
        return $vue->render('TheCommande');
    }
    public function viewTableauDeBord(){
        $commandes = \appAdmin\model\Commande::select();
        $lignes=$commandes->get();
        $vue= new \appAdmin\view\AdminView($lignes);
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
        $vue= new \appAdmin\view\AdminView(null);
        return $vue->render('Commandes');
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
}
