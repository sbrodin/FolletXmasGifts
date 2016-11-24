<?php

class Offersto_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'offersto';
    }

    public function offers_to($user_id, $year)
    {
        $select = 'receiver';
        $where = array(
            'sender' => $user_id,
            'year' => $year
        );
        $receiver = $this->read($select, $where);

        if (empty($receiver)) {
            return FALSE;
        } else {
            return $receiver[0]->receiver;
        }
    }
}