<?php namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;

class Jsondata extends \CodeIgniter\Controller
{
	protected $session;
	protected $request;

  function __construct(RequestInterface $request)
  {
      $this->session = session();
			$this->now = date('Y-m-d H:i:s');
			$this->request = $request;
      $this->logged = $this->session->get('logged_in');
			$this->data = array(
				'version' => \CodeIgniter\CodeIgniter::CI_VERSION,
				'baseURL' => BASE.'/public',
				// 'baseURL' => BASE,
				'userid' => $this->session->get('user_id'),
				'username' => $this->session->get('user_name'),
				'role' => $this->session->get('user_role'),
				'satuan' => $this->session->get('user_satuan'),
				'nip' => $this->session->get('nip'),
			);
  }

	public function getpengaduan()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$user_satuan		= $this->data['satuan'];

				if($this->logged){
					$model = new \App\Models\PengaduanModel();
					$modelfiles = new \App\Models\FilesModel();
					if($role == 100){
						$data = $model->findAll();
					}else{
							$data['pengaduan'] = $model->getPengaduan($param, $role, $userid, '', $id, $user_satuan);
							$data['lampiran']  = $modelfiles->getWhere(['id_parent' => $id])->getResult();
							$data['balasan']   = $model->getBalasan($id);
							foreach ($data['balasan'] as $key => $value) {
								unset($value->user_password);
								unset($value->user_created_at);
								unset($value->user_id);
							}

					}

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getBerita()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$user_satuan		= $this->data['satuan'];

				if($this->logged){
					$model = new \App\Models\BeritaModel();
					$modelfiles = new \App\Models\FilesModel();
					if($role == 100){
							$data['berita'] = $model->join('users','users.user_id = data_berita.create_by')->findAll();
					}else{
							$data['berita'] = $model->getBerita($param, $role, $userid, '', $id);
							$data['lampiran']  = $modelfiles->getWhere(['id_parent' => $id])->getResult();
					}

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getKegiatan()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

				if($this->logged){
					$model = new \App\Models\KegiatanModel();
					$modelfiles = new \App\Models\FilesModel();

					$data = $model->getKegiatan($param, $role, $userid, '', $id);

					foreach ($data as $key => $value) {
						$data[$key]->lampiran  = $modelfiles->getWhere(['id_parent' => $value->id])->getResult();
					}

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getFiles()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

				if($this->logged){
					$model = new \App\Models\KegiatanModel();
					$modelfiles = new \App\Models\FilesModel();

					$data = $modelfiles->getWhere(['id_parent' => $param['id']])->getResult();

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getBeritaCovid()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

				if($this->logged){
					$model = new \App\Models\BeritaModel();
					$modelfiles = new \App\Models\FilesModel();
					// if($role == 100){
					// 		$data['berita'] = $model->join('users','users.user_id = data_berita.create_by')->findAll();
					// }else{
							$data = $model->getBeritaCovid($param, $role, $userid, '', $id);
							foreach ($data as $key => $value) {
								$data[$key]->lampiran  = $modelfiles->getWhere(['id_parent' => $value->id])->getResult();
							}
					// }

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getLaporCovid()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

				if($this->logged){
					$model = new \App\Models\PengaduanModel();
					$modelfiles = new \App\Models\FilesModel();
					// if($role == 100){
					// 		$data['berita'] = $model->join('users','users.user_id = data_berita.create_by')->findAll();
					// }else{
							$data = $model->getLaporCovid($param, $role, $userid, '', $id);
							foreach ($data as $key => $value) {
								$data[$key]->lampiran  = $modelfiles->getWhere(['id_parent' => $value->id])->getResult();
							}
					// }

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				}else{
					$response = [
							'status'   => 'gagal',
							'code'     => '0',
							'data' 		 => 'silahkan login'
					];
				}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadprogram()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code		= $request->getVar('code');
				// print_r($code);die;
					$model = new \App\Models\ProgramModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

						$fulldata = [];
						$dataprogram = $model->getProgram($role, $code);


					if($dataprogram){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $dataprogram
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadkegiatan()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');
				$code1 		= $request->getVar('code1');

					$model = new \App\Models\KegiatanModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

						$fulldata = [];
						$datakegiatan = $model->getKegiatan($code, $code1);

					if($datakegiatan){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datakegiatan
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadsubkegiatan()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\KegiatanModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

						$fulldata = [];
						$datakegiatan = $model->getsubKegiatan($code);
				
					if($datakegiatan){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datakegiatan
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadpaket()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\KegiatanModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
					
						$fulldata = [];
						$datapaket = $model->getpaket($code, $userid);
						foreach ($datapaket as $key => $value) {
							$datatarget = $model->cektarget($value->id);
							// print_r($value);
							$value->target = !empty($datatarget) ? '1' : '0';
							$value->idtarget = !empty($datatarget) ? $datatarget[0]->id : '0';
							$fulldata[$key] = $value;
						}
						// die;
					if($fulldata){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $fulldata
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadpaketnya()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('ids');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\KegiatanModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
					
						$fulldata = [];
						$datapaket = $model->getpaketnya($id, $userid);

					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadtarget()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
			
					$fulldata = [];
					
					$datapaket = $model->gettarget($code, $role, $userid);
					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadtargetNip()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$fulldata = [];
					$datapaket = $model->gettargetNip($code);

					$datareal = [];
					$bulan = [];
					$newreal = [];
					foreach ($datapaket as $key => $value) {
						$ceklatar = $model->cekParam('param_latar_belakang', $value->id_paket);
						$latarbelakang = '';
						if(!empty($ceklatar)){
							$latarbelakang = $ceklatar[0]->desc;
						}
						
						if($value->type == 'keuangan'){
							$cekuraian = $model->cekParam('param_uraian', $value->id_paket, null, $value->type);
							$uraian = '';
							if(!empty($cekuraian)){
								$uraian = $cekuraian[0]->desc;
							}

							$datareal['n1'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n1');
							$cekmasalah1 = $model->cekParam('param_masalah', $value->id_paket, 'n1', $value->type);
							if(!empty($cekmasalah1)){
								$masalah1 = $cekmasalah1[0]->desc;
								$datareal['n1']->permasalahan = $masalah1;
							}

							$datareal['n2'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n2');
							$cekmasalah2 = $model->cekParam('param_masalah', $value->id_paket, 'n2', $value->type);
							if(!empty($cekmasalah2)){
								$masalah2 = $cekmasalah2[0]->desc;
								$datareal['n2']->permasalahan = $masalah2;
							}

							$datareal['n3'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n3');
							$cekmasalah3 = $model->cekParam('param_masalah', $value->id_paket, 'n3', $value->type);
							if(!empty($cekmasalah3)){
								$masalah3 = $cekmasalah3[0]->desc;
								$datareal['n3']->permasalahan = $masalah3;
							}

							$datareal['n4'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n4');
							$cekmasalah4 = $model->cekParam('param_masalah', $value->id_paket, 'n4', $value->type);
							if(!empty($cekmasalah4)){
								$masalah4 = $cekmasalah4[0]->desc;
								$datareal['n4']->permasalahan = $masalah4;
							}

							$datareal['n5'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n5');
							$cekmasalah5 = $model->cekParam('param_masalah', $value->id_paket, 'n5', $value->type);
							if(!empty($cekmasalah5)){
								$masalah5 = $cekmasalah5[0]->desc;
								$datareal['n5']->permasalahan = $masalah5;
							}

							$datareal['n6'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n6');
							$cekmasalah6 = $model->cekParam('param_masalah', $value->id_paket, 'n6', $value->type);
							if(!empty($cekmasalah6)){
								$masalah6 = $cekmasalah6[0]->desc;
								$datareal['n6']->permasalahan = $masalah6;
							}

							$datareal['n7'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n7');
							$cekmasalah7 = $model->cekParam('param_masalah', $value->id_paket, 'n7', $value->type);
							if(!empty($cekmasalah7)){
								$masalah7 = $cekmasalah6[0]->desc;
								$datareal['n7']->permasalahan = $masalah7;
							}

							$datareal['n8'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n8');
							$cekmasalah8 = $model->cekParam('param_masalah', $value->id_paket, 'n8', $value->type);
							if(!empty($cekmasalah8)){
								$masalah8 = $cekmasalah8[0]->desc;
								$datareal['n8']->permasalahan = $masalah8;
							}

							$datareal['n9'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n9');
							$cekmasalah9 = $model->cekParam('param_masalah', $value->id_paket, 'n9', $value->type);
							if(!empty($cekmasalah9)){
								$masalah9 = $cekmasalah9[0]->desc;
								$datareal['n9']->permasalahan = $masalah9;
							}

							$datareal['n10'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n10');
							$cekmasalah10 = $model->cekParam('param_masalah', $value->id_paket, 'n10', $value->type);
							if(!empty($cekmasalah10)){
								$masalah10 = $cekmasalah10[0]->desc;
								$datareal['n10']->permasalahan = $masalah10;
							}

							$datareal['n11'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n11');
							$cekmasalah11 = $model->cekParam('param_masalah', $value->id_paket, 'n11', $value->type);
							if(!empty($cekmasalah11)){
								$masalah11 = $cekmasalah11[0]->desc;
								$datareal['n11']->permasalahan = $masalah11;
							}

							$datareal['n12'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n12');
							$cekmasalah12 = $model->cekParam('param_masalah', $value->id_paket, 'n12', $value->type);
							if(!empty($cekmasalah12)){
								$masalah12 = $cekmasalah12[0]->desc;
								$datareal['n12']->permasalahan = $masalah12;
							}
							$datapaket[$key]->progres = $datareal;
							
							$datapaket[$key]->uraian = $uraian;

						}else if($value->type == 'fisik'){
							
							$datareal['n1'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n1');
							$cekuraian1 = $model->cekParam('param_uraian', $value->id_paket, 'n1', $value->type);
							if(!empty($cekuraian1)){
								$uraian1 = $cekuraian1[0]->desc;
								$datareal['n1']->uraian = $uraian1;
							}

							$cekmasalah1 = $model->cekParam('param_masalah', $value->id_paket, 'n1', $value->type);
							if(!empty($cekmasalah1)){
								$masalah1 = $cekmasalah1[0]->desc;
								$datareal['n1']->permasalahan = $masalah1;
							}
							
							$datareal['n2'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n2');
							$cekuraian2 = $model->cekParam('param_uraian', $value->id_paket, 'n2', $value->type);
							if(!empty($cekuraian2)){
								$uraian2 = $cekuraian2[0]->desc;
								$datareal['n2']->uraian = $uraian2;
							}

							$cekmasalah2 = $model->cekParam('param_masalah', $value->id_paket, 'n2', $value->type);
							if(!empty($cekmasalah2)){
								$masalah2 = $cekmasalah2[0]->desc;
								$datareal['n2']->permasalahan = $masalah2;
							}

							$datareal['n3'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n3');
							$cekuraian3 = $model->cekParam('param_uraian', $value->id_paket, 'n3', $value->type);
							if(!empty($cekuraian3)){
								$uraian3 = $cekuraian3[0]->desc;
								$datareal['n3']->uraian = $uraian3;
							}

							$cekmasalah3 = $model->cekParam('param_masalah', $value->id_paket, 'n3', $value->type);
							if(!empty($cekmasalah3)){
								$masalah3 = $cekmasalah3[0]->desc;
								$datareal['n3']->permasalahan = $masalah3;
							}

							$datareal['n4'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n4');
							$cekuraian4 = $model->cekParam('param_uraian', $value->id_paket, 'n4', $value->type);
							if(!empty($cekuraian4)){
								$uraian4 = $cekuraian4[0]->desc;
								$datareal['n4']->uraian = $uraian4;
							}

							$cekmasalah4 = $model->cekParam('param_masalah', $value->id_paket, 'n4', $value->type);
							if(!empty($cekmasalah4)){
								$masalah4 = $cekmasalah4[0]->desc;
								$datareal['n4']->permasalahan = $masalah4;
							}

							$datareal['n5'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n5');
							$cekuraian5 = $model->cekParam('param_uraian', $value->id_paket, 'n5', $value->type);
							if(!empty($cekuraian5)){
								$uraian5 = $cekuraian5[0]->desc;
								$datareal['n5']->uraian = $uraian5;
							}

							$cekmasalah5 = $model->cekParam('param_masalah', $value->id_paket, 'n5', $value->type);
							if(!empty($cekmasalah5)){
								$masalah5 = $cekmasalah5[0]->desc;
								$datareal['n5']->permasalahan = $masalah5;
							}

							$datareal['n6'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n6');
							$cekuraian6 = $model->cekParam('param_uraian', $value->id_paket, 'n6', $value->type);
							if(!empty($cekuraian6)){
								$uraian6 = $cekuraian6[0]->desc;
								$datareal['n6']->uraian = $uraian6;
							}

							if(!empty($cekmasalah6)){
								$masalah6 = $cekmasalah6[0]->desc;
								$datareal['n6']->permasalahan = $masalah6;
							}

							$datareal['n7'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n7');
							$cekuraian7 = $model->cekParam('param_uraian', $value->id_paket, 'n7', $value->type);
							if(!empty($cekuraian7)){
								$uraian7 = $cekuraian7[0]->desc;
								$datareal['n7']->uraian = $uraian7;
							}

							if(!empty($cekmasalah7)){
								$masalah7 = $cekmasalah6[0]->desc;
								$datareal['n7']->permasalahan = $masalah7;
							}

							$datareal['n8'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n8');
							$cekuraian8 = $model->cekParam('param_uraian', $value->id_paket, 'n8', $value->type);
							
							if(!empty($cekuraian8)){
								$uraian8 = $cekuraian8[0]->desc;
								$datareal['n8']->uraian = $uraian8;
							}

							if(!empty($cekmasalah8)){
								$masalah8 = $cekmasalah8[0]->desc;
								$datareal['n8']->permasalahan = $masalah8;
							}
							
							$datareal['n9'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n9');
							$cekuraian9 = $model->cekParam('param_uraian', $value->id_paket, 'n9', $value->type);
							if(!empty($cekuraian9)){
								$uraian6 = $cekuraian9[0]->desc;
								$datareal['n9']->uraian = $uraian9;
							}

							if(!empty($cekmasalah9)){
								$masalah9 = $cekmasalah9[0]->desc;
								$datareal['n9']->permasalahan = $masalah9;
							}
							
							$datareal['n10'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n10');
							$cekuraian10 = $model->cekParam('param_uraian', $value->id_paket, 'n10', $value->type);
							if(!empty($cekuraian10)){
								$uraian10 = $cekuraian10[0]->desc;
								$datareal['n10']->uraian = $uraian10;
							}

							if(!empty($cekmasalah10)){
								$masalah10 = $cekmasalah10[0]->desc;
								$datareal['n10']->permasalahan = $masalah10;
							}

							$datareal['n11'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n11');
							$cekuraian11 = $model->cekParam('param_uraian', $value->id_paket, 'n11', $value->type);
							if(!empty($cekuraian11)){
								$uraian11 = $cekuraian11[0]->desc;
								$datareal['n11']->uraian = $uraian11;
							}

							$cekmasalah11 = $model->cekParam('param_masalah', $value->id_paket, 'n11', $value->type);
							if(!empty($cekmasalah11)){
								$masalah11 = $cekmasalah11[0]->desc;
								$datareal['n11']->permasalahan = $masalah11;
							}

							$datareal['n12'] = $model->getrealisasi($value->id_paket, $value->created_by, $value->type, 'n12');
							$cekuraian12 = $model->cekParam('param_uraian', $value->id_paket, 'n12', $value->type);
							if(!empty($cekuraian12)){
								$uraian12 = $cekuraian12[0]->desc;
								$datareal['n12']->uraian = $uraian12;
							}

							$cekmasalah12 = $model->cekParam('param_masalah', $value->id_paket, 'n12', $value->type);
							if(!empty($cekmasalah12)){
								$masalah12 = $cekmasalah12[0]->desc;
								$datareal['n12']->permasalahan = $masalah12;
							}

							$datapaket[$key]->progres = $datareal;
						}

						$datapaket[$key]->latar_belakang = $latarbelakang;

					}


					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadminggu()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$type 		= $request->getVar('type');
				$code 		= $request->getVar('code');
				$idpaket 		= $request->getVar('idpaket');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$datapaket = $model->getminggu($type, $code, $idpaket);
					$cekrealisasi = $model->cekDataRealisasi($idpaket, $code, $userid);
					
					if(empty($datapaket)){
						
						$datapaket = [];
						
						if(!empty($cekrealisasi)){
							
							$cekrealisasi[0]->type = $type;
							
							if($type == 'keuangan'){
								$cekuraian = $model->cekParam('param_uraian', $idpaket, null, $type);
								$cekrealisasi[0]->uraian = $cekuraian[0]->desc;

								$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, $type);
								$cekrealisasi[0]->latar_belakang = $ceklatar[0]->desc;

								$cekmasalah = $model->cekParam('param_masalah', $idpaket, $cekrealisasi[0]->kode_bulan, $type);
								$cekrealisasi[0]->permasalahan = $cekmasalah[0]->desc;

							}else if($type = 'fisik'){
								
								$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, null);

								$cekrealisasi[0]->latar_belakang = $ceklatar[0]->desc;
								$cekuraian = $model->cekParam('param_uraian', $idpaket, $cekrealisasi[0]->kode_bulan, $type);
								if($cekuraian){
									$cekrealisasi[0]->uraian = $cekuraian[0]->desc;
								}

								$cekmasalah = $model->cekParam('param_masalah', $idpaket, $cekrealisasi[0]->kode_bulan, $type);
								if($cekmasalah){
									$cekrealisasi[0]->permasalahan = $cekmasalah[0]->desc;
								}

							}
							$datapaket[0] = $cekrealisasi[0];
							
						}else{
							
							$adadata = [];
							if($type == 'keuangan'){
								$adadata[0] = new \stdClass();
								$adadata[0]->type = $type;
								$cekuraian = $model->cekParam('param_uraian', $idpaket, null, $type);
								if(!empty($cekuraian)){
									$adadata[0]->uraian = $cekuraian[0]->desc;
								}

								$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, null);
								if(!empty($ceklatar)){
									$adadata[0]->latar_belakang = $ceklatar[0]->desc;
								}

								$str = substr($code, -1);
      							$last = $str - 1;
								$cektotalsebelumnya = $model->cekParam('bulan_realisasi', $idpaket , 'n'.$last, $type);
								
								if(!empty($cektotalsebelumnya)){
									$m1 = $cektotalsebelumnya[0]->m1;
									$m2 = $cektotalsebelumnya[0]->m2;
									$m3 = $cektotalsebelumnya[0]->m3;
									$m4 = $cektotalsebelumnya[0]->m4;
									
									$tot_sebelum = $m1 ? str_replace(".","", $m1) : '0' ;
									$tot_sebelum += $m2 ? str_replace(".","", $m2) : '0' ;
									$tot_sebelum += $m3 ? str_replace(".","", $m3) : '0' ;
									$tot_sebelum += $m4 ? str_replace(".","", $m4) : '0' ;
									
									// $cekmasalah = $model->cekParam('param_masalah', $idpaket, $cekrealisasi[0]->kode_bulan);
									$adadata[0]->total_sebelumnya = $tot_sebelum;
								}
								
							}else if($type == 'fisik'){
								$adadata[0] = new \stdClass();
								$adadata[0]->type = $type;

								$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, null);
								if(!empty($ceklatar)){
									$adadata[0]->latar_belakang = $ceklatar[0]->desc;
								}
								$cekprogres = $model->cekParam('data_progres', $idpaket, $code, $type, $userid);
								if(!empty($cekprogres)){
									$adadata[0]->progres = $cekprogres[0]->progres;
									$adadata[0]->file = $cekprogres[0]->path.$cekprogres[0]->filename;
								}

							}
							$datapaket = $adadata;
						}
					}else{
						
						
						if($type == 'keuangan'){
							$cekuraian = $model->cekParam('param_uraian', $idpaket, null, $type, $userid);
							
							if(!empty($cekuraian)){
								$datapaket[0]->uraian = $cekuraian[0]->desc;
							}

							$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, $type, $userid);
							if(!empty($ceklatar)){
								$datapaket[0]->latar_belakang = $ceklatar[0]->desc;
							}

							$cekmasalah = $model->cekParam('param_masalah', $idpaket, $code, $type, $userid);
							if(!empty($ceklatar)){
								$datapaket[0]->permasalahan = $cekmasalah[0]->desc;
							}
						}else if($type == 'fisik'){
							
							$ceklatar = $model->cekParam('param_latar_belakang', $idpaket, null, null, $userid);
							$datapaket[0]->latar_belakang = $ceklatar[0]->desc;
							$cekuraian = $model->cekParam('param_uraian', $idpaket, $code, $type, $userid);
							if(!empty($cekuraian)){
								$datapaket[0]->uraian = $cekuraian[0]->desc;
							}

							$cekmasalah = $model->cekParam('param_masalah', $idpaket, $code, $type, $userid);
							if(!empty($cekmasalah)){
								$datapaket[0]->permasalahan = $cekmasalah[0]->desc;
							}

							$cekprogres = $model->cekParam('data_progres', $idpaket, $code, $type, $userid);
							
							if(!empty($cekprogres)){
								$datapaket[0]->progres = $cekprogres[0]->progres;
								$datapaket[0]->file = $cekprogres;
								$datapaket[0]->keterangan =  $cekprogres[0]->keterangan;
								
							}
							
						}
						
						$datapaket = $datapaket;
					}
					
					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getTraceAsString());
		}
	}

	public function loadnip()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$fulldata = [];
					$datapaket = $model->getnip($code);

					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadfile()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$created_by 		= $request->getVar('iduser');
				$id_paket 		= $request->getVar('idpaket');
				$kode_bulan 		= $request->getVar('bulan');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$fulldata = [];
					$datapaket = $model->getfileprogress($created_by, $id_paket, $kode_bulan);

					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadrealisasi()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$nip			= $this->data['nip'];
				$code 		= $request->getVar('code');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$fulldata = [];
					$datapaket = $model->gettarget($code, $role, $userid);

					if($datapaket){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $datapaket
						];
					}else{
						$response = [
								'status'   => 'gagal',
								'code'     => '0',
								'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadBeritaCovid()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\BeritaModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					if($param == 'post'){
						$fulldata = [];
						$databerita = $model->getBeritaByidCovid($id);

						foreach ($databerita as $keyberita => $valueberita) {

							$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getResult();
							$datasatuan= $model->getSatuanByCode($valueberita->satuan);
							$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles[0], (array) $datasatuan);
							array_push($fulldata, $obj_merged);
						}
						$berita = $fulldata;
					}else{
							if($param && $id){
								$data = $modelparam->getparam($param, $id);
							}else{
								$data = $model->getSatuan();
							}

							$berita = [];
							foreach ($data as $key => $value) {
								$fulldata = [];
								$databerita = $model->loadBeritaCovid($value->satuan_code);
								foreach ($databerita as $keyberita => $valueberita) {
									$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getResult();
									$obj_merged = (object) array_merge((array) $valueberita);
									$obj_merged->lampiran = (array) $datafiles;
									array_push($fulldata, $obj_merged);
								}
								$berita = $fulldata;
							}
						}

					if($berita){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $berita
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadKegiatan_pol()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\KegiatanModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					if($param == 'post'){
						$fulldata = [];
						$databerita = $model->getKegiatanByid($id);
						foreach ($databerita as $keyberita => $valueberita) {

							$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getResult();
							$datasatuan= $model->getSatuanByCode($valueberita->satuan);
							$obj_merged = (object) array_merge((array) $valueberita, (array) $datasatuan);
							$obj_merged->lampiran = (array) $datafiles;
							array_push($fulldata, $obj_merged);
						}
						$berita = $fulldata;
					}else{
							if($param && $id){
								$data = $modelparam->getparam($param, $id);
							}else{
								$data = $model->getSatuan();
							}

							$berita = [];
							foreach ($data as $key => $value) {
								$fulldata = [];
								$databerita = $model->loadKegiatan($value->satuan_code);
								foreach ($databerita as $keyberita => $valueberita) {
									$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
									$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles);
									array_push($fulldata, $obj_merged);
								}
								$berita[$value->satuan_name] = $fulldata;
							}
						}

					if($berita){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $berita
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadBeritaHeadline()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\BeritaModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					if($param == 'post'){
						$fulldata = [];
						$databerita = $model->getBeritaHeadline($id, 1);

						foreach ($databerita as $keyberita => $valueberita) {

							$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
							$datasatuan= $model->getSatuanByCode($valueberita->satuan);
							$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles, (array) $datasatuan);
							array_push($fulldata, $obj_merged);
						}
						$berita = $fulldata;
					}else{
							if($param && $id){
								$data = $modelparam->getparam($param, $id);
							}else{
								$data = $model->getSatuan();
							}

							$berita = [];
							foreach ($data as $key => $value) {
								$fulldata = [];
								$databerita = $model->loadBeritaHeadline($value->satuan_code);
								foreach ($databerita as $keyberita => $valueberita) {
									$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
									$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles);
									array_push($fulldata, $obj_merged);
								}
								$berita[$value->satuan_name] = $fulldata;
							}
						}

					if($berita){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $berita
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadBeritaHeadlineCovid()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\BeritaModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					if($param == 'post'){
						$fulldata = [];
						$databerita = $model->getBeritaHeadlineCovid($id, 1);

						foreach ($databerita as $keyberita => $valueberita) {

							$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
							$datasatuan= $model->getSatuanByCode($valueberita->satuan);
							$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles, (array) $datasatuan);
							array_push($fulldata, $obj_merged);
						}
						$berita = $fulldata;
					}else{
							if($param && $id){
								$data = $modelparam->getparam($param, $id);
							}else{
								$data = $model->getSatuan();
							}

							$berita = [];
							foreach ($data as $key => $value) {
								$fulldata = [];
								$databerita = $model->loadBeritaHeadlineCovid($value->satuan_code);

								foreach ($databerita as $keyberita => $valueberita) {
									$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
									$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles);
									array_push($fulldata, $obj_merged);
								}
								$berita = $fulldata;
							}
						}

					if($berita){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $berita
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadparam()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$data = $model->getparam($param, $id);

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function save(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\PengaduanModel();
		$modelfiles = new \App\Models\FilesModel();

		if($mode == 'balasan'){
			$data = [
				'id_pengaduan'=> $request->getVar('id'),
				'isi' 				=> $request->getVar('isi'),
				'create_date' => $this->now,
				'update_date' => $this->now,
				'create_by' 	=> $this->data['userid']
	    ];

			$simpan = $model->saveBalasan($data);
			$response = [
					'status'   => 'sukses',
					'code'     => '0',
					'data' 		 => 'terkirim'
			];
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}

		$files	  = $request->getFiles()['lampiran'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/'.$request->getVar('param').'/lampiran';
		$bagian 	= $request->getVar('nama_tujuan');
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$bagian.'/'.$date.'/';

		$data = [
						'kode_tujuan' => $request->getVar('kode_tujuan'),
						'nama_tujuan'	=> $request->getVar('nama_tujuan'),
						'judul' 			=> $request->getVar('judul'),
						'isi' 				=> $request->getVar('isi'),
						'create_date' => $this->now,
						'update_date' => $this->now,
						'create_by' 	=> $this->data['userid'],
						'update_by' 	=> '',
						'status' 			=> 0,
						'role' 				=> $this->data['role']
        ];
		$res = $model->insert($data);
		$id  = $model->insertID();

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$bagian.'/'.$date.'/',
					'type' => $request->getVar('param'),
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function saveBerita(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\BeritaModel();
		$modelfiles = new \App\Models\FilesModel();

		$files	  = $request->getFiles()['lampiran'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/'.$request->getVar('param').'/lampiran';
		$bagian 	= $request->getVar('nama_tujuan');
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$bagian.'/'.$date.'/';

		$data = [
			'judul_berita' => $request->getVar('judul'),
			'isi_berita' => $request->getVar('isi'),
			'satuan' => $request->getVar('kode_tujuan'),
			'create_by' => $this->data['userid'],
			'status' => 0,
			'create_date' => $this->now,
			'update_date' => $this->now
    ];

		$res = $model->insert($data);
		$id  = $model->insertID();

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$bagian.'/'.$date.'/',
					'type' => $request->getVar('param'),
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function saveKegiatan(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\KegiatanModel();
		$modelfiles = new \App\Models\FilesModel();

		$files	  = $request->getFiles()['lampiran'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/'.$request->getVar('param').'/lampiran';
		$bagian 	= $request->getVar('nama_tujuan');
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$bagian.'/'.$date.'/';

		$data = [
			'judul_kegiatan' => $request->getVar('judul'),
			'satuan' => $request->getVar('kode_tujuan'),
			'create_by' => $this->data['userid'],
			'status' => 0,
			'create_date' => $this->now,
			'update_date' => $this->now
    ];

		$res = $model->insert($data);
		$id  = $model->insertID();

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$bagian.'/'.$date.'/',
					'type' => $request->getVar('param'),
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function saveBeritaCovid(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\BeritaModel();
		$modelfiles = new \App\Models\FilesModel();

		$files	  = $request->getFiles()['lampiran'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/'.$request->getVar('param').'/lampiran';
		$bagian 	= 'covid';
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$bagian.'/'.$date.'/';

		$data = [
			'judul_berita' => $request->getVar('judul'),
			'isi_berita' => $request->getVar('isi'),
			'create_by' => $this->data['userid'],
			'status' => 0,
			'create_date' => $this->now,
			'update_date' => $this->now
    ];

		$res = $model->insertBeritaCovid($data);
		$id  = $res;

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$bagian.'/'.$date.'/',
					'type' => $request->getVar('param'),
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function saveLapor(){
		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\PengaduanModel();
		$modelfiles = new \App\Models\FilesModel();

		$files	  = $request->getFiles()['lampiran'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/'.$request->getVar('param').'/lampiran';
		$bagian 	= 'covid';
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$bagian.'/'.$date.'/';

		$data = [
			'nama' => $request->getVar('input-name'),
			'no_telepon' => $request->getVar('input-notelp'),
			'alamat' => $request->getVar('input-alamat'),
			'id_kecamatan' => $request->getVar('input-kecamatan'),
			'id_desa' => $request->getVar('input-desa'),
			'create_date' => $this->now
    ];

		$res = $model->saveLaporan($data);
		$id  = $res;

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}

		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$bagian.'/'.$date.'/',
					'type' => 'kerumunan',
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function update(){

		$request  = $this->request;
		$id 	  = $request->getVar('id');
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		$model 	  = new \App\Models\PengaduanModel();

		$data = [
						'update_date' => $this->now,
						'update_by' 	=> $userid,
						'status' 			=> 1,
        ];

		$res = $model->update($id, $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addProgram(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\ProgramModel();

		$data = [
						'kode_program' => $request->getVar('kode_program'),
						'nama_program' => $request->getVar('nama_program'),
						'created_by'	 => $role,
						'created_date' => $this->now,
						'updated_date' => $this->now,
        ];

		$res = $model->saveParam($param, $data);
		$id  = $model->insertID();

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updateProgram(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$id 	  = $request->getVar('id_program');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\ProgramModel();

		$data = [
						'kode_program' => $request->getVar('kode_program'),
						'nama_program' => $request->getVar('nama_program'),
						'created_by'	 => $role,
						'updated_date' => $this->now,
        ];
		
		$res = $model->updateParam($param, $id, $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updateKegiatan(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$id 	  = $request->getVar('id_kegiatan');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\KegiatanModel();

		$data = [
						'kode_kegiatan' => $request->getVar('kode_kegiatan'),
						'nama_kegiatan' => $request->getVar('nama_kegiatan'),
						'created_by'	 => $role,
						'updated_date' => $this->now,
        ];
		
		$res = $model->updateParam($param, $id, $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updatesubKegiatan(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$id 	  = $request->getVar('id_subkegiatan');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\KegiatanModel();
		$sisa = 0;
		if($request->getVar('sisa_pagu_subkegiatan')){
			
			$pg = (int)str_replace(".","", $request->getVar('pagu_subkegiatan')) - (int)str_replace(".","", $request->getVar('pagu_awal'));
			$sisa = (int)str_replace(".","", $request->getVar('sisa_pagu_subkegiatan')) + $pg;
		}

		$data = [
			'kode_subkegiatan' => $request->getVar('kode_subkegiatan'),
			'nama_subkegiatan' => $request->getVar('nama_subkegiatan'),
			'pagu_subkegiatan' => $request->getVar('pagu_subkegiatan'),
			'pagu_perubahan'	=> $request->getVar('pagu_perubahan'),
			'sisa_pagu_subkegiatan' => $sisa,
			'updated_date'	=> $this->now,
        ];
		
		$res = $model->updateParam($param, $id, $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updatepaket(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$id 	  = $request->getVar('id_paket');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\KegiatanModel();

		$data = [
			'kode_paket' 		=> $request->getVar('kode_paket'),
			'nama_paket' 		=> $request->getVar('nama_paket'),
			'pagu_paket' 		=> $request->getVar('pagu_paket'),
			'pagu_perubahan'	=> $request->getVar('pagu_perubahan'),
			'updated_by'		=> $this->data['userid'],
			'updated_date'		=> $this->now,
        ];
		$model->updatePaguSub($request->getVar('kode_program'), $request->getVar('kode_kegiatan'), $request->getVar('kode_subkegiatan'), $request->getVar('sisa_pagu'));
		$res = $model->updateParam($param, $id, $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addKegiatan(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $this->data['role'];

		$model 	  = new \App\Models\KegiatanModel();

		if($request->getVar('param') == 'data_kegiatan'){
			$data = [
							'kode_program	' => $request->getVar('kode_program'),
							'kode_kegiatan' => $request->getVar('kode_kegiatan'),
							'nama_kegiatan' => $request->getVar('nama_kegiatan'),
							'created_by'		=> $role,
							'created_date'	=> $this->now,
							'updated_date'	=> $this->now,
					];
		}else if($request->getVar('param') == 'data_subkegiatan'){
			$data = [
							'kode_program	' => $request->getVar('kode_program'),
							'kode_kegiatan' => $request->getVar('kode_kegiatan'),
							'kode_subkegiatan' => $request->getVar('kode_subkegiatan'),
							'nama_subkegiatan' => $request->getVar('nama_subkegiatan'),
							'pagu_subkegiatan' => $request->getVar('pagu_subkegiatan'),
							'created_by'		=> $role,
							'created_date'	=> $this->now,
							'updated_date'	=> $this->now,
					];
		}else if($request->getVar('param') == 'data_paket'){
			$data = [
							'kode_program	' 	 => $request->getVar('kode_program'),
							'kode_kegiatan' 	 => $request->getVar('kode_kegiatan'),
							'kode_subkegiatan'	=> $request->getVar('kode_subkegiatan'),
							'kode_paket' 		=> $request->getVar('kode_paket'),
							'nama_paket' 		=> $request->getVar('nama_paket'),
							'pagu_paket' 		=> $request->getVar('pagu_paket'),
							'created_by'		=> $this->data['userid'],
							'created_date'	=> $this->now,
							'updated_date'	=> $this->now,
					];

			$model->updatePaguSub($request->getVar('kode_program'), $request->getVar('kode_kegiatan'), $request->getVar('kode_subkegiatan'), $request->getVar('sisa_pagu'));
		}

		$res = $model->saveParam($param, $data);
		$id  = $model->insertID();

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addTarget(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $this->data['role'];
		$userid 	= $this->data['userid'];

		$model 	  = new \App\Models\TargetModel();
		if($role == '100' || $role == '30'){
			$data = [
						'kode_program'		=> $request->getVar('kode_program'),
						'kode_kegiatan'		=> $request->getVar('kode_kegiatan'),
						'kode_subkegiatan'=> $request->getVar('kode_subkegiatan'),
						'id_paket'				=> $request->getVar('id_paket'),
						'created_by'			=> $userid,
						'created_date'		=> $this->now,
						'updated_date'		=> $this->now,
						'pagu'						=> $request->getVar('pagu_kegiatan'),
						// 'ppk'							=> $request->getVar('ppk'),
						'bidang'					=> $request->getVar('bidang'),
						'seksi'						=> $request->getVar('seksi'),
						'target_output'						=> $request->getVar('target_output'),
						'satuan'						=> $request->getVar('satuan'),
				];
			}
				
			if($role == '30'){
				$databulan_k = [];
				$databulan_f = [];
				$databulan_kp = [];
				for ($i=1; $i <= 12; $i++) {
					$databulan_k['id_paket'] = $request->getVar('id_paket');
					$databulan_k['created_by'] = $userid;
					$databulan_k['created_date'] = $this->now;
					
					$databulan_f['id_paket'] = $request->getVar('id_paket');
					$databulan_f['created_by'] = $userid;
					$databulan_f['created_date'] = $this->now;

					$databulan_kp['id_paket'] = $request->getVar('id_paket');
					$databulan_kp['created_by'] = $userid;
					$databulan_kp['created_date'] = $this->now;

					$databulan_k['type'] = 'keuangan';
					$databulan_k['n'.$i] = $request->getVar('k'.$i);
					$databulan_f['type'] = 'fisik';
					$databulan_f['n'.$i] = $request->getVar('f'.$i);
					
					$databulan_kp['type'] = 'persen';
					$databulan_kp['n'.$i] = $request->getVar('kp'.$i);
				}

				$databulan_k['tot'] = $request->getVar('ktot');
				$databulan_f['tot'] = $request->getVar('ftot');
				// for ($i=1; $i <= 2; $i++) {
					// if($=1){
						$res_k = $model->saveParam('bulan_target', $databulan_k);

					// }else if($=2){
						$res_f = $model->saveParam('bulan_target', $databulan_f);
					// }

						$res_kp = $model->saveParam('bulan_target', $databulan_kp);
					// code...
				// }
			}
		
		if($role == '100' || $role == '30'){
			$res = $model->saveParam($param, $data);
			$id  = $model->insertID();
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => $id
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updateTarget(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $this->data['role'];
		$userid 	= $this->data['userid'];

		$model 	  = new \App\Models\TargetModel();
		
		if($role == '30'){
		$data = [
					'updated_date'		=> $this->now,
					'pagu'						=> $request->getVar('pagu_kegiatan'),
				];

		$databulan_k = [];
		$databulan_f = [];
		$databulan_kp = [];
		
		for ($i=1; $i <= 12; $i++) {

			$databulan_k['id_paket'] = $request->getVar('id_paket');
			$databulan_k['created_by'] = $request->getVar($userid);
			$databulan_k['created_date'] = $this->now;
			
			$databulan_f['id_paket'] = $request->getVar('id_paket');
			$databulan_f['created_by'] = $request->getVar($userid);
			$databulan_f['created_date'] = $this->now;
			
			$databulan_kp['id_paket'] = $request->getVar('id_paket');
			$databulan_kp['created_by'] = $request->getVar($userid);
			$databulan_kp['created_date'] = $this->now;

			$databulan_k['type'] = 'keuangan';
			$databulan_k['n'.$i] = $request->getVar('k'.$i);
			$databulan_f['type'] = 'fisik';
			$databulan_f['n'.$i] = $request->getVar('f'.$i);
			$databulan_kp['type'] = 'persen';
			$databulan_kp['n'.$i] = $request->getVar('kp'.$i);
		}
		$databulan_k['tot'] = $request->getVar('ktot');
		$databulan_f['tot'] = $request->getVar('ftot');
		
		
		$cekbulantarket = $model->cekbulantarget($request->getVar('id_paket'));
		
		if(empty($cekbulantarket)){
			$res_k = $model->saveParam('bulan_target', $databulan_k);
			$res_f = $model->saveParam('bulan_target', $databulan_f);
			$res_kp = $model->saveParam('bulan_target', $databulan_kp);
		}else{
			$res_k 	= $model->updateTarget('bulan_target',  ['id_paket' => $request->getVar('id_paket'), 'type' => $databulan_k['type'] ], $databulan_k);
			$res_f 	= $model->updateTarget('bulan_target',  ['id_paket' => $request->getVar('id_paket'), 'type' => $databulan_f['type'] ], $databulan_f);
			$res_kp = $model->updateTarget('bulan_target', ['id_paket' => $request->getVar('id_paket'), 'type' => $databulan_kp['type'] ] ,  $databulan_kp);
		}

		$res = $model->updateTarget($param , [ 'id' => $request->getVar('id_paket') ], $data);
	}else{
		$data = [
			'updated_by' 		=> $userid,
			'updated_date'		=> $this->now,
			'pagu_perubahan'	=> $request->getVar('pagu_perubahan'),
			'bulan_perubahan'				=> $request->getVar('bulan_perubahan'),
		];

		$res = $model->updateTarget($param , [ 'id' => $request->getVar('id') ], $data);
	}
	
	

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addRealisasi(){
		try {
			//code...
		
		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $this->data['role'];
		$userid 	= $this->data['userid'];

		$model 	  = new \App\Models\TargetModel();

		$type = $request->getVar('type');

		$edited = $request->getVar('edited');

			if($edited){

				$data = [
					'created_by'		=> $userid,
					'updated_date'	=> $this->now
				];

				$data_new = [
						'created_by'		=> $userid,
						'updated_date'	=> $this->now,
						'koordinat'			=> $request->getVar('koordinat'),
						'latar_belakang'=> $request->getVar('latar_belakang'),
						'uraian'				=> $request->getVar('uraian'),
						'permasalahan'	=> $request->getVar('permasalahan'),
					];
			}else{

			
			$data = [
					'id_paket'			=> $request->getVar('id_paket'),
					'type'				=> $type,
					'kode_bulan'		=> $request->getVar('kode_bulan'),
					'created_by'		=> $userid,
					'created_date'		=> $this->now,
					'updated_date'		=> $this->now,
				];

			$data_new = [
					'id_paket'			=> $request->getVar('id_paket'),
					'created_by'		=> $userid,
					'created_date'	=> $this->now,
					'updated_date'	=> $this->now,
					'koordinat'			=> $request->getVar('koordinat'),
					'kode_bulan'		=> $request->getVar('kode_bulan')
				];
				
			$data_permasalahan = [
					'id_paket'			=> $request->getVar('id_paket'),
					'type'				=> $type,
					'desc'				=> $request->getVar('permasalahan'),
					'kode_bulan'		=> $request->getVar('kode_bulan'),
					'create_by'			=> $userid,
				];
				
			$data_uraian = [
					'id_paket'			=> $request->getVar('id_paket'),
					'type'				=> $type,
					'desc'				=> $request->getVar('uraian'),
					'kode_bulan'		=> $request->getVar('kode_bulan'),
					'create_by'			=> $userid,
				];

			$data_latar = [
					'id_paket'			=> $request->getVar('id_paket'),
					'type'				=> $type,
					'desc'				=> $request->getVar('latar_belakang'),
					'kode_bulan'		=> $request->getVar('kode_bulan'),
					'create_by'			=> $userid,
				];
				
				if(!empty($_FILES)){
					
					$files	  = $request->getFiles()['file'];
					
					$path			= FCPATH.'public';
					$tipe			= 'uploads/users/progres';
					$date 		= date('Y/m/d');
					$folder		= $path.'/'.$tipe.'/'.$date.'/';
					
					if (!is_dir($folder)) {
						mkdir($folder, 0777, TRUE);
					}
					
					foreach ($files as $i => $value) {
						# code...						
						$stat = $files[$i]->move($folder, $files[$i]->getName());
						
						$data_progres = [
							'id_paket'			=> $request->getVar('id_paket'),
							'progres'			=> $request->getVar('progres'),
							'filename'			=> $files[$i]->getName(),
							'extention'			=> null,
							'size'				=> $files[$i]->getSize('kb'),
							'path'				=> $tipe.'/'.$date.'/',
							'type'				=> 'fisik',
							'created_date'		=> $this->now,
							'updated_date'		=> $this->now,
							'create_by'			=> $userid,
							'kode_bulan'		=> $request->getVar('kode_bulan'),
							'keterangan'		=> $request->getVar('keterangan'),
						];
						
						// $cekprogres = $model->cekParam('data_progres', $request->getVar('id_paket'), $request->getVar('kode_bulan'), 'fisik', $userid);
						// if(empty($cekprogres)){
							$res_progres = $model->saveParam('data_progres', $data_progres);
							
						// }
					}
				}
				
			}

		if($request->getVar('type') == 'keuangan'){
			if($request->getVar('m1')){
				$data['m1'] = $request->getVar('m1');
			}else if($request->getVar('m2')){
				$data['m2'] = $request->getVar('m2');
			}else if($request->getVar('m3')){
				$data['m3'] = $request->getVar('m3');
			}else if($request->getVar('m4')){
				$data['m4'] = $request->getVar('m4');
			}

		}else if($request->getVar('type') == 'fisik'){
			if($request->getVar('m1')){
				$data['m1'] = $request->getVar('m1');
				$data['total']	= $request->getVar('m1');
			}else if($request->getVar('m2')){
				$data['m2'] = $request->getVar('m2');
				$data['total']	= $request->getVar('m2');
			}else if($request->getVar('m3')){
				$data['m3'] = $request->getVar('m3');
				$data['total']	= $request->getVar('m3');
			}else if($request->getVar('m4')){
				$data['m4'] = $request->getVar('m4');
				$data['total']	= $request->getVar('m4');
			}
		}

			if($edited){
				$idnya = $request->getVar('idnya');
				$res = $model->updateDong('bulan_realisasi', $idnya , $data);
				$res2 = $model->updateDong2('data_realisasi', $request->getVar('id_paket'), $request->getVar('kode_bulan') , $data_new);

			}else{
				$cekrealisasi = $model->cekrealisasi($request->getVar('id_paket'), $request->getVar('kode_bulan'), $userid, $type, $request->getVar('m1'), $request->getVar('m2'), $request->getVar('m3'), $request->getVar('m4'));
				
				if(empty($cekrealisasi)){
					$res = $model->saveParam('bulan_realisasi', $data);
				}else{
					if(!array_key_exists("total",$data)){
						$data['total'] = '';
					}
					$res = $model->updateRealisasi($cekrealisasi[0]->id, $request->getVar('m1'), $request->getVar('m2'), $request->getVar('m3'), $request->getVar('m4'), @$data['total']);
				}

				$cekpaket	= $model->cekpaket($request->getVar('id_paket'), $request->getVar('kode_bulan'), $userid);
				
				if(empty($cekpaket)){
					$res_new = $model->saveParam('data_realisasi', $data_new);
					
					if($request->getVar('type') == 'keuangan'){
						$data_latar['type'] = 'keuangan';
						$data_uraian['type'] = 'keuangan';
						$data_permasalahan['type'] = 'keuangan';

						$ceklatar = $model->cekParam('param_latar_belakang', $request->getVar('id_paket'), null, null, $userid);
						if(empty($ceklatar)){
							$res_latar = $model->saveParam('param_latar_belakang', $data_latar);
						}
						$cekuraian = $model->cekParam('param_uraian', $request->getVar('id_paket'), null, null, $userid);
						if(empty($cekuraian)){
							$res_uraian = $model->saveParam('param_uraian', $data_uraian);
						}
						$cekmasalah = $model->cekParam('param_masalah', $request->getVar('id_paket'), $request->getVar('kode_bulan'), 'keuangan' , $userid);
						if(empty($cekmasalah)){
							$res_masalah = $model->saveParam('param_masalah', $data_permasalahan);
						}

					}else if($request->getVar('type') == 'fisik'){
						$data_latar['type'] = 'fisik';
						$data_uraian['type'] = 'fisik';
						$data_permasalahan['type'] = 'fisik';

						$ceklatar = $model->cekParam('param_latar_belakang', $request->getVar('id_paket'), null, null, $userid);
						
						if(empty($ceklatar)){
							$res_latar = $model->saveParam('param_latar_belakang', $data_latar);
						}
						$cekuraian = $model->cekParam('param_uraian', $request->getVar('id_paket'), $request->getVar('kode_bulan'), 'fisik', $userid);
						if(empty($cekuraian)){
							$res_uraian = $model->saveParam('param_uraian', $data_uraian);
						}
						$cekmasalah = $model->cekParam('param_masalah', $request->getVar('id_paket'), $request->getVar('kode_bulan'), 'fisik', $userid);
						if(empty($cekmasalah)){
							$res_masalah = $model->saveParam('param_masalah', $data_permasalahan);
						}

						$cekprogres = $model->cekParam('data_progres', $request->getVar('id_paket'), null, null, $userid);

					}

				}else{
					
					if($request->getVar('type') == 'keuangan'){
						$data_latar['type'] = 'keuangan';
						$data_uraian['type'] = 'keuangan';
						$data_permasalahan['type'] = 'keuangan';

						$ceklatar = $model->cekParam('param_latar_belakang', $request->getVar('id_paket'), null, null, $userid);
						
						if(empty($ceklatar)){
							$res_latar = $model->saveParam('param_latar_belakang', $data_latar);
						}
						$cekuraian = $model->cekParam('param_uraian', $request->getVar('id_paket'), null, null, $userid);
						if(empty($cekuraian)){
							$res_uraian = $model->saveParam('param_uraian', $data_uraian);
						}
						$cekmasalah = $model->cekParam('param_masalah', $request->getVar('id_paket'), $request->getVar('kode_bulan'), $request->getVar('type'), $userid);
						if(empty($cekmasalah)){
							$res_masalah = $model->saveParam('param_masalah', $data_permasalahan);
						}
					}else if($request->getVar('type') == 'fisik'){
						$data_latar['type'] = 'fisik';
						$data_uraian['type'] = 'fisik';
						$data_permasalahan['type'] = 'fisik';

						$ceklatar = $model->cekParam('param_latar_belakang', $request->getVar('id_paket'), null, null, $userid);
						
						if(empty($ceklatar)){
							$res_latar = $model->saveParam('param_latar_belakang', $data_latar);
						}
						$cekuraian = $model->cekParam('param_uraian', $request->getVar('id_paket'), $request->getVar('kode_bulan'), $request->getVar('type'), $userid);
						if(empty($cekuraian)){
							$res_uraian = $model->saveParam('param_uraian', $data_uraian);
						}
						$cekmasalah = $model->cekParam('param_masalah', $request->getVar('id_paket'), $request->getVar('kode_bulan'), $request->getVar('type'), $userid);
						if(empty($cekmasalah)){
							$res_masalah = $model->saveParam('param_masalah', $data_permasalahan);
						}

					}
				}
			}

			$id  = $model->insertID();

			$response = [
					'status'   => 'sukses',
					'code'     => '0',
					'data' 		 => 'terkirim'
			];
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;

		} catch (\Exception $e) {
			die($e->getMessage());
		}

	}

	public function addParam(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$model 	  = new \App\Models\ParamModel();

		$data = [
						'satuan_code' => $request->getVar('satuan_code'),
						'satuan_name'	=> $request->getVar('satuan_name'),
						'satuan_desc' => $request->getVar('satuan_desc'),
        ];

		$res = $model->saveParam($param, $data);
		$id  = $model->insertID();

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addUser(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$model 	  = new \App\Models\UserModel();

		$data = [
			'user_name' 		=> $request->getVar('user_name'),
			'user_email' 		=> $request->getVar('user_email'),
			'user_password' => password_hash($request->getVar('user_password'), PASSWORD_DEFAULT),
			'user_role' 		=> $request->getVar('user_role'),
			'user_fullname' => $request->getVar('user_fullname'),
			'user_satuan' 	=> $request->getVar('user_satuan'),
			'user_status' 	=> 1,
			'create_by' 		=> $this->data['userid'],
			'user_created_at'=> $this->now,
			'nip'=> $request->getVar('nip'),
		];

		$model->save($data);
		// $id  = $model->insertID();

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function updateUser(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$model 	  = new \App\Models\UserModel();

		$data = [
			'user_name' 		=> $request->getVar('user_name'),
			'user_email' 		=> $request->getVar('user_email'),
			'user_fullname' 	=> $request->getVar('user_fullname'),
			'nip'				=> $request->getVar('nip'),
		];
		$model->update($request->getVar('id'), $data);

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function actionBerita(){

		$request  = $this->request;
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		$param 	  = $request->getVar('param');

		$model 	  = new \App\Models\BeritaModel();
		if($param['mode'] == 'headline'){
			$count = $model->countStatus();
			if($count >= 5){
				$data = [
					'update_date' => $this->now,
					'update_by' 	=> $userid,
					'status' 			=> 2,
				];
				$lastId = $model->getMaxId();

				if($param['id'] == $lastId){
					$lastId = $param['id'];
				}
				$model->update($lastId, $data);
			}

			$data = [
				'update_date' => $this->now,
				'update_by' 	=> $userid,
				'status' 			=> $param['stat'],
			];

			$res = $model->update($param['id'], $data);

		}else if($param['mode'] == 'delete'){
			$res = $model->delete($param['id']);
		}else if($param['mode'] == 'update'){
			switch ($param['stat']) {
				case 'false':
						$status = 0;
					break;

				default:
						$status = 2;
					break;
			}
			$data = [
									'update_date' => $this->now,
									'update_by' 	=> $userid,
									'status' => $status,
			        ];
				$res = $model->update($param['id'], $data);
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function actionKegiatan(){

		$request  = $this->request;
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		$param 	  = $request->getVar('param');

		$model 	  = new \App\Models\KegiatanModel();
		if($param['mode'] == 'headline'){
			$count = $model->countStatus();
			if($count >= 5){
				$data = [
					'update_date' => $this->now,
					'update_by' 	=> $userid,
					'status' 			=> 0,
				];
				$lastId = $model->getMaxId();
				$model->update($lastId, $data);
			}

			$data = [
				'update_date' => $this->now,
				'update_by' 	=> $userid,
				'status' 			=> $param['stat'],
			];

			$res = $model->update($param['id'], $data);

		}else if($param['mode'] == 'delete'){
			$res = $model->delete($param['id']);
		}else if($param['mode'] == 'update'){
			switch ($param['stat']) {
				case 'false':
						$status = 0;
					break;

				default:
						$status = 1;
					break;
			}
			$data = [
									'update_date' => $this->now,
									'update_by' 	=> $userid,
									'status' => $status,
			        ];
				$res = $model->update($param['id'], $data);
		}


		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function actionBeritaCovid(){

		$request  = $this->request;
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		$param 	  = $request->getVar('param');

		$model 	  = new \App\Models\BeritaModel();
		if($param['mode'] == 'headline'){
			$count = $model->countStatusCovid();
			if($count >= 5){
				$data = [
					'update_date' => $this->now,
					'update_by' 	=> $userid,
					'status' 			=> 2,
				];
				$lastId = $model->getMaxIdCovid();
				$model->updateBeritaCovid($lastId, $data);
			}

			$data = [
				'update_date' => $this->now,
				'status' 			=> $param['stat'],
			];
			$res = $model->updateBeritaCovid($param['id'], $data);

		}else if($param['mode'] == 'delete'){
			$res = $model->deleteDataCovid($param['id']);
		}else if($param['mode'] == 'update'){
			switch ($param['stat']) {
				case 'false':
						$status = 0;
					break;

				default:
						$status = 2;
					break;
			}
			$data = [
									'update_date' => $this->now,
									'update_by' 	=> $userid,
									'status' => $status,
			        ];
				$res = $model->updateBeritaCovid($param['id'], $data);
		}


		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function actionLaporCovid(){

		$request  = $this->request;
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		$param 	  = $request->getVar('param');
		$id				= $param['id'];

		$model 	  = new \App\Models\PengaduanModel();
		$modelfiles = new \App\Models\FilesModel();

		if($param['mode'] == 'view'){
			$data = $model->getLaporCovid($id);

			foreach ($data as $key => $value) {
				$data[$key]->lampiran  = $modelfiles->getWhere(['id_parent' => $value->id, 'type' => 'kerumunan'])->getResult();
			}

		}else if($param['mode'] == 'delete'){
			$res = $model->deleteDataCovid($param['id']);
		}else if($param['mode'] == 'update'){
			switch ($param['stat']) {
				case 'false':
						$status = 0;
					break;

				default:
						$status = 2;
					break;
			}
			$data = [
									'update_date' => $this->now,
									'update_by' 	=> $userid,
									'status' => $status,
			        ];
				$res = $model->updateBeritaCovid($param['id'], $data);
		}


		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => @$data
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function addKonten(){

		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$mode 	  = $request->getVar('mode');
		$model 	  = new \App\Models\KontenModel();
		$modelfiles = new \App\Models\FilesModel();

		$files	  = $request->getFiles()['img'];
		$path			= FCPATH.'public';
		$tipe			= 'uploads/users/pelayanan/img';
		// $bagian 	= $request->getVar('nama_tujuan');
		$date 		= date('Y/m/d');
		$folder		= $path.'/'.$tipe.'/'.$date.'/';

		$data = [
			'satuan_pelayanan' => $request->getVar('satuan'),
			'jenis_pelayanan' => $request->getVar('jenis'),
			'konten' => $request->getVar('konten'),
			'banner' => null,
			'create_by' => $this->data['userid'],
			'create_date' => $this->now,
    ];

		$res = $model->insert($data);
		$id  = $model->insertID();

		if (!is_dir($folder)) {
		    mkdir($folder, 0777, TRUE);
		}
		// print_r($id);die;
		if($id){
			foreach($files as $idx => $img){

				$stat = $img->move($folder, $img->getName());

				$datalampiran = [
					'id_parent' => $id,
					'file_name' => $img->getName(),
					'extention' => null,
					'size' => $img->getSize('kb'),
					'path' => $tipe.'/'.$date.'/',
					'type' => 'pelayanan',
					'create_date' => $this->now,
					'update_date' => $this->now,
		    ];
				$modelfiles->insert($datalampiran);
				// $saveupload = $model->saveDataUpload($datalampiran);
			}
		}

		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terkirim'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function loadKonten()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\KontenModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					if($param == 'post'){
						$fulldata = [];
						$databerita = $model->getKontenByid($id);
						foreach ($databerita as $keyberita => $valueberita) {

							$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getResult();
							$datasatuan= $model->getSatuanByCode($valueberita->satuan_pelayanan);
							$obj_merged = (object) array_merge((array) $valueberita, (array) $datasatuan);
							$obj_merged->lampiran = (array) $datafiles;
							array_push($fulldata, $obj_merged);
						}
						$berita = $fulldata;
					}else{
							if($param && $id){
								$data = $modelparam->getparam($param, $id);
							}else{
								$data = $model->getSatuan();
							}

							$berita = [];
							foreach ($data as $key => $value) {
								$fulldata = [];
								$databerita = $model->loadKegiatan($value->satuan_code);
								foreach ($databerita as $keyberita => $valueberita) {
									$datafiles = $modelfiles->getWhere(['id_parent' => $valueberita->id])->getRow();
									$obj_merged = (object) array_merge((array) $valueberita, (array) $datafiles);
									array_push($fulldata, $obj_merged);
								}
								$berita[$value->satuan_name] = $fulldata;
							}
						}

					if($berita){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $berita
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	function kawalcorona(){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.kawalcorona.com/indonesia/provinsi/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Cookie: __cfduid=d9578df7ba02ceb3ed84751b41a36c0391610346454"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$result = [
			'status'   => 'sukses',
			'code'     => $this->now,
			'data' 		 => json_decode($response, true)
		];

		header('Content-Type: application/json');
		echo json_encode($result);
		exit;
	}

	function coronas(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://dashboard-pikobar-api.digitalservice.id/v2/kasus/harian?wilayah=kota&kode_kab=3211",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "api-key: 480d0aeb78bd0064d45ef6b2254be9b3"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		// echo $response;
		print_r($response);die;
	}

	public function loadusers()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\UserModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

						$fulldata = [];
						$datauser = $model->getUsers($userid);

						foreach ($datauser as $keyuser => $valueuser) {
							$datafiles = $modelfiles->getWhere(['id_parent' => $valueuser['user_id']])->getRow();
							$datasatuan= $model->getSatuanByCode($valueuser['user_satuan']);
							$obj_merged = (object) array_merge((array) $valueuser, (array) $datafiles, (array) $datasatuan);
							array_push($fulldata, $obj_merged);
						}
						$users = $fulldata;

					if($users){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $users
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadppk()
	{
		try
		{

				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\UserModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
						$fulldata = [];
						$datauser = $model->getUsersPpk($userid);

						foreach ($datauser as $keyuser => $valueuser) {
							$datafiles = $modelfiles->getWhere(['id_parent' => $valueuser['user_id']])->getRow();
							$obj_merged = (object) array_merge((array) $valueuser, (array) $datafiles);
							array_push($fulldata, $obj_merged);
						}
						$users = $fulldata;

					if($users){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $users
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function loadall()
	{
		try
		{

				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];
				$code			= $request->getVar('code');

					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
						$fulldata = [];
						$dataprogram = $model->getall('data_program', 'kode_program, nama_program');
						$dataprogram = json_decode(json_encode($dataprogram), true);
						if(!empty($dataprogram)){
							
							foreach ($dataprogram as $key => $value) {
								$datakegiatan = $model->getall('data_kegiatan','kode_kegiatan, nama_kegiatan', ['kode_program' => $value['kode_program']]);
								$datakegiatan = json_decode(json_encode($datakegiatan), true);
								
								if(!empty($datakegiatan)){
									foreach ($datakegiatan as $key1 => $value1) {
										$datasubkegiatan = $model->getall('data_subkegiatan', 'kode_subkegiatan, nama_subkegiatan', ['kode_kegiatan' => $value1['kode_kegiatan']]);
										$datasubkegiatan = json_decode(json_encode($datasubkegiatan), true);
										if(!empty($datasubkegiatan)){
											foreach ($datasubkegiatan as $key2 => $value2) {
												$datapaket = $model->getall('data_paket', 'id, nama_paket', ['kode_subkegiatan' => $value2['kode_subkegiatan']]);
												$datapaket = json_decode(json_encode($datapaket), true);
												
												if(!empty($datapaket)){
													foreach ($datapaket as $key3 => $value3) {
														$datatarget = $model->getall('data_target', 'id_paket, pagu, bidang, seksi', ['id_paket' => $value3['id'] ]);
														$datatarget = json_decode(json_encode($datatarget), true);
														
														if(!empty($datatarget)){
															foreach ($datatarget as $key4 => $value4) {
																
																$databulantarget_k = $model->getall('bulan_target', $code, ['id_paket' => $value4['id_paket'], 'type' => 'keuangan']);
																$databulantarget_k = json_decode(json_encode($databulantarget_k), true);
																
																$databulantarget_f = $model->getall('bulan_target', $code, ['id_paket' => $value4['id_paket'], 'type' => 'fisik']);
																$databulantarget_f = json_decode(json_encode($databulantarget_f), true);
																
																$datatarget[$key4]['keuangan'] = $databulantarget_k[0][$code];
																$datatarget[$key4]['fisik'] = $databulantarget_f[0][$code];
															}
															
															$datarealisasi = $model->getall('data_realisasi', 'id_paket, koordinat ,kode_bulan, created_by', ['id_paket' => $value3['id'], 'kode_bulan' => $code ]);
															$datarealisasi = json_decode(json_encode($datarealisasi), true);

															$data_latarbelakang = $model->getall('param_latar_belakang', '*', ['id_paket' => $value3['id'], 'kode_bulan' => $code ]);
															$data_latarbelakang = json_decode(json_encode($data_latarbelakang), true);
															
															if(!empty($data_latarbelakang)){
																$data_latarbelakang = $data_latarbelakang[0]['desc'];
															}

															$data_uraian = $model->getall('param_uraian', '*', ['id_paket' => $value3['id'], 'kode_bulan' => $code ]);
															$data_uraian = json_decode(json_encode($data_uraian), true);
															if(!empty($data_uraian)){
																$data_uraian = $data_uraian[0]['desc'];
															}

															$data_masalah = $model->getall('param_masalah', '*', ['id_paket' => $value3['id'], 'kode_bulan' => $code ]);
															$data_masalah = json_decode(json_encode($data_masalah), true);
															if(!empty($data_masalah)){
																$data_masalah = $data_masalah[0]['desc'];
															}
															
															foreach ($datarealisasi as $key5 => $value5) {
																$dataprogres_k = $model->getall('bulan_realisasi', "* , replace(m1, '.','') + replace(m2, '.','') + replace(m3, '.','') + replace(m4, '.','') as new_total", ['id_paket' => $value5['id_paket'], 'type' => 'keuangan', 'kode_bulan' => $value5['kode_bulan']]);
																$dataprogres_k = json_decode(json_encode($dataprogres_k), true);
																$dataprogres_f = $model->getall('bulan_realisasi', '*', ['id_paket' => $value5['id_paket'], 'type' => 'fisik', 'kode_bulan' => $value5['kode_bulan']]);
																$dataprogres_f = json_decode(json_encode($dataprogres_f), true);
																$datarealisasi[$key5]['keuangan'] = $dataprogres_k[0];
																$datarealisasi[$key5]['fisik'] = $dataprogres_f[0];
																$datarealisasi[$key5]['latar_belakang'] = $data_latarbelakang;
																$datarealisasi[$key5]['uraian'] = $data_uraian;
																$datarealisasi[$key5]['permasalahan'] = $data_masalah;
																$datarealisasi[$key5]['ppk'] = $model->getall('users', 'user_fullname', ['user_id' => $value5['created_by']]);
															}
															
															// $datatarget = empty($datatarget) ? 0 : $datatarget[0];
															// $datarealisasi = empty($datarealisasi) ? 0 : $datarealisasi[0];
															if(!empty($datatarget)){
																$datapaket[$key3]['target'] = $datatarget;
																$datapaket[$key3]['realisasi'] = $datarealisasi;
																foreach ($datapaket[$key3]['target'] as $keytar => $valuetar) {
																	$datapaket[$key3]['pagu_paket'] = $valuetar['keuangan'];
																}
															}
														}
														
													}
													
													$datasubkegiatan[$key2]['paket'] = $datapaket;
													$pagu_sub = [];
													$target_keu_sub = [];
													$real_keu_sub = [];
													$target_fis_sub = [];
													$real_fis_sub = [];
													
													foreach ($datasubkegiatan[$key2]['paket'] as $keypak => $valuepak) {
														if(array_key_exists("pagu_paket",$valuepak)){
															if(!empty($valuepak['target']) && !empty($valuepak['realisasi'])){
																array_push($pagu_sub, str_replace(".","",$valuepak['pagu_paket']));
																array_push($target_keu_sub, str_replace(".", "",$valuepak['target'][0]['keuangan']));
																array_push($real_keu_sub, str_replace(".", "",$valuepak['realisasi'][0]['keuangan']['new_total']));
																array_push($target_fis_sub, str_replace(".", "",$valuepak['target'][0]['fisik']));
																array_push($real_fis_sub, str_replace(".", "",$valuepak['realisasi'][0]['fisik']['total']));
															}
														}
													}
													
													$datasubkegiatan[$key2]['pagu_subkegiatan'] = array_sum($pagu_sub);
													
													$datasubkegiatan[$key2]['target_keu_subkegiatan']= array_sum($target_keu_sub);
													$datasubkegiatan[$key2]['target_persen_keu_subkegiatan']= round(array_sum($target_keu_sub) == 0 ? 1 : array_sum($target_keu_sub)  / array_sum($pagu_sub),2);
													
													$datasubkegiatan[$key2]['real_keu_subkegiatan']= array_sum($real_keu_sub);
													$datasubkegiatan[$key2]['real_persen_keu_subkegiatan']= round(array_sum($real_keu_sub) == 0 ? 1 : array_sum($real_keu_sub) / array_sum($pagu_sub),2);
													
													$datasubkegiatan[$key2]['target_fis_subkegiatan']= array_sum($target_fis_sub);
													$datasubkegiatan[$key2]['real_fis_subkegiatan']= array_sum($real_fis_sub);

													$datasubkegiatan[$key2]['dev_keu_subkegiatan']= round((array_sum($real_keu_sub) == 0 ? 1 : array_sum($real_keu_sub) / array_sum($pagu_sub))-(array_sum($target_keu_sub) == 0 ? 1 : array_sum($target_keu_sub) / array_sum($pagu_sub)),2) ;
													$datasubkegiatan[$key2]['dev_fis_subkegiatan']= array_sum($real_fis_sub)-array_sum($target_fis_sub) ;
												}

												
											}
											
											$newdatasubkegatan = array();
											foreach ($datasubkegiatan as $kekey => $valuey) {
												if(count($valuey) > 2){
													foreach ($valuey['paket'] as $keykey1 => $valuey1) {
														if(!empty($valuey1['target']) && !empty($valuey1['realisasi'])){
															array_push($newdatasubkegatan, $valuey);
														}
													}
												}
											}
											
											$datakegiatan[$key1]['subkegiatan'] = $newdatasubkegatan;
											
											$pagu_keg = [];
											$target_keu_keg = [];
											$real_keu_keg = [];
											$target_fis_keg = [];
											$real_fis_keg = [];
											
											foreach ($datakegiatan[$key1]['subkegiatan'] as $keysub => $valuesub) {
												if(isset($valuesub['pagu_subkegiatan'])){
													array_push($pagu_keg, $valuesub['pagu_subkegiatan']);
													array_push($target_keu_keg, str_replace(".", "",$valuesub['target_keu_subkegiatan']));
													array_push($real_keu_keg, str_replace(".", "",$valuesub['real_keu_subkegiatan']));
													array_push($target_fis_keg, str_replace(".", "",$valuesub['target_fis_subkegiatan']));
													array_push($real_fis_keg, str_replace(".", "",$valuesub['real_fis_subkegiatan']));
												}
												
											}
											
											$datakegiatan[$key1]['pagu_kegiatan']= array_sum($pagu_keg);
											$datakegiatan[$key1]['target_keu_kegiatan']= array_sum($target_keu_keg);
											
											$datakegiatan[$key1]['target_persen_keu_kegiatan']= round(array_sum($target_keu_keg) == 0 ? 1 : array_sum($target_keu_keg) / array_sum($pagu_keg),2);

											$datakegiatan[$key1]['real_keu_kegiatan']= array_sum($real_keu_keg);
											$datakegiatan[$key1]['real_persen_keu_kegiatan']= round(array_sum($real_keu_keg) == 0 ? 1 : array_sum($real_keu_keg) / array_sum($pagu_keg),2);

											$datakegiatan[$key1]['target_fis_kegiatan']= array_sum($target_fis_keg);
											$datakegiatan[$key1]['real_fis_kegiatan']= array_sum($real_fis_keg);
										}

										
										
									}

									$newdatakegiatan = array();
									foreach ($datakegiatan as $kekeg => $valueg) {
										if(count($valueg) > 2){
											if(!empty($valueg['subkegiatan'])){
												array_push($newdatakegiatan, $valueg);
											}
										}
									}
									// print_r($newdatakegiatan);
									$dataprogram[$key]['kegiatan'] = $newdatakegiatan;
									
									$pagu_prog = [];
									$target_keu_prog = [];
									$real_keu_prog = [];
									$target_fis_prog = [];
									$real_fis_prog = [];
									foreach ($dataprogram[$key]['kegiatan'] as $keykeg => $valuekeg) {
										if(count($valuekeg) > 2){
											array_push($pagu_prog, $valuekeg['pagu_kegiatan']);
											array_push($target_keu_prog, str_replace(".", "",$valuekeg['target_keu_kegiatan']));
											array_push($real_keu_prog, str_replace(".", "",$valuekeg['real_keu_kegiatan']));
											array_push($target_fis_prog, str_replace(".", "",$valuekeg['target_fis_kegiatan']));
											array_push($real_fis_prog, str_replace(".", "",$valuekeg['real_fis_kegiatan']));
										}
									}

									$dataprogram[$key]['pagu_program']	   = array_sum($pagu_prog);
									$dataprogram[$key]['target_keu_program']= array_sum($target_keu_prog);
									$dataprogram[$key]['target_persen_keu_program']= round(array_sum($target_keu_prog) == 0 ? 1 : array_sum($target_keu_prog) / array_sum($pagu_prog),2);
									$dataprogram[$key]['real_keu_program']= array_sum($real_keu_prog);
									$dataprogram[$key]['real_persen_keu_program']= round(array_sum($real_keu_prog) == 0 ? 1 : array_sum($real_keu_prog) / array_sum($pagu_prog),2);
									$dataprogram[$key]['target_fis_program']= array_sum($target_fis_prog);
									$dataprogram[$key]['real_fis_program']= array_sum($real_fis_prog);
								}

								$newdataprogram = array();
								foreach ($dataprogram as $keyprog => $valueprog) {
									if(count($valueprog) > 2){
										if(!empty($valueprog['kegiatan'])){
											array_push($newdataprogram, $valueprog);
										}
									}
								}
								
							}
						}

						
						// die;
					if($newdataprogram){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $newdataprogram
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			echo '<pre>';
			print_r($e->getTraceAsString());die;
			$response = [
				'status'   => 'gagal',
				'code'     => '0',
				'data'     => $e->getTraceAsString(),
			];

			header('Content-Type: application/json');
				echo json_encode($response);
				exit;
		}
	}

	public function actionUsers(){

		$request  = $this->request;
		$mode 	  = $request->getVar('mode');
		$id 	  	= $request->getVar('id');
		$status 	= $request->getVar('status');
		$role 		= $this->data['role'];
		$userid		= $this->data['userid'];

		switch ($status) {
			case 'false':
					$status = 0;
				break;

			default:
					$status = 1;
				break;
		}
		$model 	  = new \App\Models\UserModel();

		$data = [
						'update_date' => $this->now,
						'update_by' 	=> $userid,
						'user_status' => $status,
        ];
		if($mode == 'update'){
			$res = $model->update($id, $data);

		}else{
			$res = $model->delete(['user_id' => $id]);
		}
		$response = [
				'status'   => 'sukses',
				'code'     => '0',
				'data' 		 => 'terupdate'
		];
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;

	}

	public function loadkota(){
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	  = $request->getVar('id');
				$role 		= $this->data['role'];
				$userid		= $this->data['userid'];

					$model = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();

					$data = $model->getkota($param, $id);

					if($data){
						$response = [
							'status'   => 'sukses',
							'code'     => '1',
							'data' 		 => $data
						];
					}else{
						$response = [
						    'status'   => 'gagal',
						    'code'     => '0',
						    'data'     => 'tidak ada data',
						];
					}

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function deleteData(){
		
		try {
			$request  = $this->request;
			$table 	  	= $request->getVar('table');
			$id 	  	= $request->getVar('id');

			$role 		= $this->data['role'];
			$userid		= $this->data['userid'];

			$model 	  = new \App\Models\ProgramModel();
			$modelpaket 	  = new \App\Models\KegiatanModel();
			
			if($table == 'data_paket'){
				$datapaket = $modelpaket->getpaketbyid($id, $userid);
				if(!empty($datapaket)){
					$kodeprog = $datapaket[0]->kode_program;
					$kodekeg = $datapaket[0]->kode_kegiatan;
					$kodesubkeg = $datapaket[0]->kode_subkegiatan;

					$pagupaket = str_replace(".","",$datapaket[0]->pagu_paket);
					
					$datasub = $modelpaket->getsubaja($kodeprog, $kodekeg, $kodesubkeg);
					$sisapagusub = $datasub[0]->sisa_pagu_subkegiatan;
					$sisapagu = $sisapagusub + $pagupaket;

					$modelpaket->updatePaguSub($kodeprog, $kodekeg, $kodesubkeg, $sisapagu);
				}
			}else if($table == 'data_subkegiatan'){
				$datapaket = $modelpaket->getsubkegbyid($id, $userid);
				if(!empty($datapaket)){
					$kodeprog = $datapaket[0]->kode_program;
					$kodekeg = $datapaket[0]->kode_kegiatan;
					$kodesubkeg = $datapaket[0]->kode_subkegiatan;

					$modelpaket->deletePaket($kodeprog ,$kodekeg ,$kodesubkeg );
				}
				
			}else if($table == 'data_kegiatan'){
				$datakeg = $modelpaket->getkegbyid($id, $userid);
				if(!empty($datakeg)){
					$kodeprog = $datakeg[0]->kode_program;
					$kodekeg = $datakeg[0]->kode_kegiatan;

					$modelpaket->deletesubkeg($kodeprog ,$kodekeg);
					$modelpaket->deletePaketByprogkeg($kodeprog ,$kodekeg);
				}
								
			}else if($table == 'data_program'){
				$dataprog = $modelpaket->getprogbyid($id, $userid);
				$kodeprog = $dataprog[0]->kode_program;
				$modelpaket->deletekegbyprog($kodeprog);
				$modelpaket->deletesubkegbyprog($kodeprog);
				$modelpaket->deletePaketByprog($kodeprog);
			}

			$res = $model->deleteGlob($table, $id);
			
			$response = [
					'status'   => 'sukses',
					'code'     => '0',
					'data' 		 => 'terupdate'
			];
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}catch (\Exception $e){
			die($e->getTraceAsString());
		}

	}

}
