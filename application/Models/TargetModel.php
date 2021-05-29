<?php namespace App\Models;

use CodeIgniter\Model;

class TargetModel extends Model{
    protected $table = 'data_target';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_program','kode_kegiatan','nama_kegiatan','created_by','updated_by','create_date','update_date'];
    protected $createdField  = 'create_date';
    protected $updatedField  = 'update_date';

    public function gettarget($code = null)
    {

      $sql = "SELECT dt.*, dp.nama_paket as nama_paket FROM `data_target` dt
              inner join data_paket dp on dp.id = dt.id_paket";

      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function getsubKegiatan($code = null)
    {
          $builder = $this->db->table('data_subkegiatan');
          if($code){
            $query   = $builder->getWhere(['kode_kegiatan' => $code]);
          }else{
            $query   = $builder->get();
          }
          return  $query->getResult();
    }

    public function getpaket($code = null)
    {
          $builder = $this->db->table('data_paket');
          if($code){
            $query   = $builder->getWhere(['kode_subkegiatan' => $code]);
          }else{
            $query   = $builder->get();
          }
          return  $query->getResult();
    }

    public function saveParam($table = null, $data = null)
    {
        return  $this->db->table($table)->insert($data);
    }

}
