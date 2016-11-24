<?php

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('offersto_model');
        $this->load->model('config_model');
    }

    public function index()
    {
        $data = array();
        $data['title'] = $this->lang->line('home');

        $user_id = $this->session->userdata['user']->user_id;
        $current_year = $this->config_model->get_config_db('current_year');
        $receiver = $this->offersto_model->offers_to($user_id, $current_year);

        if (!empty($receiver)) {
            $select = 'first_name, last_name';
            $where = array('user_id' => $receiver);
            $nb = 1;
            $receiver = $this->user_model->read($select, $where, $nb)[0];
            $offers_to = $receiver->first_name . ' ' . $receiver->last_name;
            $data['info'] = sprintf($this->lang->line('you_offer_to'), $offers_to);
        } else {
            $data['info'] = $this->lang->line('no_draw_yet');
        }
        // var_dump($data['receiver']);
        // exit;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav', $data);
        $this->load->view('index', $data);
        $this->load->view('templates/footer', $data);
    }
}
