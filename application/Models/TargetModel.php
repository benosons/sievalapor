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
      if($code){
        $sql = "SELECT dt.*, dp.nama_paket as nama_paket, bt.*, dsk.nama_subkegiatan, dk.nama_kegiatan, dpo.nama_program, u.user_fullname as nama_ppk
                FROM data_target dt
                inner join data_paket dp on dp.id = dt.id_paket
								inner join data_subkegiatan dsk on dsk.kode_subkegiatan = dt.kode_subkegiatan
								inner join data_kegiatan dk on dk.kode_kegiatan = dt.kode_kegiatan
								inner join data_program dpo on dpo.kode_program = dt.kode_program
                inner join bulan_target bt on bt.id_paket = dt.id_paket
                inner join users u on u.user_id = dt.ppk where dt.id = '$code'";

        $result = $this->db->query($sql);
        $row = $result->getResult();
        return $row;
      }

      $sql = "SELECT dt.*, dp.nama_paket as nama_paket FROM `data_target` dt
              inner join data_paket dp on dp.id = dt.id_paket";

      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function gettargetNip($code = null)
    {
      if($code){
        $sql = "SELECT dt.*, u.*, dp.nama_paket as nama_paket, bt.*, dsk.nama_subkegiatan, dk.nama_kegiatan, dpo.nama_program
                FROM data_target dt
                inner join data_paket dp on dp.id = dt.id_paket
								inner join data_subkegiatan dsk on dsk.kode_subkegiatan = dt.kode_subkegiatan
								inner join data_kegiatan dk on dk.kode_kegiatan = dt.kode_kegiatan
								inner join data_program dpo on dpo.kode_program = dt.kode_program
                inner join bulan_target bt on bt.id_paket = dt.id_paket
                inner join users u on u.user_id = dt.ppk where dt.id = '$code'";

        $result = $this->db->query($sql);
        $row = $result->getResult();
        return $row;
      }

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
        $res = $this->db->table($table)->insert($data);
        // echo $this->db->getLastQuery();die;
        return  $res;
    }

    public function getminggu($type = null, $code = null)
    {
      $str = substr($code, -1);
      $last = $str - 1;
      $toto = '';
      $now = '';
      if($type == 'keuangan'){
        $toto = "(select sum(replace(m1, '.','')) + sum(replace(m2, '.','')) + sum(replace(m3, '.','')) + sum(replace(m4, '.','')) from bulan_realisasi where type = '$type' and kode_bulan = 'n$last' ORDER BY id DESC LIMIT 0, 1)";
        $now  = ",(select sum(replace(m1, '.','')) + sum(replace(m2, '.','')) + sum(replace(m3, '.','')) + sum(replace(m4, '.','')) from bulan_realisasi where type = '$type' and kode_bulan = '$code' ORDER BY id DESC LIMIT 0, 1) as totalnya";
      }else if($type == 'fisik'){
        $toto = "(select total from bulan_realisasi where type = '$type' and kode_bulan = 'n$last' ORDER BY id DESC LIMIT 0, 1)";
      }

      $sql = "SELECT *, $toto as total_sebelumnya $now from bulan_realisasi where type = '$type' and kode_bulan = '$code'";
      // print_r($sql);die;
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function updateDong($table = null, $id = null, $data = null)
    {

      $builder = $this->db->table($table);
      $query   = $builder->where('id', $id);
      $query->update($data);
      // echo $this->db->getLastQuery();die;
      return true;
    }

    public function getnip($code = null)
    {

      $sql = "SELECT dt.*, dp.nama_paket as nama_paket, u.* FROM `data_target` dt
              inner join data_paket dp on dp.id = dt.id_paket
              inner join users u on u.user_id = dt.ppk";

      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function getrealisasi($id_paket = null, $ppk = null, $type = null, $kodebulan = null)
    {

      $sql = "SELECT kode_bulan, m1, m2, m3, m4 ,koordinat, latar_belakang, uraian, permasalahan from bulan_realisasi where id_paket = '$id_paket' and created_by = '$ppk' and type = '$type' and kode_bulan = '$kodebulan'";

      $result = $this->db->query($sql);
      $row = $result->getResult();
      $val = [];
      $toto = [];
      if((array)$row){

        if($type == 'fisik'){
          foreach ($row as $key => $value) {

            if($value->m1){
              $val['m1'] = $value->m1;
              $val['tot'] = $value->m1;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;

            }
            if($value->m2){
              $val['m2'] = $value->m2;
              $val['tot'] = $value->m2;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;

            }
            if($value->m3){
              $val['m3'] = $value->m3;
              $val['tot'] = $value->m3;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;

            }
            if($value->m4){
              $val['m4'] = $value->m4;
              $val['tot'] = $value->m4;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;

            }
          }


        }else{
          foreach ($row as $key => $value) {
            if($value->m1){
              $toto['m1'] = $value->m1;
              $val['m1'] = $value->m1;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;
            }
            if($value->m2){
              $toto['m2'] = $value->m2;
              $val['m2'] = $value->m2;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;
            }
            if($value->m3){
              $toto['m3'] = $value->m3;
              $val['m3'] = $value->m3;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;
            }
            if($value->m4){
              $toto['m4'] = $value->m4;
              $val['m4'] = $value->m4;
              $val['koordinat'] = $value->koordinat;
              $val['latar_belakang'] = $value->latar_belakang;
              $val['uraian'] = $value->uraian;
              $val['permasalahan'] = $value->permasalahan;
            }
          }

          $sum = 0;
          foreach($toto as $key => $value){
               $sum += (int)str_replace('.','',$toto[$key]);
          }
          $val['tot'] =$sum;

        }

      }

      return (object)$val;
    }

}
