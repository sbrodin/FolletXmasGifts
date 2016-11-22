<?php

/**
  * Classe étendue du controller MY_Controller qui se situe dans application/core/MY_Controller.php .
  * Cette classe définit les règles de création, lecture, mise à jour, acivation et désactivation des utilisateurs.
  */
class Users extends MY_Controller {

    private $username_min_length = 6;
    private $username_max_length = 255;
    private $password_min_length = 4;
    private $password_max_length = 255;

    /**
    * Constructeur qui appelle les models utilisés par le controller.
    */
    public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('family_model');
    }

    /**
    * Fonction d'affichage de tous les utilisateurs.
    */
    public function index() {
        // Gestion des droits de lecture
        if (!user_can('view_users')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }
        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('users_admin');
        $data['users'] = $this->user_model->read('*');
        $data['families'] = $this->family_model->read('*');
        $families = array();
        foreach ($data['families'] as $key => $family) {
            $families[$family->family_id] = $family->name;
        }
        $data['families'] = $families;

        foreach ($data['users'] as $users_item) {
            $users_item->active = $users_item->active ? $this->lang->line('yes') : $this->lang->line('no');
            $users_item->first_name = ($users_item->first_name === '') ? $this->lang->line('no_data') : $users_item->first_name;
            $users_item->last_name = ($users_item->last_name === '') ? $this->lang->line('no_data') : $users_item->last_name;
            $users_item->user_name = ($users_item->user_name === '') ? $this->lang->line('no_data') : $users_item->user_name;
            $add_date = new DateTime($users_item->add_date);
            $users_item->add_date_formatted = $add_date->format('d/m/Y H:i:s');
            if ($users_item->last_connection) {
                $last_connection = new DateTime($users_item->last_connection);
                $users_item->last_connection_formatted = $last_connection->format('d/m/Y H:i:s');
            } else {
                $users_item->last_connection_formatted = $this->lang->line('never_connected');
            }
            $users_item->score = isset($users_scores[$users_item->user_id]) ? $users_scores[$users_item->user_id] : 0;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/users/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction de création d'utilisateur.
    */
    public function create() {
        // Gestion des droits d'ajout
        if (!user_can('create_user')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }
        $data = array();
        $data['title'] = $this->lang->line('add_user');

        $data['families'] = $this->family_model->read('*');
        $families = array();
        foreach ($data['families'] as $key => $family) {
            $families[$family->family_id] = $family->name;
        }
        $data['families'] = $families;

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => $this->lang->line('email'),
                    'rules' => 'trim|strtolower|required|is_unique[user.email]|valid_email',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'is_unique' => $this->lang->line('already_in_db_field'),
                        'valid_email' => $this->lang->line('valid_email'),
                    ),
                ),
                array(
                    'field' => 'password',
                    'label' => $this->lang->line('password'),
                    'rules' => 'trim|required',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $donnees_echapees = array(
                    'acl' => 'user',
                    'active' => '1',
                    'first_name' => $post['first_name'],
                    'last_name' => $post['last_name'],
                    'family_id' => $post['family_id'],
                    'email' => $post['email'],
                    // 'password' => password_hash($post['password'], PASSWORD_BCRYPT),
                    'password' => $post['password'],
                    'add_date' => date('Y-m-d H:i:s'),
                );

                // Envoi d'email pour info
                $subject = 'FolletXmasGifts - Création de compte';
                $body = 'Un nouveau compte a été créé.<br/>';
                $body.= 'Email : ' . $post['email'];
                send_email_interception('stanislas.brodin@gmail.com', $subject, $body);

                $this->user_model->create($donnees_echapees);
                $this->session->set_flashdata('success', $this->lang->line('user_successful_creation'));
                redirect('admin/users', 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/users/create', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction de visualisation d'un utilisateur.
    * @param $userid Id de l'utilisateur à visualiser
    */
    /*public function view($userid) {
        // Gestion des droits de lecture
        if (!user_can('view_user')) {
            redirect(site_url(), 'location');
        }
        $data['user'] = $this->user_model->read('userid, username, email, isprivileged, isadmin, adddate, lastconnection', array("userid" => $userid))[0];

        // si l'utilisateur cherché n'existe pas ou qu'aucune donnée n'est renvoyée
        if(!$data['user']) {
            redirect(site_url('admin/users'), 'location');
        }

        $data['title'] = $this->lang->line('index_user');

        $data['user']->isprivileged = $data['user']->isprivileged ? $this->lang->line('yes') : $this->lang->line('no');
        $data['user']->isadmin = $data['user']->isadmin ? $this->lang->line('yes') : $this->lang->line('no');
        $data['user']->adddate = DateTime::createFromFormat("Y-m-d H:i:s", $data['user']->adddate);
        $data['user']->adddate = $data['user']->adddate->format("d/m/Y H:i:s");

        if($data['user']->lastconnection) {
            $data['user']->lastconnection = DateTime::createFromFormat("Y-m-d H:i:s", $data['user']->lastconnection);
            $data['user']->lastconnection = $data['user']->lastconnection->format("d/m/Y H:i:s");
        } else {
            $data['user']->lastconnection = $this->lang->line('never_connected');
        }

        $this->load->view('templates/header_admin', $data);
        $this->load->view('admin/users/view', $data);
        $this->load->view('templates/footer');
    }

    /**
    * Fonction de mise à jour d'un utilisateur.
    * @param $user_id Id de l'utilisateur à mettre à jour
    */
    public function edit($user_id) {
        // Gestion des droits de mise à jour
        if (!user_can('edit_user')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
        }
        $data = array();
        $select = 'first_name,
                   last_name,
                   family_id,
                   email,
                   password';
        $data['user'] = $this->user_model->read($select, array("user_id" => $user_id))[0];

        // si l'utilisateur cherché n'existe pas ou qu'aucune donnée n'est renvoyée
        if(!$data['user']) {
            redirect(site_url('admin/users'), 'location');
        }

        $data['title'] = $this->lang->line('edit_user');
        $data['user_id'] = $user_id;

        $data['families'] = $this->family_model->read('*');
        $families = array();
        foreach ($data['families'] as $key => $family) {
            $families[$family->family_id] = $family->name;
        }
        $data['families'] = $families;

        $rules = array(
            array(
                'field' => 'first_name',
                'label' => $this->lang->line('first_name'),
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => $this->lang->line('required_field'),
                ),
            ),
            array(
                'field' => 'last_name',
                'label' => $this->lang->line('last_name'),
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => $this->lang->line('required_field'),
                ),
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line('email'),
                'rules' => 'trim|strtolower|required|valid_email',
                'errors' => array(
                    'required' => $this->lang->line('required_field'),
                    'is_unique' => $this->lang->line('already_in_db_field'),
                    'valid_email' => $this->lang->line('valid_email'),
                ),
            ),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() !== FALSE) {
            $donnees_echapees = array();
            if ($this->input->post('password')) {
                $donnees_echapees['password'] = $this->input->post('password');
                $donnees_echapees['first_connection'] = 1;
            }
            $donnees_echapees['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : '';
            $donnees_echapees['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : '';
            $donnees_echapees['family_id'] = $this->input->post('family_id') ? $this->input->post('family_id') : 1;
            $donnees_echapees['email'] = $this->input->post('email') ? $this->input->post('email') : '';

            $donnees_non_echapees = array();

            $this->user_model->update(array("user_id" => $user_id), $donnees_echapees, $donnees_non_echapees);

            $this->session->set_flashdata('success', $this->lang->line('user_successful_edition'));
            redirect('admin/users', 'location');
            exit;
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/users/edit', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
    * Fonction d'activation d'un utilisateur.
    * @param $user_id Id de l'utilisateur à activer
    */
    public function activate($user_id) {
        // Gestion des droits d'activation
        if (!user_can('activate_user')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $donnees_echapees = array('active' => 1);

        $this->user_model->update(array("user_id" => $user_id), $donnees_echapees);

        redirect(site_url('admin/users'), 'location');
        exit;
    }

    /**
    * Fonction de désactivation d'un utilisateur.
    * @param $user_id Id de l'utilisateur à désactiver
    */
    public function deactivate($user_id) {
        // Gestion des droits de désactivation
        if (!user_can('deactivate_user')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $donnees_echapees = array('active' => 0);

        $this->user_model->update(array("user_id" => $user_id), $donnees_echapees);

        redirect(site_url('admin/users'), 'location');
        exit;
    }
}