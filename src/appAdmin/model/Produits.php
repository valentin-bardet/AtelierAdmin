<?php
namespace appAdmin\model;

class Produits extends \Illuminate\Database\Eloquent\Model {

    protected $table      = 'produit';  /* le nom de la table */
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;    /* si vrai la table doit contenir
                                     les deux colonnes updated_at,
                                     created_at */
}
?>