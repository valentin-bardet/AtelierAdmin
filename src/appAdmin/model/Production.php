<?php
namespace appAdmin\model;

class Production extends \Illuminate\Database\Eloquent\Model {

    protected $table      = 'production';  /* le nom de la table */
    protected $primaryKey = '';     /* le nom de la clé primaire */
    public    $timestamps = false;    /* si vrai la table doit contenir
                                     les deux colonnes updated_at,
                                     created_at */
}
?>