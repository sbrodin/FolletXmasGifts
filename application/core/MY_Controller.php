<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  * Classe étendue du controller CI
  *
  * L'extension de la classe de base permet de vérifier que l'utilisateur est connecté
  * et active le profiler en environnement de dev.
  */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Chargement du fichier de langue
        $this->lang->load('FolletXmasGifts', 'french');

        // Authentification de l'utilisateur
        if (!is_connected()) {
            // Redirige l'utilisateur vers la page de connexion s'il n'est pas authentifié
            redirect(site_url('connection'), 'location');
        }
    }
}