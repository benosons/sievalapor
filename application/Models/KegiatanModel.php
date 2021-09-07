<?php namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model{
    protected $table = 'data_kegiatan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_program','kode_kegiatan','nama_kegiatan','created_by','updated_by','create_date','update_date'];
    protected $createdField  = 'create_date';
    protected $updatedField  = 'update_date';

    public function getKegiatan($code = null, $code1 = null)
    {
          
          $builder = $this->db->table('data_kegiatan');
          if($code){
            $query   = $builder->getWhere(['kode_program' => $code]);
          }else if($code1){
            $query   = $builder->getWhere(['kode_kegiatan' => $code1]);
          }else{
            $query   = $builder->get();
          }
          return  $query->getResult();
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

    public function getpaket($code = null, $userid = null)
    {
          if($code){
            $sql = "SELECT * FROM `data_paket` WHERE `kode_subkegiatan` = '$code' and
                    id not in (select id_paket from data_realisasi where id_paket = data_paket.id ) and created_by = '$userid'";

            $result = $this->db->query($sql);
            $row = $result->getResult();
            return $row;
          }else{
            $builder = $this->db->table('data_paket');
            $query   = $builder->getWhere(['created_by' => $userid]);
            // $query   = $builder->get();
          }
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function saveParam($table = null, $data = null)
    {
        return  $this->db->table($table)->insert($data);
    }

    public function updatePaguSub($kodeprog = null, $kodekeg = null, $kodesubkeg = null, $pagunya = null)
    {   
        $builder = $this->db->table('data_subkegiatan');
        $builder->set('sisa_pagu_subkegiatan', $pagunya );
        $builder->where('kode_program', $kodeprog);
        $builder->where('kode_kegiatan', $kodekeg);
        $builder->where('kode_subkegiatan', $kodesubkeg);
        $builder->update();
        // echo $this->db->getLastQuery();die;
        return  true;
    }

    public function getpaketbyid($id = null, $userid = null)
    {
         
            $builder = $this->db->table('data_paket');
            $query   = $builder->getWhere(['created_by' => $userid, 'id' => $id]);
            // $query   = $builder->get();
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function getsubkegbyid($id = null, $userid = null)
    {
         
            $builder = $this->db->table('data_subkegiatan');
            $query   = $builder->getWhere(['id' => $id]);
            // $query   = $builder->get();
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function getkegbyid($id = null, $userid = null)
    {
         
            $builder = $this->db->table('data_kegiatan');
            $query   = $builder->getWhere(['id' => $id]);
            // $query   = $builder->get();
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function getprogbyid($id = null, $userid = null)
    {
         
            $builder = $this->db->table('data_program');
            $query   = $builder->getWhere(['id' => $id]);
            // $query   = $builder->get();
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function getsubaja($kodeprog = null, $kodekeg = null, $kodesubkeg = null)
    {
         
            $builder = $this->db->table('data_subkegiatan');
            $query   = $builder->getWhere(['kode_program' => $kodeprog, 'kode_kegiatan' => $kodekeg, 'kode_subkegiatan' => $kodesubkeg]);
            // $query   = $builder->get();
          // echo $this->db->getLastQuery();die;
          return  $query->getResult();
    }

    public function deletePaket($kodeprog = null,$kodekeg = null,$kodesubkeg = null)
    {
      
        $builder = $this->db->table('data_paket');
        $builder->where(['kode_program' => $kodeprog, 'kode_kegiatan' => $kodekeg, 'kode_subkegiatan' => $kodesubkeg]);
        $builder->delete();
        return true;
    }

    public function deletePaketByprogkeg($kodeprog = null,$kodekeg = null)
    {
      
        $builder = $this->db->table('data_paket');
        $builder->where(['kode_program' => $kodeprog, 'kode_kegiatan' => $kodekeg]);
        $builder->delete();
        return true;
    }

    public function deletesubkeg($kodeprog = null,$kodekeg = null)
    {
        $builder = $this->db->table('data_subkegiatan');
        $builder->where(['kode_program' => $kodeprog, 'kode_kegiatan' => $kodekeg]);
        $builder->delete();
        return true;
    }

    public function deletekegbyprog($kodeprog = null)
    {
        $builder = $this->db->table('data_kegiatan');
        $builder->where(['kode_program' => $kodeprog]);
        $builder->delete();
        return true;
    }

    public function deletesubkegbyprog($kodeprog = null)
    {
        $builder = $this->db->table('data_subkegiatan');
        $builder->where(['kode_program' => $kodeprog]);
        $builder->delete();
        return true;
    }

    public function deletePaketByprog($kodeprog = null)
    {
        $builder = $this->db->table('data_paket');
        $builder->where(['kode_program' => $kodeprog]);
        $builder->delete();
        return true;
    }

    public function updateParam($table = null, $id = null, $data = null)
    {   
      $builder = $this->db->table($table);
      $query   = $builder->where('id', $id);
      $query->update($data);
      echo $this->db->getLastQuery();die;
      return true;
    }

    

}
