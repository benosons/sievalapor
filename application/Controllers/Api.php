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

	

}
