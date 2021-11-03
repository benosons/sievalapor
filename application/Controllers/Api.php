<?php namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Models\UserModel;
use App\Controller\BaseController;

class Api extends \CodeIgniter\Controller
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

  public function auth()
	{
		try {
			
			$model = new UserModel();
			$userModel = new \App\Models\UserModel();
			
			$email = $this->request->getVar('username');
			$password = $this->request->getVar('password');
			$data = $model->getWhere(['user_name' => $email])->getRow();
			if($data){
					$pass = $data->user_password;
					$hash =  substr_replace($pass, "$2y$10", 0, 1);
					$verify_pass = password_verify($password, $hash);
					if($verify_pass){

							$users = $userModel->updateIsLogin($data->user_id, ['isLogin' => 1]);
							$result = [
								'info'   => 'success',
								'code'     => 200,
								'data'     => $data,
							];

							header('Content-Type: application/json');
							echo json_encode($result);
							exit;
							
					}else{
							$result = [
								'info'   => 'failed',
								'code'     => 1,
								'data'     => 'Password Salah',
							];

							header('Content-Type: application/json');
							echo json_encode($result);
							exit;
					}
			}else{
					$result = [
						'info'   => 'failed',
						'code'     => 2,
						'data'     => 'User belum terdaftar',
					];

					header('Content-Type: application/json');
					echo json_encode($result);
					exit;
			}
			//code...
		} catch (\Throwable $e) {
			echo '<pre>';
			print_r($e->getTraceAsString());die;
		}
	}


	public function isloadtarget()
	{
		try
		{
				$request  		= $this->request;
				$param 	  		= $request->getVar('param');
				$id		 	  	= $request->getVar('id');
				$role 			= $request->getVar('role'); //30
				$userid			= $request->getVar('userid'); //467
				$code 			= $request->getVar('code');
				
					$model = new \App\Models\TargetModel();
					$modelparam = new \App\Models\ParamModel();
					$modelfiles = new \App\Models\FilesModel();
			
					$fulldata = [];
					
					$datapaket = $model->gettarget($code, $role, $userid);
					
					if(!empty($datapaket)){
						if($code){
							$uangan = [];
							$fisikan = [];
							foreach ($datapaket as $key => $value) {
								if($value->type == 'keuangan'){
									if($value->n1){
										$uangan['n1'] = 'Januari';
									}
									if($value->n2){
										$uangan['n2'] = 'Februari';
									}
									if($value->n3){
										$uangan['n3'] = 'Maret';
									}
									if($value->n4){
										$uangan['n4'] = 'April';
									}
									if($value->n5){
										$uangan['n5'] = 'Mei';
									}
									if($value->n6){
										$uangan['n6'] = 'Juni';
									}
									if($value->n7){
										$uangan['n7'] = 'Juli';
									}
									if($value->n8){
										$uangan['n8'] = 'Agustus';
									}
									if($value->n9){
										$uangan['n9'] = 'September';
									}
									if($value->n10){
										$uangan['n10'] = 'Oktober';
									}
									if($value->n11){
										$uangan['n11'] = 'November';
									}
									if($value->n12){
										$uangan['n12'] = 'Desember';
									}

								}

								if($value->type == 'fisik'){
									if($value->n1){
										$fisikan['n1'] = 'Januari';
									}
									if($value->n2){
										$fisikan['n2'] = 'Februari';
									}
									if($value->n3){
										$fisikan['n3'] = 'Maret';
									}
									if($value->n4){
										$fisikan['n4'] = 'April';
									}
									if($value->n5){
										$fisikan['n5'] = 'Mei';
									}
									if($value->n6){
										$fisikan['n6'] = 'Juni';
									}
									if($value->n7){
										$fisikan['n7'] = 'Juli';
									}
									if($value->n8){
										$fisikan['n8'] = 'Agustus';
									}
									if($value->n9){
										$fisikan['n9'] = 'September';
									}
									if($value->n10){
										$fisikan['n10'] = 'Oktober';
									}
									if($value->n11){
										$fisikan['n11'] = 'November';
									}
									if($value->n12){
										$fisikan['n12'] = 'Desember';
									}
								}
							}

							$fulldata['kode_program'] = $datapaket[0]->kode_program;
							$fulldata['nama_program'] = $datapaket[0]->nama_program;
							$fulldata['kode_kegiatan'] = $datapaket[0]->kode_kegiatan;
							$fulldata['nama_kegiatan'] = $datapaket[0]->nama_kegiatan;
							$fulldata['kode_subkegiatan'] = $datapaket[0]->kode_subkegiatan;
							$fulldata['nama_subkegiatan'] = $datapaket[0]->nama_subkegiatan;
							$fulldata['paket'] = $datapaket[0]->nama_paket;
							$fulldata['id_paket'] = $datapaket[0]->id_paket;
							$fulldata['pagu_kegiatan'] = $datapaket[0]->pagu;
							$keuang = [];
							foreach ($uangan as $keyuang => $valueuang) {
								$minggukeuangan = $model->getminggu('keuangan', $keyuang, $datapaket[0]->id_paket);
								
								if(!empty($minggukeuangan)){
									if(!$minggukeuangan[0]->m1){
										// $uangan[$keyuang]= 'm1';
										array_push($keuang, [
											'bulan_id' => $keyuang,
											'bulan_name' => $valueuang,
											'minggu' => 'm1'
										]);

									}else if(!$minggukeuangan[0]->m2){
										// $uangan[$keyuang]= 'm2';
										array_push($keuang, [
											'bulan_id' => $keyuang,
											'bulan_name' => $valueuang,
											'minggu' => 'm2'
										]);
									}else if(!$minggukeuangan[0]->m3){
										// $uangan[$keyuang]= 'm3';
										array_push($keuang, [
											'bulan_id' => $keyuang,
											'bulan_name' => $valueuang,
											'minggu' => 'm3'
										]);
									}else if(!$minggukeuangan[0]->m4){
										// $uangan[$keyuang]= 'm4';
										array_push($keuang, [
											'bulan_id' => $keyuang,
											'bulan_name' => $valueuang,
											'minggu' => 'm4'
										]);
									}else{
										// $uangan[$keyuang]= 'done';
										array_push($keuang, [
											'bulan_id' => $keyuang,
											'bulan_name' => $valueuang,
											'minggu' => 'done'
										]);
									}
								}else{
									// $uangan[$keyuang]= 'm1';
									array_push($keuang, [
										'bulan_id' => $keyuang,
										'bulan_name' => $valueuang,
										'minggu' => 'm1'
									]);
								}
							}
							
							$fisi = [];
							foreach ($fisikan as $keyfisik => $valuefisik) {
								$minggufisik = $model->getminggu('fisik', $keyfisik, $datapaket[0]->id_paket);
								
								if(!empty($minggufisik)){
									if(!$minggufisik[0]->m1){
										// $fisikan[$keyfisik] = 'm1';
										array_push($fisi, [
											'bulan_id' => $keyfisik,
											'bulan_name' => $valuefisik,
											'minggu' => 'm1'
										]);
									}else if(!$minggufisik[0]->m2){
										// $fisikan[$keyfisik] = 'm2';
										array_push($fisi, [
											'bulan_id' => $keyfisik,
											'bulan_name' => $valuefisik,
											'minggu' => 'm2'
										]);
									}else if(!$minggufisik[0]->m3){
										// $fisikan[$keyfisik] = 'm3';
										array_push($fisi, [
											'bulan_id' => $keyfisik,
											'bulan_name' => $valuefisik,
											'minggu' => 'm3'
										]);
									}else if(!$minggufisik[0]->m4){
										// $fisikan[$keyfisik] = 'm4';
										array_push($fisi, [
											'bulan_id' => $keyfisik,
											'bulan_name' => $valuefisik,
											'minggu' => 'm4'
										]);
									}else{
										// $fisikan[$keyfisik] = 'done';
										array_push($fisi, [
											'bulan_id' => $keyfisik,
											'bulan_name' => $valuefisik,
											'minggu' => 'done'
										]);
									}
								}else{
									// $fisikan[$keyfisik]= 'm1';
									array_push($fisi, [
										'bulan_id' => $keyfisik,
										'bulan_name' => $valuefisik,
										'minggu' => 'm1'
									]);
								}
							}
							$fulldata['keuangan'] = $keuang;
							
							$fulldata['fisik'] = $fisi;
						}else{
							$fulldata = $datapaket;
						}
						
						if($fulldata){
							$response = [
								'status'   => 'success',
								'code'     => 200,
								'data' 		 => $fulldata
							];
						}else{
							$response = [
									'status'   => 'failed',
									'code'     => '0',
									'data'     => 'tidak ada data',
							];
						}
					}else{
						$response = [
							'status'   => 'failed',
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

	public function isaddrealisasi(){
		try {
			//code...
		
		$request  = $this->request;
		$param 	  = $request->getVar('param');
		$role 		= $request->getVar('role');
		$userid 	= $request->getVar('userid');

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

				if(!empty($request->getFile('files'))){
					
					$files	  = $request->getFiles()['files'];
					
					$path			= FCPATH.'public';
					$tipe			= 'uploads/users/progres';
					$date 		= date('Y/m/d');
					$folder		= $path.'/'.$tipe.'/'.$date.'/';
					
					
					if (!is_dir($folder)) {
						mkdir($folder, 0777, TRUE);
					}
					

						# code...	
						$stat = $files->move($folder, $files->getName());
						
						$data_progres = [
							'id_paket'			=> $request->getVar('id_paket'),
							'progres'			=> $request->getVar('progres'),
							'filename'			=> $files->getName(),
							'extention'			=> null,
							'size'				=> $files->getSize('kb'),
							'path'				=> $tipe.'/'.$date.'/',
							'type'				=> 'fisik',
							'created_date'		=> $this->now,
							'updated_date'		=> $this->now,
							'create_by'			=> $userid,
							'kode_bulan'		=> $request->getVar('kode_bulan'),
							'keterangan'		=> $request->getVar('keterangan'),
						];
						
							$res_progres = $model->saveParam('data_progres', $data_progres);
							
				}
				
			}

		if($request->getVar('type') == 'keuangan'){
			
			for ($i=1; $i <= 4 ; $i++) { 
				# code...
				if($request->getVar('param') == 'm'.$i ){
					$data['m'.$i] = $request->getVar('value');
				}
			}
			// if($request->getVar('m1')){
			// 	$data['m1'] = $request->getVar('m1');
			// }else if($request->getVar('m2')){
			// 	$data['m2'] = $request->getVar('m2');
			// }else if($request->getVar('m3')){
			// 	$data['m3'] = $request->getVar('m3');
			// }else if($request->getVar('m4')){
			// 	$data['m4'] = $request->getVar('m4');
			// }
			
		}else if($request->getVar('type') == 'fisik'){
			for ($i=1; $i <= 4 ; $i++) { 
				# code...
				if($request->getVar('param') == 'm'.$i ){
					$data['m'.$i] = $request->getVar('value');
					$data['total']	= $request->getVar('value');
				}
			}
			// if($request->getVar('m1')){
			// 	$data['m1'] = $request->getVar('m1');
			// 	$data['total']	= $request->getVar('m1');
			// }else if($request->getVar('m2')){
			// 	$data['m2'] = $request->getVar('m2');
			// 	$data['total']	= $request->getVar('m2');
			// }else if($request->getVar('m3')){
			// 	$data['m3'] = $request->getVar('m3');
			// 	$data['total']	= $request->getVar('m3');
			// }else if($request->getVar('m4')){
			// 	$data['m4'] = $request->getVar('m4');
			// 	$data['total']	= $request->getVar('m4');
			// }
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
					'code'     => 200,
					'data' 		 => 'terkirim'
			];
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;

		} catch (\Exception $e) {
			die($e->getMessage());
		}

	}

	public function isloadminggu()
	{
		try
		{
				$request  = $this->request;
				$param 	  = $request->getVar('param');
				$id		 	= $request->getVar('id');
				$role 		= $request->getVar('role');
				$userid		= $request->getVar('userid');
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
							'code'     => 200,
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

	

}
