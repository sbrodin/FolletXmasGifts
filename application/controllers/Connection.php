<?php

/**
  * Cette classe définit les règles de gestion des acl, de connexion et de déconnexion.
  */
class Connection extends CI_Controller {

    // Gestion des acl
    public $admin_acl = array(
        // acl pour admin
        'admin_all',
        // acl pour admin users
        'view_users',
        'create_user',
        'view_user',
        'edit_user',
        'activate_user',
        'deactivate_user',
        // acl pour admin family
        'view_families',
        'add_family',
        'edit_family',
        // acl pour admin offersto
        'view_offersto',
        'add_offersto',
        'edit_offersto',
        // acl pour wish
        'add_wish',
        'view_wishes',
        'view_wish',
        'edit_wish',
        'delete_wish',
    );
    public $user_acl = array(
        // acl pour wish
        'add_wish',
        'view_wishes',
        'view_wish',
        'edit_wish',
        'delete_wish',
    );

    /**
    * Constructeur qui appelle les models utilisés par le controller
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        // Chargement du fichier de langue
        $this->lang->load('FolletXmasGifts', 'french');
    }

    /**
    * Fonction d'affichage de la page de connexion.
    */
    public function index() {
        $data = array();
        $data['title'] = $this->lang->line('log_in');
        if (!empty($this->input->get()) && $this->input->get('url')!==NULL) {
            $url = urlencode($this->input->get('url'));
        } else {
            $url = '';
        }
        $data['url'] = $url;

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => $this->lang->line('email'),
                    'rules' => 'required|valid_email',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'valid_email' => $this->lang->line('valid_email'),
                    ),
                ),
                array(
                    'field' => 'password',
                    'label' => $this->lang->line('password'),
                    'rules' => 'required',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $this->login($url);
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('login', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction d'affichage de la page de changement de
    * mot de passe à la première connexion.
    */
    public function first_connection() {
        $data = array();
        $data['title'] = $this->lang->line('first_connection');

        if (get_cookie('folletxmasgifts_connection_email')) {
            $data['email'] = get_cookie('folletxmasgifts_connection_email');
        } else {
            $this->session->set_flashdata('error', $this->lang->line('deactivated_account'));
            redirect(site_url(), 'location');
            exit;
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'new_password',
                    'label' => $this->lang->line('new_password'),
                    'rules' => 'trim|required',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                    ),
                ),
                array(
                    'field' => 'new_password_confirmation',
                    'label' => $this->lang->line('new_password_confirmation'),
                    'rules' => 'trim|required|matches[new_password]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'matches' => $this->lang->line('must_match_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $where = array(
                    'email' => $post['email'],
                );
                $donnees_echapees = array(
                    'password' => password_hash($post['new_password'], PASSWORD_BCRYPT),
                    'first_connection' => 0,
                );
                $this->user_model->update($where, $donnees_echapees);
                delete_cookie('folletxmasgifts_connection_email');
                $this->session->set_flashdata('success', $this->lang->line('password_saved'));
                redirect(site_url('connection'), 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('first_connection', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction de connexion.
    * Cette fonction stocke en session les acl en fonction des privilèges récupérés en base de l'utilisateur.
    */
    public function login($url = '') {
        // Récupère les données envoyées par le formulaire
        $post = $this->input->post();
        if (empty($post) || !$post['email'] || !$post['password']) {
            redirect(site_url('connection'), 'location');
            exit;
        }

        // Cas de la redirection depuis la page d'accueil
        if (!empty($this->input->get()) && $this->input->get('url')!==NULL) {
            $url = urlencode($this->input->get('url'));
        }

        if ($user = $this->user_model->get_user_by_auth($post['email'], $post['password'])) {
            if ($user->first_connection) {
                $this->input->set_cookie('folletxmasgifts_connection_email', $post['email'], 3600*24*30, '', '/', '', FALSE, TRUE);
                $this->session->set_flashdata('info', $this->lang->line('change_password_first_connection'));
                redirect(site_url('connection/first_connection'), 'location');
                exit;
            }

            if ($user->active == 0) {
                $this->session->set_flashdata('error', $this->lang->line('deactivated_account'));
                redirect(site_url('connection'), 'location');
                exit;
            }

            $donnees_echapees = array(
                'last_connection' => date("Y-m-d H:i:s"),
                'hash' => NULL,
                'date_hash' => NULL,
            );

            $this->user_model->update(array("user_id" => $user->user_id), $donnees_echapees);

            $this->session->set_userdata('user', $user);
            if ($user->acl === 'admin') {
                $this->session->set_userdata('acl', $this->admin_acl);
            } else if ($user->acl === 'moderator') {
                $this->session->set_userdata('acl', $this->moderator_acl);
            } else {
                $this->session->set_userdata('acl', $this->user_acl);
            }
            // Définition du cookie de connexion pour 30 jours
            $this->input->set_cookie('folletxmasgifts_connected', 'TRUE', 3600*24*30, '', '/', '', FALSE, TRUE);
            if ($url !== '') {
                redirect(site_url(urldecode($url)), 'location');
                exit;
            } else {
                redirect(site_url(), 'location');
                exit;
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('incorrect_login'));
            redirect(site_url('connection'), 'location');
            exit;
        }
    }

    /**
    * Fonction d'oubli de mot de passe.
    */
    public function forgotten_password() {
        $data = array();
        $data['title'] = $this->lang->line('forgotten_password');

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => $this->lang->line('email'),
                    'rules' => 'required|valid_email|in_database_email',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'valid_email' => $this->lang->line('valid_email'),
                        'in_database_email' => $this->lang->line('not_in_database_email'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $where = array('email' => $post['email']);
                $hash = random_string('alnum', 255);
                $donnees_echapees = array(
                        'hash' => $hash,
                        'date_hash' => date('Y-m-d H:i:s'),
                    );
                $this->user_model->update($where, $donnees_echapees);

                $subject = 'FolletXmasGifts - Mot de passe oublié';
                $body = 'Pour réinitialiser votre mot de passe, veuillez cliquer sur <a href="' . site_url('reset_password/' . $hash) . '">ce lien</a>';
                send_email_interception($post['email'], $subject, $body);

                $this->session->set_flashdata('info', $this->lang->line('reset_password_email_sent'));
                redirect(site_url('connection'), 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('forgotten_password', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction de réinitialisation du mot de passe
    * Cette fonction permet de réinitialiser son mot de passe à partir d'un lien reçu dans un email
    */
    public function reset_password($hash)
    {
        $data = array();
        $data['title'] = $this->lang->line('reset_password');
        $data['hash'] = $hash;

        $user = $this->user_model->read('*', array('hash'=>$hash));
        // Si le hash n'existe pas en base
        if (!$user) {
            redirect(site_url(), 'location');
            exit;
        } else {
            $user = $user[0];
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'new_password',
                    'label' => $this->lang->line('new_password'),
                    'rules' => 'trim|required|min_length[8]|contains_uppercase|contains_lowercase|contains_number',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'min_length' => $this->lang->line('min_length_field'),
                        'contains_uppercase' => $this->lang->line('must_contain_uppercase_field'),
                        'contains_lowercase' => $this->lang->line('must_contain_lowercase_field'),
                        'contains_number' => $this->lang->line('must_contain_number_field'),
                    ),
                ),
                array(
                    'field' => 'new_password_confirmation',
                    'label' => $this->lang->line('new_password_confirmation'),
                    'rules' => 'trim|required|matches[new_password]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'matches' => $this->lang->line('must_match_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $where = array(
                    'hash' => $hash,
                );
                $donnees_echapees = array(
                    'password' => password_hash($post['new_password'], PASSWORD_BCRYPT),
                    'hash' => NULL,
                    'date_hash' => NULL,
                );
                $this->user_model->update($where, $donnees_echapees);
                redirect(site_url('connection'), 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('reset_password', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction de déconnexion.
    * Cette fonction supprime les données de session.
    */
    public function logout() {
        if (!empty($this->session->userdata['user'])) {
            $this->session->unset_userdata('user');
        }
        if (!empty($this->session->userdata['acl'])) {
            $this->session->unset_userdata('acl');
        }
        delete_cookie('folletxmasgifts_connected');
        redirect(site_url(''), 'location');
        exit;
    }
}