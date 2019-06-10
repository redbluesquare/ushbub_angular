<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\VehiclesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VehiclesController extends DefaultController
{
	
	public function index()
	{
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		$usertoken = '';
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		$model = new VehiclesModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$data = array(
				$user['user_id'],
				$this->getInput()->json->get('make',null),
				$this->getInput()->json->get('model',null),
				$this->getInput()->json->get('year',null),
				$this->getInput()->json->get('car_reg',null,'string'),
				$this->getInput()->json->get('colour',null),
				$this->getInput()->json->get('trim',null),
				$this->getInput()->json->get('params',null,'string'),
				$date,
				$user['user_id']
				
			);
			$columns = array('user_id','make_id','model_id','year','registration_number','colour','trim','params','created_on','created_by');
			$table = '#__ddc_user_vehicles';
			if($result = $model->insert($table,$columns,$data)){
				$item = $model->listItems($result->id);
			}
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$item = $model->listItems($id,$user['user_id']);

		}
		if(($input = $this->getInput()->getMethod()==='DELETE') &&($user['user_id'])){
			$conditions = array(
				$this->getContainer()->get('db')->qn('id'). ' = '. $id
				);
			$table = '#__ddc_user_vehicles';
			if($result = $model->delete($table,$conditions)){
				$item->success = true;
			}

		}
		return $item;
	}

	public function locations(){
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		$usertoken = '';
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		$vmodel = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('id');
		$token = $this->getInput()->json->get('apptoken',null);
		$model = new VendorlocationsModel($this->getInput(), $this->getContainer()->get('db'));
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$item = $model->listItems($id);
			return $item;
		}
	}

	public function getTown(){
		$id = urldecode($this->getInput()->getString('id'));
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$city = $model->setLocation($id);
		return array($city);
	}

	public function producttypes(){
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		$usertoken = '';
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('id');
		$input = $this->getInput()->json;
		$token = $this->getInput()->json->get('apptoken',null);
		$pc = $this->getInput()->getString('pc',null,'string');
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			
			
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$item = $model->getProductTypes($id, null, $pc);
			return $item;
		}

	}
}