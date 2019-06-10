<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\UsergroupsModel;
use App\Models\EmailmessagesModel;
use App\Models\SportscompsModel;
use App\Models\ImagesModel;
use App\Models\VendorlocationsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;
use App\Models\App\Models;
use Joomla\Http\Http;

class ProfilesController extends DefaultController
{
	
	public function index()
	{
		$h = getallheaders();
		$usertoken = null;
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		$input = $this->getInput()->json;
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$imgModel = new ImagesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $model->authenticate_token($usertoken);
		$method = $this->getInput()->getMethod();
		if($method=='GET'){
			if($user['success']==true)
			{
				$user = $model->getItemById($user['user_id']);
				$user_image = $imgModel->getItemById(null,$user->id,'ddc+profile');
				$item->success = true;
				$item->user_id = $user->id;
				$item->first_name = $user->first_name;
				$item->last_name = $user->last_name;
				$item->username = $user->username;
				$item->email = $user->email;
				$item->aboutme = $model->getUserProfile($user->id,'aboutme');
				$item->company = $model->getUserProfile($user->id,'company');
				$item->profession = $model->getUserProfile($user->id,'profession');
				if($user_image->image_link){
					$item->image = $user_image->image_link;
				}
				$item->msg = '';
				$item->profiles = '';
				if($cw = $model->getCarwashLocation($user->id)){
					$cw->carwash_location = json_decode($cw->carwash_location);
					$item->carwash_options = $cw;
				}
				else{
					$item->carwash_options = array('carwash_location'=>'');
				}
			}
		}
		if($method=='POST'){
			$fname = $input->get('first_name',null,'string');
			$lname = $input->get('last_name',null,'string');
			$table = '#__ddc_users';
			$fields = array($this->getContainer()->get('db')->qn('first_name'). " = '". $fname."'",$this->getContainer()->get('db')->qn('last_name'). " = '". $lname ."'");
			$conditions = array($this->getContainer()->get('db')->qn('id'). ' = '. $this->getContainer()->get('db')->q($user['user_id']) );
			if($model->update($table, $fields, $conditions)){
				$item->success = true;
			}
			if($model->getUserProfile($user['user_id'],'aboutme')){
				$model->updateuserprofiles($user['user_id'],'aboutme',$input->get("aboutme", null,'string'));
			}else{
				$model->adduserprofiles($user['user_id'],'aboutme',$input->get("aboutme", null,'string'));
			}
			if($model->getUserProfile($user['user_id'],'company')){
				$model->updateuserprofiles($user['user_id'],'company',$input->get("company", null,'string'));
			}else{
				$model->adduserprofiles($user['user_id'],'company',$input->get("company", null,'string'));
			}
			if($model->getUserProfile($user['user_id'],'profession')){
				$model->updateuserprofiles($user['user_id'],'profession',$input->get("profession", null,'string'));
			}else{
				$model->adduserprofiles($user['user_id'],'profession',$input->get("profession", null,'string'));
			}
		}
		if($method=='DELETE'){

			return $item;
		}
		if($method=='UPDATE'){

			return $item;
		}
		return $item;
	}

	function images()
	{
		$token = $this->getInput()->getString('apptoken',null);
		$h = getallheaders();
		$usertoken = null;
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		if($token != "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			return $item;
		}
		$profModel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$model = new ImagesModel($this->getInput(), $this->getContainer()->get('db'));
		$method = $this->getInput()->getMethod();
		if($method=='GET'){

			return $method;
		}
		if($method=='POST'){
			if($user = $profModel->authenticate_token($usertoken)){
				$model->removeImage(null, $user['user_id'],'ddc_profile');
				if($model->addImage($user['user_id'],'ddc_profile',$user['user_id'])){
					$item->success = true;
				}
			}
			return $item;
		}
		if($method=='DELETE'){

			return $method;
		}
		if($method=='UPDATE'){

			return $method;
		}
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
							<img src="http://ushbub.co.uk/api/email/getstats?uref=$refferal" style="height:1px;float:right;">
							<img src="http://ushbub.co.uk/assets/images/logo_ushbub.png" style="height:50px;border-raduis:25px;">
						</div>
						<div>
							<h1>Hey $fname, you're an Ushbubba now :)</h1>
							<p>Thank you for joining Ushbub. We hope you find value in our community.</p>
							<h2></h2>
EOT;
					if($regpoint == '' || $regpoint == null):
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
								<p>We think you could be a great competitor in our <a href="http://ushbub.co.uk/world-cup?ref=$refferal">Ushball predictor competition</a>. Be sure to check your scores daily to give you the best chance of getting maximum points.</p>
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

	public function cwlocation()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$item = array();
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$vlmodel = new VendorlocationsModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = '';
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$user = $model->authenticate_token($usertoken);
		$vl = $vlmodel->getItemById($this->getInput()->json->get('id',null));
		$state = 9;
		//add a new usergroup_map
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			if(!$cw = $model->getCarwashLocation($user['user_id'])){
				$data = array(
					$user['user_id'],
					'carwash.location',
					json_encode($vl),
					$state
				);
				$columns = array("user_id", "profile_key", "profile_value", "ordering");
				$model->insert('#__ddc_user_profiles',$columns, $data);
			}
			else{
				$table = '#__ddc_user_profiles';
				$fields = array($this->getContainer()->get('db')->qn('profile_value'). " = '". json_encode($vl)."'");
				$conditions = array($this->getContainer()->get('db')->qn('user_id'). ' = '. $this->getContainer()->get('db')->q($user['user_id']),$this->getContainer()->get('db')->qn('profile_key'). ' = '. $this->getContainer()->get('db')->q('carwash.location') );
				$model->update($table, $fields, $conditions);
			}
			$item = $model->getCarwashLocation($user['user_id']);
			$item->carwash_location = json_decode($item->carwash_location);
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$items->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$id = $this->getInput()->getString('id',null);
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
			else{
				$item->msg = "Login failed";
			}
		}
		return $item;
	}

	public function participants(){
		$token = $this->getInput()->json->get('apptoken',null);
		$items = array();
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$ugmodel = new UsergroupsModel($this->getInput(), $this->getContainer()->get('db'));
		$sc_model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
		$id = $this->getInput()->getString("id", null);
		$h = getallheaders();
		$usertoken = null;
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$user = $model->authenticate_token($usertoken);
		if($user['success'] == false){
			return $items;
		}
		if($input = $this->getInput()->getMethod()==='POST'){
			if(!$token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t"){
			
			}
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//update a participant

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get a participant or many
			$items = $ugmodel->getParticipants($id);
			for($i=0;$i<count($items);$i++){
				$items[$i]->points1 = intval($items[$i]->points1);
				$items[$i]->points = intval($items[$i]->points);
				$items[$i]->t_points = $items[$i]->points+$items[$i]->points1;
			}
			usort($items, function($a, $b)
			{
				return -1*strcmp($a->t_points, $b->t_points);
			});
			$position = 1;
			for($i=0;$i<count($items);$i++){
				if($i==0){
					$items[0]->position = $position;
				}else{
					if($items[$i]->t_points == $items[$i-1]->t_points){
						$items[$i]->position = $position;
					}
					else{
						$position = $i+1;
						$items[$i]->position = $position;
					}
				}
			}
		}
		return $items;
	}

	public function reset()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$email = $this->getInput()->json->get('email',null,'string');
		$reset_token = $this->getInput()->json->get('reset_token',null,'string');
		$item = array('success' => false);
		$date = date('Y-m-d H:i:s');
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//check if reset token exists
			if($reset_token == null){
				//check if e-mail exists
				$user = $model->validate_user_email($email);
				if($user!=null){
					//Create a token and store it for a password reset
					$reset_token = $model->randStrGen(30);
					$user_id = $user->id;
				}
				else{
					$item['msg'] = 'Check your e-mail address is spelt correctly. It is not on our records. Otherwise, create a new account.';
					return $item;
				}
				$data = array(
					$user_id,
					$reset_token,
					$date,
					$user_id
				);
				$columns = array("user_id", "token", "created_on", "created_by");
				if($model->insert('#__ddc_access_tokens',$columns, $data)){
					$item['success'] = true;
					//send reset e-mail
					$ref_token = $model->randStrGen(25);
					$emodel = new EmailmessagesModel($this->getInput(), $this->getContainer()->get('db'));
					$body = $emodel->getItemById(4);
					$names = array("{{name}}","{{token}}","{{ref_token}}");
					$values = array($user->first_name,$reset_token,$ref_token);
					$b = $model->emailTemplate($names, $values, $body->message);
					$model->sendEmail($email, $user->first_name, $body->subject, $b);
					//log e-mail
					$emodel->email_logger(4,$ref_token, $user->id, null);
				}
			}
			else{
				//validate the token and save the new password
				if($check = $model->validate_access_token($reset_token)){
					$user = $model->getUser(null,null,null,$reset_token);
					//update the user password
					$model->update_password($user->id,$this->getInput()->json->get('password',null,'string'));
					if($model->loginuser($user->email,$this->getInput()->json->get('password',null,'string')))
					{
						$user = $model->authenticate_email($user->email);
						if($user['success']==true)
						{
							$item['success'] = true;
							$item['user_id'] = $user['user_id'];
							$item['first_name'] = $user['first_name'];
							$item['last_name'] = $user['last_name'];
							$item['usertoken'] = $user['token'];
						}
					}
				}
				else{
					$item['msg'] = 'Sorry, the token is invalid. You need to request a new password reset.';
				}
			}
			return $item;
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$items->method = 'PUT';
			return $items;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//check if reset token exists
			if($reset_token!=null){
				if($model->validate_access_token($reset_token)){
					$item['state'] = 4;
					$item['success'] = true;
				}
				else{
					$item['msg'] = 'Sorry, the token is invalid. You need to request a new password reset.';
				}
			}
			return $item;
		}
	}

	public function usergroup()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$item = array();
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$ugmodel = new UsergroupsModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = $this->getInput()->json->get("usertoken", null,'string');
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$config = json_decode($this->container->get('config'));
		$sc = $config->ushbub->sports_comp;
		$sc_default = $config->ushbub->sports_comp_default;
		$user = $model->authenticate_token($usertoken);
		$ugm = $ugmodel->getItemById($user['user_id'],null,$sc);
		$state = 1;
		//add a new usergroup_map
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			if($ugm){
				$item->msg = 'user group already created';
				return $item;
			}
			$data = array(
				$user['user_id'],
				$sc_default,
				null,
				$state
			);
			$columns = array("user_id", "group_id", "token", "state");
			$item = $model->insert('#__ddc_user_usergroup_map',$columns, $data);
			$items= $ugmodel->listItems($user['user_id'],null,$sc);
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$items->method = 'PUT';
			return $items;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$id = $this->getInput()->getString('id',null);
			$items = $ugmodel->listItems($user['user_id'],null,$sc);
		}
		
		return $items;
	}

	public function saveaddress()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$input = $this->getInput()->json;
		$item = new \StdClass();
		$item->success = false;
		if($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")
		{
			$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
			$usertoken = $input->get("usertoken", null);
			$ddc_userinfo_id = $input->get("ddc_userinfo_id", null);
			$user_id = $input->get("user_id", 0);
			$company = $input->get("company", 0);
			$title = $input->get("title", null);
			$first_name = $input->get("first_name", null,'string');
			$last_name = $input->get("last_name", null,'string');
			$email = $input->get("email", null,'string');
			$address_type = $input->get("address_type", 'sh');
			$address_type_name = $input->get("address_type_name", 0);
			$address_1 = $input->get("address_1", null,'string');
			$address_2 = $input->get("address_2", null,'string');
			$city = $input->get("city", null,'string');
			$county = $input->get("county", null,'string');
			$zip = $input->get("zip", null,'string');
			$country_id = $input->get("country_id", null,'string');
			$phone_1 = $input->get("phone_1", null,'string');
			$phone_2 = $input->get("phone_2", null,'string');
			$customer_note = $input->get("customer_note", null,'string');
			$table = '#__ddc_userinfos';
			if($ddc_userinfo_id!=0){
				$fields = array($this->getContainer()->get('db')->qn('company'). " = '". ($company)."'",$this->getContainer()->get('db')->qn('title'). " = '". $title ."'",$this->getContainer()->get('db')->qn('first_name'). " = '". $first_name ."'",$this->getContainer()->get('db')->qn('last_name'). " = '". $last_name ."'",$this->getContainer()->get('db')->qn('email'). " = '". $email ."'",$this->getContainer()->get('db')->qn('address_type'). " = '". $address_type ."'",$this->getContainer()->get('db')->qn('address_type_name'). " = '". $address_type_name ."'",$this->getContainer()->get('db')->qn('address_1'). " = '". $address_1 ."'",$this->getContainer()->get('db')->qn('address_2'). " = '". $address_2 ."'",$this->getContainer()->get('db')->qn('city'). " = '". $city ."'",$this->getContainer()->get('db')->qn('county'). " = '". $county ."'",$this->getContainer()->get('db')->qn('country_id'). " = '". $country_id ."'",$this->getContainer()->get('db')->qn('zip'). " = '". $zip ."'",$this->getContainer()->get('db')->qn('phone_1'). " = '". $phone_1 ."'",$this->getContainer()->get('db')->qn('phone_2'). " = '". $phone_2 ."'",$this->getContainer()->get('db')->qn('customer_note'). " = '". $customer_note ."'");
				$conditions = array($this->getContainer()->get('db')->qn('ddc_userinfo_id'). ' = '. $this->getContainer()->get('db')->q($ddc_userinfo_id) );
				$result = $model->update($table, $fields, $conditions);
				$item->success = true;
				$item->info = $result;
				return $item;
			}
			$columns = array("ddc_userinfo_id", "user_id", "company", "title", "first_name", "last_name", "email", "address_type", "address_type_name", "address_1", "address_2", "city", "county", "zip", "country_id", "phone_1", "phone_2", "customer_note");
			$data = array($ddc_userinfo_id, $user_id, $company, $title, $first_name, $last_name, $email, $address_type, $address_type_name, $address_1, $address_2, $city, $county, $zip, $country_id, $phone_1, $phone_2, $customer_note);
			if($result = $model->insert($table, $columns, $data))
			{
				$item->success = true;
				$item->info = $result;
			}
		}
		
		return $item;
	}
	
	public function getaddress()
	{
		$usertoken = $this->getInput()->getString("usertoken", null);
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$item = $model->getShipAddress($usertoken);
		
		return array("address"=>$item);
	}

	public function stripecustomer()
	{
		$token = $this->getInput()->json->get('apptoken',null);
		$return = new \StdClass();
		$return->success = false;
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = '';
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		if($usertoken==''){
			return $return;
		}
		$config = json_decode($this->container->get('config'));
		if($config->stripe->status == 'live'){
			$secret_key = $config->stripe->secret_key_live;
		}else{
			$secret_key = $config->stripe->secret_key_test;
		}
		$user = $model->authenticate_token($usertoken);
		$stripeToken = $this->getInput()->json->get('token',null,'string');
		//add a new usergroup_map
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			if($model->isStripeCustomer($user['user_id'])){
				$result = $model->updateStripeCustomer($stripeToken,$user['user_id'],$secret_key);
			}
			else
			{
				//convert token to customer token and save
				$result = $model->createStripeCustomer($stripeToken,$user['user_id'],$secret_key);
			}
			$return->cardinfo = '';
			$result = $model->getstripecustomer($user['user_id']);
			$return->success = true;
			if($result!=null){
				$return->cardinfo = json_decode($result->profile_value);
			}
			return $return;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			$return->cardinfo = '';
			$result = $model->getstripecustomer($user['user_id']);
			$return->success = true;
			if($result!=null){
				$return->cardinfo = json_decode($result->profile_value);
			}
		}
		return $return;
	}	

	public function email(){
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$emodel = new EmailmessagesModel($this->getInput(), $this->getContainer()->get('db'));
		$result = array();
		$names = array("{{name}}");
		$values = array(
			array('Darryl','usher_darryl@hotmail.com')
		);
		$body = $emodel->getItemById(3);
		for($i=0;$i<count($values);$i++){
			$b = $model->emailTemplate($names, $values[$i], $body->message);
			$res = $model->sendEmail($values[$i][1], $values[$i][0], $body->subject, $b);
		}
		
		return $res;
	}
}