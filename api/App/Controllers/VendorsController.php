<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\VendorsModel;
use App\Models\ProfilesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VendorsController extends DefaultController
{
	
	public function index()
	{
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems();
		return $items;
	}
	public function getCount()
	{
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems();
		$result = 0;
		for($i=0;$i<count($items);$i++)
		{
			$param_array = json_decode($items[$i]->params);
			if($param_array['sendupdates']==1)
			{
				$result++;
			}
		}
		return $result;
	}
	
	public function add()
	{
		
		$id = $this->getInput()->getString('id');
		$title = $this->getInput()->getString('title');
		$description = $this->getInput()->getString('description');
		$shop_type = $this->getInput()->getString('shop_type');
		$email = $this->getInput()->getString('email');
		$full_name = $this->getInput()->getString('full_name');
		if($this->getInput()->getString('email')==null)
		{
			return array("success"=>false);
		}
		$date = Date("Y-m-d H:i:s");

		$result = array("success"=>false);

		$body = 'Title: '.$title.'\n Description: '.$description.'\n Shop Type: '.$shop_type.'\n Full Name: '.$full_name.'\n E-mail: '.$email;

		if(mail('admin@ushbub.co.uk', "New shop registration", $body)){
			$result['success'] = true;
			$result['msg'] = 'Excellent, you are one step closer to selling on our platform! To ensure you are human, we will contact you soon to verify your account.<br>You can create your account whilst we validate your details. <a class="btn btn-primary" href="https://www.ushbub.co.uk/registration-to-ushbub.html">Create</a>';
		}
		
		return $result;
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