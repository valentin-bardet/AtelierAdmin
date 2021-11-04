<?php
namespace mf\utils;

class ClassLoader extends AbstractClassLoader{      

        public function __construct($file_root){
                parent::__construct($file_root);
        }

        public function getFilename(string $classname): string{
            $resultat="";
            $resultat = str_replace("\\",DIRECTORY_SEPARATOR,$classname);
            $resultat = $resultat.".php";
            return $resultat;
        }

        public function makePath(string $filename): string{
            return $this->prefix.DIRECTORY_SEPARATOR.$filename;
        }

        public function loadClass(string $classname){
           $var = $this->getFilename($classname) ;
           $chemin_complet=$this->makePath($var);

           if(file_exists($chemin_complet)){
               require_once $chemin_complet;
           }
        }
    }