<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;
use App\Models\App\Models;

class ProfilesController extends DefaultController
{
	
	public function index()
	{
		$id = $this->getInput()->getString('usertoken',null);
		$token = $this->getInput()->getString('apptoken',null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$items = array("profile"=>"not authorised");
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$items = array( "profile" => $model->getItemById($id));
		}
		return $items;
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
					$item->fullname = $user['fullname'];
					$item->usertoken = $user['token'];
				}
			}
		}
		return $item;
	}
	public function authenticate()
	{
		$token = $this->getInput()->getString('apptoken',null);
		$item = new \StdClass();
		$item->success = false;
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
			$email = $this->getInput()->getString("email", null);
			$usertoken = $this->getInput()->getString("usertoken", null);
			
			if($usertoken!=null){
				$user = $model->authenticate_token($usertoken);
				if($user['success']==false){
					$usertoken = null;
				}
			}
			if($usertoken==null){
				$user = $model->authenticate_email($email);
			}
			if($user['success']==true)
			{
				$item->success = true;
				$item->user_id = $user['user_id'];
				$item->usertoken = $user['token'];
			}else{
				$u = $model->register();
				$user = $model->authenticate_email($email);
				if($user['success']==true)
				{
					$item->success = true;
					$item->user_id = $user['user_id'];
					$item->usertoken = $user['token'];
				}
			}
		}
		return array("user"=>$item);
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