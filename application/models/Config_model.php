<?php

class Config_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'config';
    }

    public function get_config_db($name)
    {
        $select = 'value';
        $where = array('name' => $name);
        $config = $this->read($select, $where);

        if (empty($config)) {
            return FALSE;
        } else {
            return $config[0]->value;
        }
    }

    public function set_config_db($name = '', $value = '')
    {
        if ($name == '' || $value == '') {
            return FALSE;
        }
        // Si la config existe déjà en base, on la met à jour, sinon on l'ajoute
        if ($this->get_config_db($name)) {
            $donnees_echappees = array('value' => $value);
            $where = array('name' => $name);
            $this->update($where, $donnees_echappees);
        } else {
            $donnees_echappees = array(
                'name' => $name,
                'value' => $value
            );
            $this->create($donnees_echappees);
        }
    }
}