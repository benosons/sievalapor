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
								'code'     => 0,
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
						
						$fulldata['keuangan'] = $uangan;
						$fulldata['fisik'] = $fisikan;
					}else{
						$fulldata = $datapaket;
					}
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

	

}
