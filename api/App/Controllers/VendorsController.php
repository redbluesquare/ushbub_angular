<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\VendorsModel;
use App\Models\ProfilesModel;
use App\Models\VendorlocationsModel;
use App\Models\VendoruserModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VendorsController extends DefaultController
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
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$vumodel = new VendoruserModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$ddc_vendor_id = $this->getInput()->json->get('ddc_vendor_id',null,'integer');
			$title = $this->getInput()->json->get('title',null,'string');
			$description = $this->getInput()->json->get('description',null,'string');
			$shop_type = $this->getInput()->json->get('shop_type',null,'string');
			$email = $this->getInput()->json->get('email',null,'string');
			$full_name = $this->getInput()->json->get('full_name',null,'string');
			$introduction = $this->getInput()->json->get('introduction',null,'string');
			$address1 = $this->getInput()->json->get('address1',null,'string');
			$address2 = $this->getInput()->json->get('address2',null,'string');
			$city = $this->getInput()->json->get('city',null,'string');
			$county = $this->getInput()->json->get('county',null,'string');
			$country = $this->getInput()->json->get('country',null,'string');
			$post_code = $this->getInput()->json->get('post_code',null,'string');
			if($email==null)
			{
				return array("success"=>false);
			}
			if($ddc_vendor_id==0){
				$columns = array('title','alias','introduction','description','owner','vendor_details','state','created_on','created_by','address1','address2','city','county','country','post_code',);
				$data = array($title,preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title))),
							$shop_type, $description, $user['user_id'], json_encode(array('contact_email'=>$email)),0,$date,$user['user_id'],$address1,$address2,$city,$county,$country,$post_code);
				$table = '#__ddc_vendors';
				if($model->insert($table,$columns,$data)){
					$body = 'Title: '.$title.'\n Description: '.$description.'\n Shop Type: '.$shop_type.'\n Full Name: '.$full_name.'\n E-mail: '.$email;
					if(mail('admin@ushbub.co.uk', "New shop registration", $body)){
						$item->success = true;
						$item->msg = 'Excellent, thanks for submitting a new shop to Ushbub! We hope to have the shop added soon.';
					}
				}
				else{
					$item->msg = 'Sorry, something went wrong.';
				}
			}
			else{
				$table = '#__ddc_vendors';
				$fields = array(
					$this->getContainer()->get('db')->qn('title'). " = ". $this->getContainer()->get('db')->q($title),
					$this->getContainer()->get('db')->qn('alias'). " = ". $this->getContainer()->get('db')->q(preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title)))),
					$this->getContainer()->get('db')->qn('introduction'). " = ". $this->getContainer()->get('db')->q($introduction),
					$this->getContainer()->get('db')->qn('description'). " = ". $this->getContainer()->get('db')->q($description),
					$this->getContainer()->get('db')->qn('modified_on'). " = ". $this->getContainer()->get('db')->q($date),
					$this->getContainer()->get('db')->qn('modified_by'). " = ". $this->getContainer()->get('db')->q($user['user_id']),
					$this->getContainer()->get('db')->qn('address1'). " = ". $this->getContainer()->get('db')->q($address1),
					$this->getContainer()->get('db')->qn('address2'). " = ". $this->getContainer()->get('db')->q($address2),
					$this->getContainer()->get('db')->qn('city'). " = ". $this->getContainer()->get('db')->q($city),
					$this->getContainer()->get('db')->qn('county'). " = ". $this->getContainer()->get('db')->q($county),
					$this->getContainer()->get('db')->qn('country'). " = ". $this->getContainer()->get('db')->q($country),
					$this->getContainer()->get('db')->qn('post_code'). " = ". $this->getContainer()->get('db')->q($post_code)
				);
				$conditions = array($this->getContainer()->get('db')->qn('ddc_vendor_id'). ' = '. $ddc_vendor_id);
				$model->update($table,$fields,$conditions);
			}
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//List all vendors
			$item = $model->listItems($id);
			$admin = $this->getInput()->getString('admin','');
			for($i=0;$i<count($item);$i++){
				$item[$i]->vendor_details = json_decode($item[$i]->vendor_details);
				if($vumodel->getItemById($item[$i]->ddc_vendor_id,$this->getInput()->getString('user_id'))){
					$item[$i]->admin=true;
				}else{
					$item[$i]->admin=false;	
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
			echo $id;
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

	public function getcountries(){
		$id = urldecode($this->getInput()->getString('id'));
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$countries = $model->getCountries($id);
		return $countries;
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