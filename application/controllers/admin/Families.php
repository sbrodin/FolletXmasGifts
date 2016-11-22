<?php

class Families extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('family_model');
    }

    public function index()
    {
        if (!user_can('view_families')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('families_admin');

        $select = 'family_id, name';
        $where = array();
        $nb = NULL;
        $debut = NULL;
        $order = 'name ASC';
        $data['families'] = $this->family_model->read($select, $where, $nb, $debut, $order);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/families/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {
        if (!user_can('add_family')) {
            $this->session->set_flashdata('warning', sprintf($this->lang->line('no_correct_rights_log_again'), site_url('logout')));
            redirect(site_url(), 'location');
            exit;
        }

        $data = array();
        $data['title'] = $this->lang->line('admin') . ' - ' . $this->lang->line('add_family');

        $post = $this->input->post();
        if (!empty($post)) {
            $rules = array(
                array(
                    'field' => 'family_name',
                    'label' => $this->lang->line('family_name'),
                    'rules' => 'trim|required|is_unique[family.name]',
                    'errors' => array(
                        'required' => $this->lang->line('required_field'),
                        'is_unique' => $this->lang->line('already_in_db_field'),
                    ),
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== FALSE) {
                $donnees_echapees = array(
                    'name' => $post['family_name'],
                );
                $this->family_model->create($donnees_echapees);
                $this->session->set_flashdata('success', $this->lang->line('family_successful_creation'));
                redirect(site_url('admin/families'), 'location');
                exit;
            }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('admin/families/add', $data);
        $this->load->view('templates/footer', $data);
    }

    public function edit($family_id = 0)
    {
        if (!user_can('edit_family')) {
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
            $this->load->view('admin/families/edit', $data);
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
                $this->load->view('admin/families/edit', $data);
                $this->load->view('templates/footer', $data);
            } else {
                $where = array('family_id' => $family_id);
                $donnees_echapees = array(
                    'name' => $post['family_name'],
                );
                $this->family_model->update($where, $donnees_echapees);
                $this->session->set_flashdata('success', $this->lang->line('family_successful_edition'));
                redirect(site_url('admin/families'), 'location');
                exit;
            }
        }
    }
}
