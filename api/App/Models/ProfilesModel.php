<?php
namespace App\Models;
use App\Models\DefaultModel;
use Joomla\Application;
use Joomla\Github\Package\Authorization;
use Stripe\Charge;
use Stripe\Order;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Error\Api;
use Joomla\Crypt\Crypt;
use Joomla\Crypt\Password\Simple;

class ProfilesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_token = 'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t';
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('u.id, u.username, u.first_name, u.last_name, u.email, u.password')
			  ->from($this->db->quoteName('#__ddc_users', 'u'))
			->leftJoin('#__ddc_access_tokens as at on at.user_id = u.id')
  			->group('u.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val, $token = null)
	{
		if((int)$val > 0)
		{
			$query->where('u.id = '.(int)$val);
		}
		if($token != null)
		{
			$query->where('at.token = "'.$token.'"');
		}
		if($this->input->get('myId', null)!=null)
		{
			$query->where('u.id = '.(int)$this->input->json->get('myId', null));
		}
		//$query->where('u.block = "0"');

		return $query;
	}

	public function getCarwashLocation($user_id)
	{
		//get customer stripe account number

		$query = $this->db->getQuery(TRUE)
			->select('up.profile_value as carwash_location')
			->from('#__ddc_user_profiles as up')
			->where('(up.profile_key = "carwash.location")')
			->where('(up.user_id = "'.$user_id.'")');
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		return $result;
	
	}

	public function getUserProfile($id,$key){
		$query = $this->db->getQuery(TRUE)
			->select('up.profile_value as value')
			->from('#__ddc_user_profiles as up')
			->where('(up.profile_key = "'.$key.'")')
			->where('(up.user_id = "'.$id.'")');
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		return $result;
	}

	public function getUser($id = null, $email = null, $username = null, $token = null){
		
		$user = false;
		if($id!=null)
		{
			$user = $this->getItemById($id);
		}
		if(($email!=null) && ($user == false))
		{
			$user = $this->validate_user_email($email);
		}
		if(($username!=null) && ($user == false))
		{
			$user = $this->getItemByAlias($username);
		}
		if(($token!=null) && ($user == false))
		{
			$user = $this->getItemByAlias(null,$token);
		}
		return $user;
	}
	
	public function register(){

		if(($this->input->getMethod()=='POST') && ($this->_token == $this->input->get("apptoken", null,'string')))
		{
			$fname = $this->input->json->get("fname", null,'string');
			$lname = $this->input->json->get("lname", null,'string');
			$email = $this->input->json->get("email", null,'string');
			$tokenID = $this->input->json->get("tokenId", null,'string');
			$date = date("Y-m-d H:i:s");
			$options = [
					'cost' => 8,
			];
			$data = array(
					$fname,
					$lname,
					$email,
					password_hash($tokenID, PASSWORD_BCRYPT,$options),
					$date,
					json_encode(array('authentication'=>base64_encode(password_hash($tokenID, PASSWORD_BCRYPT,$options))))
			);
			if($this->getUser(null,$email,null) != false)
			{
				$obj = array("success" => false, "msg" => "user already exists");
			}
			else {
				//Save user
				$columns = array("first_name", "last_name", "email", "password", "registerDate", "params" );
				$result = $this->insert("#__ddc_users", $columns, $data);
				$obj = $result;
			}
		}else 
		{
			$obj = array("success" => false, "msg" => "request did not authenticate");
		}
		return $obj;
	}
	public function authenticate_email($user_email)
	{
		$result = array("success" => false);
		if($user = $this->validate_user_email($user_email)){
			$columns = array("user_id","token", "series", "invalid", "time", "uastring" );
			$token = $this->randStrGen(25);
			$data = array($user->id,$token,$this->randStrGen(20),0,strtotime(Date('Y-m-d H:i:s'))+(3600*24),"ddcshopbox" );
			$this->insert("#__ddc_user_keys", $columns, $data);
			$result['success'] = true;
			$result['token'] = $token;
			$result['user_id'] = $user->id;
			$result['first_name'] = $user->first_name;
			$result['last_name'] = $user->last_name;
		}
		return $result;
	}
	public function authenticate_token($token)
	{
		$result = array("success" => false);
		
		$query = $this->db->getQuery(true)
		->select('u.id, uk.token, u.first_name, u.last_name, u.email')
		->from('#__ddc_users as u')
		->leftjoin('#__ddc_user_keys as uk on u.id = uk.user_id')
		->where('uk.token = ' . $this->db->quote($token));
		$this->db->setQuery($query);
		$response = $this->db->loadObject();
		if($response!=null){
			$result['success'] = true;
			$result['token'] = $response->token;
			$result['user_id'] = $response->id;
			$result['first_name'] = $response->first_name;
			$result['last_name'] = $response->last_name;
			$result['email'] = $response->email;
		}

		return $result;
	}
	
	public function loginuser($email,$password)
	{
		if($user = $this->getUser(null,$email,null)){
			if(password_verify($password,$user->password)){
				return true;
			}
		}
		return false;
	}

	public function update_password($user_id,$password)
	{
		$date = date("Y-m-d H:i:s");
		$options = [
				'cost' => 8,
		];
		$fields = array($this->db->qn('password'). " = ". $this->db->q(password_hash($password, PASSWORD_BCRYPT,$options)),$this->db->qn('modified_on'). " = ".$this->db->q($date),$this->db->qn('modified_by'). " = ".$this->db->q($user_id));
		$conditions = array($this->db->qn('id'). ' = '. $this->db->q($user_id) );
		$table = '#__ddc_users';
		$result = $this->update($table, $fields, $conditions);
		return $result;
	}

	public function updateuserprofiles($id,$key,$value){
		$fields = array($this->db->qn('profile_value').' = "'.$value.'"');
		$conditions = array($this->db->qn('user_id') .'='. $id,
						$this->db->qn('profile_key').' = "'.$key.'"'
					);
		$this->addLog(json_encode($fields));
		$result = $this->update('#__ddc_user_profiles',$fields,$conditions);
		return $result;
	}

	public function adduserprofiles($id,$key,$value,$order = 0){
		$values = array($id,$key,$value,$order);
		$columns = array("user_id", "profile_key", "profile_value",'ordering');
		$result = $this->insert("#__ddc_user_profiles", $columns, $values);
		return $result;
	}

	public function getShipAddress($token)
	{
		$result = array("success" => false);
		
		$query = $this->db->getQuery(true)
		->select('u.id, u.first_name, u.username, u.email, ui.*')
		->from('#__ddc_users as u')
		->leftjoin('#__ddc_userinfos as ui on ui.user_id = u.id')
		->leftjoin('#__ddc_user_keys as uk on (uk.user_id = u.id)')
		->where('uk.token = ' . $this->db->quote($token));
		$this->db->setQuery($query);
		$response = $this->db->loadObject();
		if($response!=null){
			$result['success'] = true;
			$result['info'] = $response;
		}
		return $result;
	}
	
	public function getStripeCustomer($user_id)
	{
		//get customer stripe account number

		$query = $this->db->getQuery(TRUE)
			->select('up.profile_value')
			->from('#__ddc_user_profiles as up')
			->where('(up.profile_key = "stripe.customer") AND (up.user_id = ' . $this->db->quote($user_id).')');
		$this->db->setQuery($query);
		$dbCustomer = $this->db->loadObject();
		return $dbCustomer;
	
	}
	
	public function isStripeCustomer($user_id)
	{
		//check if customer exists
		$query = $this->db->getQuery(TRUE);
		$query->select('up.profile_value')
			->from('#__ddc_user_profiles as up')
			->where('(up.profile_key = "stripe.customer") AND (up.user_id = ' . $this->db->quote($user_id).')');
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if($result==null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function createStripeCustomer($token,$user_id, $secretKey)
	{
		$return = array("success"=>false);
		//check if customer exists
		if(!$this->isStripeCustomer($user_id))
		{
			$user = $this->getUser($user_id);
			$stripe = new Stripe();
			$stripe->setApiKey($secretKey);
			$sCustomer = new Customer();
			try
			{
				$response = $sCustomer->create(array(
						"description" => "Customer for ".$user->email,
						"email" => $user->email,
						"source" => $token
				));
			}
			catch(Exception $e)
			{
				$return['msg'] = $e->getMessage();
			}
			if(isset($response->id))
			{
				//get customer variable back and save to db
				$data = array('stripeCustomerToken' => $response->id,
						'stripeCustomerBrand' => $response->sources->data[0]->brand,
						'stripeCustomerExp_month' => $response->sources->data[0]->exp_month,
						'stripeCustomerExp_year' => $response->sources->data[0]->exp_year,
						'stripeCustomerlast4' => $response->sources->data[0]->last4
				);
				//add customer value to #__user_profiles table
				// Create a new query object.
				$query = $this->db->getQuery(true);
				// Insert columns.
				$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
				// Insert values.
				$values = array($user_id, $this->db->quote('stripe.customer'), $this->db->quote(json_encode($data)), 1);
				// Prepare the insert query.
				$query
				->insert($this->db->quoteName('#__ddc_user_profiles'))
				->columns($this->db->quoteName($columns))
				->values(implode(',', $values));
				// Set the query using our newly populated query object and execute it.
				$this->db->setQuery($query);
				$return['result'] = $this->db->execute();
			}
			else
			{
				$return['result'] = false;
			}
			return $return;
		}
		else
		{
			return false;
		}
	}
	
	public function updateStripeCustomer($token,$user_id,$secretKey)
	{
		$dbCustomer = $this->getStripeCustomer($user_id);
		$stripeCustomerToken = json_decode($dbCustomer->profile_value);
		$stripeCustomerToken = $stripeCustomerToken->stripeCustomerToken;
		//load Stripe model
		$stripe = new Stripe();
		//get secret #shh don't tell
		$stripe->setApiKey($secretKey);
		//Initialise the Stripe customer class
		$customer = new Customer();
		try
		{
			$response = $customer->retrieve($stripeCustomerToken);
			if($response->delete==false){
				$response->delete();
			}
		}
		catch(Exception $e){
			$return['msg'] = $e->getMessage();
		}
		// Create a new query object.
		$query = $this->db->getQuery(true);
		// delete all custom keys for the user.
		$conditions = array(
				$this->db->quoteName('user_id') . ' = '.(int)$user_id,
				$this->db->quoteName('profile_key') . ' = ' . $this->db->quote('stripe.customer')
		);
		$query->delete($this->db->quoteName('#__ddc_user_profiles'));
		$query->where($conditions);
		// Set the query using our newly populated query object and execute it.
		$this->db->setQuery($query);
		$result = $this->db->execute();
	
		$result = $this->createStripeCustomer($token,$user_id,$secretKey);
		return $result;
	}
	
	public function chargeStripeCustomer($token,$user_id,$secretKey)
	{
		//Get the shopping cart details
		$shopcart = new DdcshopboxModelsShopcart();
		$paymentDetails = $shopcart->listItems();
		$id = strtoupper('ddcshopbox').$paymentDetails[0]->ddc_shoppingcart_header_id;
		//$descriptions = array();
		$quantities = array();
		$costs = array();
		$shipping = $paymentDetails[0]->shipping_cost;
		$shipping_discount = $paymentDetails[0]->coupon_value;
		for($i=0;$i<count($paymentDetails);$i++)
		{
			//array_push($descriptions, $paymentDetails[$i]->vendor_product_name);
			array_push($quantities, $paymentDetails[$i]->product_quantity);
			array_push($costs,$paymentDetails[$i]->product_price);
		}
		$subtotal = 0;
		$currency = $this->_params->get('ddc_currency');
  		$url = JRoute::_('index.php?option=com_ddcshopbox$view=shopcart');
		for($i=0;$i<count($paymentDetails);$i++)
	  	{
	  	$subtotal = $subtotal+(($costs[$i]*$quantities[$i]));
	  	}
	
	  	$response = null;
	  	//load Stripe model
	  	$stripe = new Stripe();
	  	$ddcstripe = new DdcshopboxModelsDdcstripe();
	  	//get secret #shh don't tell
	  	$pm = $ddcstripe->getItem();
	  	if($this->getpartjsonfield($pm->payment_params,'paymentmethod_mode')=='live')
	  	{
		  	$api_secret = 'api_secret';
		}
		  	else
		  		{
		  		$api_secret = 'test_api_secret';
		}
		$apiKey = $this->getpartjsonfield($pm->payment_params, $api_secret);;
		$stripe->setApiKey($apiKey);
		//get the customer
		$dbCustomer = $this->getStripeCustomer();
		$stripeCustomerToken = json_decode($dbCustomer->profile_value);
		$stripeCustomerToken = $stripeCustomerToken->stripeCustomerToken;
		//create the payment charge
		$sCharge = new Charge();
		$response = $sCharge->create(array(
		"amount" => ($subtotal+$shipping-$shipping_discount)*100,
		"currency" => 'gbp',
		"description" => "Ushbub Shopping cart #".$paymentDetails[0]->ddc_shoppingcart_header_id,
	    			"customer" => $stripeCustomerToken
		    			));
		
	    	if(isset($response->id))
		    	{
		    	//get customer variable back and save to db
		    	$stripeChargeToken = $response->id;
		    	$date = date("Y-m-d H:i:s");
			//add customer value to #__user_profiles table
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);
			// Insert columns.
			$columns = array('ref','ref_id', 'token', 'state', 'created', 'modified', 'created_by', 'modified_by');
			// Insert values.
			$values = array($db->quote('ddcshopbox'),$paymentDetails[0]->ddc_shoppingcart_header_id,$db->quote($stripeChargeToken),2,$db->quote($date),$db->quote($date),0,0);
			// Prepare the insert query.
	    		$query
		    		->insert($db->quoteName('#__ddc_payments'))
		  				->columns($db->quoteName($columns))
		  				->values(implode(',', $values));
		    		// Set the query using our newly populated query object and execute it.
		    				$db->setQuery($query);
		    				$result = $db->execute();
		
		    				return true;
		}
	}

	public function emailTemplate($names = array(), $values=array(), $body = ''){
		$body = str_replace($names, $values, $body);
		return $body;
	}
}