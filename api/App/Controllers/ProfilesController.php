<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\UsergroupsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;
use App\Models\App\Models;
use Joomla\Http\Http;

class ProfilesController extends DefaultController
{
	
	public function index()
	{
		$id = $this->getInput()->getString('usertoken',null);
		$token = $this->getInput()->getString('apptoken',null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$item = array("profile"=>"not authorised");
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$item = $model->getItemById($id);
		}
		return $item;
	}
	public function login()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$item = new \StdClass();
		$item->success = false;
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
			$email = $this->getInput()->json->get("email",null,'string');
			$password = $this->getInput()->json->get("sk", null,'string');
			$usertoken = $this->getInput()->json->get("usertoken", null,'string');
			if($model->loginuser($email,$password))
			{
				if($usertoken==null){
				$user = $model->authenticate_email($email);
				}
				if($user['success']==true)
				{
					$item->success = true;
					$item->user_id = $user['user_id'];
					$item->first_name = $user['first_name'];
					$item->last_name = $user['last_name'];
					$item->usertoken = $user['token'];
				}
			}
		}
		return $item;
	}
	public function usergroup()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$item = new \StdClass();
		$item->success = false;

		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$ugmodel = new UsergroupsModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = $this->getInput()->json->get("usertoken", null,'string');
		$ug = $this->getInput()->json->get("usergroup", null,'string');
		$usergroup = $ugmodel->getUsergroup(null,$ug);
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$user = $model->authenticate_token($usertoken);
		$ugm = $ugmodel->getItemById($user['user_id'],$usergroup->id);
		$state = 0;
		//add a new usergroup_map
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			if($ugm){
				$item->msg = 'user group already created';
				return $item;
			}
			$data = array(
				$user['user_id'],
				$usergroup->id,
				null,
				$state
			);
			$columns = array("user_id", "group_id", "token", "state");
			$item = $model->insert('#__ddc_user_usergroup_map',$columns, $data);
			$item = $ugmodel->getItemById($user['user_id'],$usergroup->id);
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$id = urldecode($this->getInput()->getString('id',null,'string'));
			$usergroup = $ugmodel->getUsergroup(null,$id);
			$item = $ugmodel->getItemById($user['user_id'],$usergroup->id);
			
			return $item;
		}
		
		return $item;
	}
	public function authenticate()
	{
		$input = $this->getInput()->json;
		$token = $this->getInput()->json->get("apptoken",null,'string');
		$item = new \StdClass();
		$item->success = false;
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$model = new ProfilesModel($input, $this->getContainer()->get('db'));
			$email = (string)$input->get("email", null,'string');
			$usertoken = $input->get("usertoken", null);
			$ref = $input->get('ref',1);
			$regpoint = $input->get('regpoint',null,'string');
			if($ref == 1){
				if($usertoken!=null){
					$user = $model->authenticate_token($usertoken);
					if($user['success']==false){
						$usertoken = null;
					}
				}
				if($user['success']==true)
				{
					$item->success = true;
					$item->user_id = $user['user_id'];
					$item->first_name = $user['first_name'];
					$item->last_name = $user['last_name'];
					$item->usertoken = $user['token'];
				}
			}
			elseif($ref == 0){
				$u = $model->register();
				if($model->loginuser((string)$email,$input->get("tokenId", null,'string'))){
					$user = $model->authenticate_email($email);
					if($user['success']==true)
					{
						$item->success = true;
						$item->user_id = $user['user_id'];
						$item->first_name = $user['first_name'];
						$item->last_name = $user['last_name'];
						$item->usertoken = $user['token'];
					}
					$refferal = (string)$model->randStrGen(20);
					$fname = (string)ucfirst($user['first_name']);
					$subject = $fname.', you just joined Ushbub';
					$body = <<<EOT
					<div style="max-width:800px;">
						<div style="height:50px;">
							<img src="http://ushbub.co.uk/assets/images/logo_ushbub.png?uref=$refferal" style="height:50px;border-raduis:25px;">
						</div>
						<div>
							<h1>Hey $fname, you're an Ushbubba now :)</h1>
							<p>Thank you for joining Ushbub. We hope you find value in our community.</p>
							<h2></h2>
EOT;
					if($regpoint == null):
					$body .= <<<EOT
							<h2>Do you own your own business?</h2>
							<p>Register your business on our <a href="http://ushbub.co.uk/shops?ref=$refferal">free listings section</a> so other Ushbubba's can find and support your business.</p>
							<p>You can update your preferences at any time, simply go to the preferences section on your profile page.</p>
						</div>
					</div>
EOT;
					endif;
					if($regpoint == 'sc'):
						$body .= <<<EOT
								<h2>Believe you have what it takes to be the best?</h2>
								<p>We think you could be a great competitor in our <a href="http://ushbub.co.uk/shops?ref=$refferal">Ushball predictor competition</a>. Be sure to check your scores daily to give you the best chance of getting maximum points.</p>
								<p>Don't worry about forgetting to check, we will send you a reminder e-mail. If you need to change your e-mail preferences, you can update your preferences at any time, simply go to the preferences section on your profile page.</p>
							</div>
						</div>
EOT;
					endif;
					$item->msg = $model->sendEmail($email, $fname, $subject, $body);
					
				}
				else{
					$item->msg = 'Sorry, the credentials are invalid, it looks like you already have an account.';
				}
			}
		}
		return $item;
	}
	public function saveaddress()
	{
		$token = $this->getInput()->getString('apptoken',null);
		$item = new \StdClass();
		$item->success = false;
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
			$usertoken = $this->getInput()->getString("usertoken", null);
			$ddc_userinfo_id = $this->getInput()->getInt("ddc_userinfo_id", 0);
			$user_id = $this->getInput()->getString("user_id", null);
			$company = $this->getInput()->getString("company", null);
			$title = $this->getInput()->getString("title", null);
			$first_name = $this->getInput()->getString("first_name", null);
			$middle_name = $this->getInput()->getString("middle_name", null);
			$last_name = $this->getInput()->getString("last_name", null);
			$address_type = $this->getInput()->getString("address_type", null);
			$address_type_name = $this->getInput()->getString("address_type_name", null);
			$address_1 = $this->getInput()->getString("address_1", null);
			$address_2 = $this->getInput()->getString("address_2", null);
			$city = $this->getInput()->getString("city", null);
			$county = $this->getInput()->getString("county", null);
			$zip = $this->getInput()->getString("zip", null);
			$country_id = $this->getInput()->getString("country_id", null);
			$phone_1 = $this->getInput()->getString("phone_1", null);
			$phone_2 = $this->getInput()->getString("phone_2", null);
			$customer_note = $this->getInput()->getString("customer_note", null);
			$table = '#__ddc_userinfos';
			if($ddc_userinfo_id!=0){
				$fields = array($this->getContainer()->get('db')->qn('company'). " = '". ($company)."'",$this->getContainer()->get('db')->qn('title'). " = '". $title ."'",$this->getContainer()->get('db')->qn('first_name'). " = '". $first_name ."'",$this->getContainer()->get('db')->qn('last_name'). " = '". $last_name ."'",$this->getContainer()->get('db')->qn('middle_name'). " = '". $middle_name ."'",$this->getContainer()->get('db')->qn('address_type'). " = '". $address_type ."'",$this->getContainer()->get('db')->qn('address_type_name'). " = '". $address_type_name ."'",$this->getContainer()->get('db')->qn('address_1'). " = '". $address_1 ."'",$this->getContainer()->get('db')->qn('address_2'). " = '". $address_2 ."'",$this->getContainer()->get('db')->qn('city'). " = '". $city ."'",$this->getContainer()->get('db')->qn('county'). " = '". $county ."'",$this->getContainer()->get('db')->qn('country_id'). " = '". $country_id ."'",$this->getContainer()->get('db')->qn('zip'). " = '". $zip ."'",$this->getContainer()->get('db')->qn('phone_1'). " = '". $phone_1 ."'",$this->getContainer()->get('db')->qn('phone_2'). " = '". $phone_2 ."'",$this->getContainer()->get('db')->qn('customer_note'). " = '". $customer_note ."'");
				$conditions = array($this->getContainer()->get('db')->qn('ddc_userinfo_id'). ' = '. $this->getContainer()->get('db')->q($ddc_userinfo_id) );
				$result = $model->update($table, $fields, $conditions);
				$item->success = true;
				$item->info = $result;
				return array("user"=>$item);
			}
			$columns = array("ddc_userinfo_id", "user_id", "company", "title", "first_name", "middle_name", "last_name", "address_type", "address_type_name", "address_1", "address_2", "city", "county", "zip", "country_id", "phone_1", "phone_2", "customer_note");
			$data = array($ddc_userinfo_id, $user_id, $company, $title, $first_name, $middle_name, $last_name, $address_type, $address_type_name, $address_1, $address_2, $city, $county, $zip, $country_id, $phone_1, $phone_2, $customer_note);
			if($result = $model->insert($table, $columns, $data))
			{
				$item->success = true;
				$item->info = $result;
			}
		}
		
		
		return array("user"=>$item);
	}
	
	public function getaddress()
	{
		$usertoken = $this->getInput()->getString("usertoken", null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$item = $model->getShipAddress($usertoken);
		
		return array("address"=>$item);
	}
	public function getstripecustomer()
	{
		$return = new \StdClass();
		$return->success = false;
		$usertoken = $this->getInput()->getString("usertoken", null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$customer = $model->getStripeCustomer($usertoken);
		if(count($customer)==1)
		{
			$return->cardinfo = json_decode($customer->profile_value);
			$return->success = true;
		}
		return array("payment"=>$return);
	}
	
	public function addstripecustomer()
	{
		$return = new \StdClass();
		$return->success = false;
		$usertoken = $this->getInput()->getString("usertoken", null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($model->isStripeCustomer($usertoken)){
			if($result = $model->updateStripeCustomer($usertoken))
			{
				$return->success = true;
			}
			return array("payment"=>$return);
		}
		else
		{
			//convert token to customer token and save
			if($result = $model->createStripeCustomer($usertoken))
			{
				$return->success = true;
			}
			return array("payment"=>$return);
		}
	}
}