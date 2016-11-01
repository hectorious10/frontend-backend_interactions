<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User_model extends CI_Model
{
    protected $_table = 'user';
    protected $_pimary_key = 'user_id';

    public function dataset($_table){
        $fields = $this->db->list_fields($_table);
        return $fields[0];
    }

    public function get_user($id = null, $table = null){

        if(is_numeric($id)){
            $this->db->where($this->_primary_key, $id);
        }

        if(is_array($id)){
            foreach($id as $_key => $_value){
                $this->db->where($_key, $_value);
            }
        }
        $q = $this->db->get($table);
        return $q->result_array();
    }

    public function get_list($id = null, $table = null){
        if(is_numeric($id)){
            $this->db->where('cid', $id);
        }
        $this->db->where('cdel', 0);
        $this->db->order_by('cid', "desc");
        $q = $this->db->get($table);
        return $q->result_array();
    }

    public function get_art($id = null, $loc = null){
        $this->db->select('*');
        $this->db->join('article', 'article.aid = cl_art.artid', 'left');
        if($loc != null){
            $this->db->where('aloc', $loc);
        }
        $this->db->where('arcldid', $id);
        $q = $this->db->get('cl_art');
        return $q->result_array();
    }

    public function get_allart($id = null){
        if($id != null){
            $this->db->where('aloc', $id);
        }
        $q = $this->db->get('article');
        return $q->result_array();
    }

    public function get_alldata($id = null, $loc = null){
        $this->db->select('*');
        $this->db->join('cl_dat', 'cl_dat.cldclt = cl_info.cid', 'inner');
        $this->db->join('cl_art', 'cl_art.arcldid = cl_dat.cldid', 'inner');
        $this->db->join('article', 'article.aid = cl_art.artid', 'inner');
        $this->db->where('cdel', '0');
        $this->db->where('cid', $id);
        if($loc != null){$this->db->where('aloc', $loc); }
        $q = $this->db->get('cl_info');
        return $q->result_array();
    }
    public function insquot($data)
    {
        $this->db->insert('cl_info', $data);
        return $this->db->insert_id();
    }
    public function insdat($data, $usr)
    {
        $this->db->set('cldclt', $usr );
        $this->db->insert('cl_dat', $data);
        return $this->db->insert_id();
    }
    public function insart($data, $usr)
    {
        if(is_array($data)){
            foreach($data as $value){
                $val = explode('-',$value);
                $this->db->set('artid',$val[0]);
                $this->db->set('arct', $val[1]);
                $this->db->set('arcldid', $usr );
                $this->db->insert('cl_art');
            }
        }
        return $this->db->insert_id();
    }

    public function updatelog($data)
    {
        $created = date('Y-m-d H:i:s');
        $this->db->set('ulog', $created );
        $this->db->where('uid', $data);
        $this->db->update('users');
    }

    /**
     *
     * @param type $data
     * @param type $user_id
     * @return type
     */
    public function updateinv($ordid = null , $values = null, $art = null)
    {
        $this->db->select('aid')->where('anm', $art);
        $answ = $this->db->get('article');
        foreach($answ->result_array() as $row){
            $arid = $row['aid'];
        }
        $this->db->where('arcldid', $ordid)->where('artid', $arid);
        $result = $this->db->get('cl_art');
        if ($result->num_rows() > 0){
            if($values == '0'){
                $this->db->where('arcldid', $ordid)->where('artid', $arid);
                $this->db->delete('cl_art');
                return $this->db->affected_rows();
            }else{
                $this->db->where('arcldid', $ordid)->where('artid', $arid)->set('arct', $values);
                $this->db->update('cl_art');
                return $this->db->affected_rows();
            }
        }else{
            $this->db->set('arcldid', $ordid)->set('artid', $arid)->set('arct', $values);
            $this->db->insert('cl_art');
            return $this->db->affected_rows();
        }
    }

    public function updateinvxt($ordid = null ,$data = null)
    {
        $this->db->where('cldid', $ordid);
        $this->db->update('cl_dat', $data);
        return $this->db->affected_rows();
    }
    public function updatenfo($ordid = null ,$data = null)
    {
        $this->db->where('cid', $ordid);
        $this->db->update('cl_info', $data);
        return $this->db->affected_rows();
    }
    /**
     *
     * @param type $user_id
     * @return type
     * @usage $result = $this->user_model->delete(3);
     */
    public function delete($id = 'null')
    {
        $this->db->where('cid', $id);
        $this->db->delete('cl_info');
        return $this->db->affected_rows();
    }
}