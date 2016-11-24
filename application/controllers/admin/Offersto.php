<?php

class Offersto extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('family_model');
        $this->load->model('offersto_model');
        $this->load->model('config_model');
    }

    public function index()
    {
        if (!user_can('view_offersto')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('offersto_admin');

        $data['current_year'] = $this->config_model->get_config_db('current_year');
        $data['current_year_complete'] = $this->config_model->get_config_db('year_' . $data['current_year'] . '_complete');

        $select = 'year';
        $order = 'year ASC';
        $data['years'] = $this->db->select($select)
                                  ->distinct()
                                  ->from('offersto')
                                  ->order_by($order)
                                  ->get()
                                  ->result();
        $years = array();
        foreach ($data['years'] as $key => $year) {
            $years[$year->year] = $year->year;
        }
        if (!empty($years)) {
            $data['years'] = $years;
            $data['info'] = '';
        } else {
            $data['info'] = $this->lang->line('define_links_to_choose_year');
        }

        $select = 'sender,
                   receiver,
                   year,
                   user_sender.first_name AS sender_fn,
                   user_sender.last_name AS sender_ln,
                   user_receiver.first_name AS receiver_fn,
                   user_receiver.last_name AS receiver_ln';
        $where = array('year' => $data['current_year']);
        $order = 'year ASC, sender ASC';
        $data['links'] = $this->db->select($select)
                                  ->from('offersto')
                                  ->join('user user_sender', 'offersto.sender = user_sender.user_id')
                                  ->join('user user_receiver', 'offersto.receiver = user_receiver.user_id')
                                  ->where($where)
                                  ->order_by($order)
                                  ->get()
                                  ->result();

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'current_year',
                    'label' => $this->lang->line('current_year'),
                    'rules' => 'trim|required|greater_than_equal_to[2016]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'greater_than_equal_to' => $this->lang->line('must_be_year_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $this->config_model->set_config_db('current_year', $post['current_year']);
                $this->session->set_flashdata('success', $this->lang->line('current_year_successful_edition'));
                redirect(site_url('admin/offersto'), 'location');
                exit;
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/offersto/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {
        if (!user_can('add_offersto')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('add_link');

        $data['users'] = $this->user_model->read('user_id, first_name, last_name');
        $users = array();
        foreach ($data['users'] as $key => $user) {
            $users[$user->user_id] = $user->first_name . ' ' . $user->last_name;
        }
        $data['users'] = $users;

        $post = $this->input->post();
        if (!empty($post)) {
            // Si le lien existe déjà en base pour l'année entrée
            $where = array(
                'sender' => $post['sender'],
                'receiver' => $post['receiver'],
                'year' => $post['year'],
            );
            $already_exists = $this->offersto_model->read('*', $where);
            if (!empty($already_exists)) {
                $data['error_already_exists'] = $this->lang->line('duplicate_links');
            }
            // Si les 2 utilisateurs entrés sont identiques
            if ($post['sender'] == $post['receiver']) {
                $data['error_same_user'] = $this->lang->line('error_same_user');
            }
            $rules = array(
                array(
                    'field' => 'year',
                    'label' => $this->lang->line('year'),
                    'rules' => 'trim|required|greater_than_equal_to[2016]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'greater_than_equal_to' => $this->lang->line('must_be_year_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE && !isset($data['error_already_exists']) && !isset($data['error_same_user'])) {
                $donnees_echapees = array(
                    'sender' => $post['sender'],
                    'receiver' => $post['receiver'],
                    'year' => $post['year'],
                );
                $this->offersto_model->create($donnees_echapees);
                $this->session->set_flashdata('success', $this->lang->line('link_successful_creation'));
                redirect(site_url('admin/offersto'), 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/offersto/add', $data);
        $this->load->view('templates/footer', $data);
    }

    public function edit($family_id = 0)
    {
        if (!user_can('edit_offersto')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        if ($family_id === 0) {
            redirect(site_url(), 'location');
            exit;
        }

        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('edit_family');

        $select = 'family_id, name';
        $where = array(
            'family_id' => $family_id,
        );
        $family = $this->family_model->read($select, $where);
        if (!$family) {
            redirect(site_url(), 'location');
            exit;
        } else {
            $family = $family[0];
        }
        $data['family'] = $family;

        $post = $this->input->post();
        if (empty($post)) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/nav', $data);
            $this->load->view('admin/offersto/edit', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $rules = array(
                array(
                    'field' => 'family_name',
                    'label' => $this->lang->line('family_name'),
                    'rules' => 'trim|ucfirst|required|is_unique[family.name]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'is_unique' => $this->lang->line('already_in_db_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/nav', $data);
                $this->load->view('admin/offersto/edit', $data);
                $this->load->view('templates/footer', $data);
            } else {
                $where = array('family_id' => $family_id);
                $donnees_echapees = array(
                    'name' => $post['family_name'],
                );
                $this->family_model->update($where, $donnees_echapees);
                $this->session->set_flashdata('success', $this->lang->line('family_successful_edition'));
                redirect(site_url('admin/offersto'), 'location');
                exit;
            }
        }
    }

    public function draw_complete()
    {
        $this->config_model->set_config_db('year_' . $this->config_model->get_config_db('current_year') . '_complete', 'TRUE');
        $this->session->set_flashdata('success', $this->lang->line('draw_complete_successful'));
        redirect(site_url('admin/offersto'), 'location');
        exit;
    }

    public function draw_incomplete()
    {
        $this->config_model->set_config_db('year_' . $this->config_model->get_config_db('current_year') . '_complete', 'FALSE');
        $this->session->set_flashdata('success', $this->lang->line('draw_incomplete_successful'));
        redirect(site_url('admin/offersto'), 'location');
        exit;
    }
}
