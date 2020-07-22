<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Kajian_model extends CI_Model {
 
    var $table = 'kajian';
    var $column_order = array('judul',null); //set column field database for datatable orderable
    var $column_search = array('judul'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();
    }
 
    private function _get_datatables_query()
    {
        //custom filter here
        if($this->input->post('kategori_id'))
        {
            $this->db->where('kategori_id', $this->input->post('kategori_id'));
        }
        if($this->input->post('ustadz_id'))
        {
            $this->db->where('ustadz_id', $this->input->post('ustadz_id'));
        }
        $this->db->select('kajian.*, kategori.nama as nama_kategori, ustadz.nama as nama_ustadz, pengguna.nama as post_by');
        $this->db->join('kategori', 'kategori.id = kajian.kategori_id');
        $this->db->join('ustadz', 'ustadz.id = kajian.ustadz_id');
        $this->db->join('pengguna', 'pengguna.id = kajian.pengguna_id');
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
 
        return $query->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
 
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function ustadz_list()
    {
        $this->db->select("id, nama");
        $this->db->from('ustadz');
        $data = $this->db->get();
        return $data;
    }

    public function kategori_list()
    {
        $this->db->select("id, nama");
        $this->db->from('kategori');
        $data = $this->db->get();
        return $data;
    }
 
 
}