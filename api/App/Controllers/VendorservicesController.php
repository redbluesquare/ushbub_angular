<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\VendorsModel;
use App\Models\ProfilesModel;
use App\Models\ServicesModel;
use App\Models\VendorlocationsModel;
use App\Models\VehiclesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VendorservicesController extends DefaultController
{
	
	public function index(){
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
		$model = new ServicesModel($this->getInput(), $this->getContainer()->get('db'));
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
			$params = json_encode(
				array(
					'car' => $input->get('car_id')
				)
			);
			$columns = array("user_id","session_id","vendor_id","first_name","last_name",
				"payment_method","email_to","book_date","mobile_no","planned_start_time",
				"planned_end_time","catid","created_by","created_on","state");
			$data = array($user['user_id'],$usertoken,$this->getInput()->json->get('vendor_id',0),
				$user['first_name'],$user['last_name'],$this->getInput()->json->get('payment_method',null,'string'),$user['email'],
				$this->getInput()->json->get('service_date',null,'string'),$this->getInput()->json->get('mobile_no',null,'string'),
				$this->getInput()->json->get('planned_start_time','09:00','string'),$this->getInput()->json->get('planned_end_time','17:00','string'),
				$this->getInput()->json->get('cat_id',0),$user['user_id'],$date,$this->getInput()->json->get('state',0)
			);
			$table = "#__ddc_service_headers";
			if($result = $model->insert($table,$columns,$data)){
				//save service details
				$columns = array('service_header_id','product_id','product_quantity','product_pack','product_price','currency','params','state','created_by','created_on');
				$data = array($result->id,1,1,1,$this->getInput()->json->get('service_price','15.00','string'),52,$params,1,$user['user_id'],$date);
				$table = '#__ddc_service_details';
				if($dresult = $model->insert($table,$columns,$data)){
					$item = $model->listItems($user['user_id'],$result->id);
					$vehiclemodel = new VehiclesModel($this->getInput(), $this->getContainer()->get('db'));
					for($i=0;$i<count($item);$i++){
						$item[$i]->params = json_decode($item[$i]->params);
						if($item[$i]->params->car){
							$item[$i]->params->car = $vehiclemodel->getItemById($item[$i]->params->car);
						}
					}
				}
				
			}
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			
			$vehiclemodel = new VehiclesModel($this->getInput(), $this->getContainer()->get('db'));
			$item = $model->listItems($user['user_id'],$id);
			for($i=0;$i<count($item);$i++){
				$item[$i]->params = json_decode($item[$i]->params);
				if($item[$i]->params->car){
					$item[$i]->params->car = $vehiclemodel->getItemById($item[$i]->params->car);
				}
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


}