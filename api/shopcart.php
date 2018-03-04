<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 


class DdcshopboxModelsShopcart extends DdcshopboxModelsDefault
{
 
    //Define class level variables
  	var $_user_id     				= null;
  	var $_product_id  				= null;
  	var $_vendor_id  				= null;
  	var $_cat_id	  				= null;
  	var $_published   				= 2;
  	var $_session					= null;
  	var $_shoppingcart_header_id 	= null;
  	var $_params					= null;
  	var $_token						= null;
  	var $_app						= null;
  	var $_data						= null;

  function __construct()
  {


    
    $this->_app = JFactory::getApplication();
    
    //If no User ID is set to current logged in user
    $this->_user_id = $this->_app->input->get('profile_id', JFactory::getUser()->id);
    $this->_product_id = $this->_app->input->get('product_id', null);
    $this->_vendor_id = $this->_app->input->get('vendor_id', null);
    $this->_session = JFactory::getSession();
    $this->_shoppingcart_header_id = $this->_session->get('shoppingcart_header_id',$this->_app->input->get('shoppingcart_header_id',null));
    $this->_token = $this->_app->input->get('carttoken',null);
    //if($this->_token!=null)
    //{
    //	$this->_session->set('shoppingcart_header_id',$this->_app->input->get('shoppingcart_header_id',null));
    //}	
    $this->_params = JComponentHelper::getParams('com_ddcshopbox');
    if($this->_app->input->get('shopcart_state', null)!= null)
    {
    	$this->_published = $this->_app->input->get('shopcart_state', 3);
    }
    $this->_data = $this->_app->input->get('jform', array(),'array');

    parent::__construct();       
  }
 

  protected function _buildQuery()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);

    $query->select('vp.*');
    $query->select('pp.*');
    $query->select('vc.*');
    $query->select('coup.*');
    $query->select('i.*');
    $query->select('sch.*');
    $query->select('sch.state as header_state');
    $query->select('scd.*');
    $query->select('v.title,v.images,v.address1,v.address2,v.city as v_city,v.county as v_county,v.post_code as v_post_code');
    $query->from('#__ddc_shoppingcart_headers as sch');
    $query->leftJoin('#__ddc_shoppingcart_details as scd on (scd.shoppingcart_header_id = sch.ddc_shoppingcart_header_id)');
   	$query->leftJoin('#__ddc_vendor_products as vp on (vp.ddc_vendor_product_id = scd.product_id)');
    $query->leftJoin('#__ddc_images as i on (vp.ddc_vendor_product_id = i.link_id) AND (i.linked_table = "ddc_products")');
    $query->leftJoin('#__ddc_vendors as v on vp.vendor_id = v.ddc_vendor_id');
    $query->leftJoin('#__ddc_product_prices as pp on vp.ddc_vendor_product_id = pp.product_id');
    $query->rightJoin('#__ddc_currencies as vc on vc.ddc_currency_id = pp.product_currency');
    $query->leftJoin('#__ddc_coupons as coup on coup.ddc_coupon_id = sch.coupon_id');
    $query->group("scd.product_id, sch.ddc_shoppingcart_header_id");
    
    return $query;
  }

  protected function _buildWhere(&$query)
  {
  	if($this->_shoppingcart_header_id!=null)
  	{
  		$query->where('sch.ddc_shoppingcart_header_id = "'. (int)$this->_shoppingcart_header_id .'"');
  	}
  	if($this->_token!=null)
  	{
  		$query->where('sch.session_id = "'.$this->_token.'"');
  	}
  	if($this->_user_id != 0)
  	{
  		$query->where('sch.user_id = "'. (int)$this->_user_id .'"');
  	}
  	else 
  	{
  		$query->where('sch.session_id = "'.$this->_session->getId().'"');
  	}
  	if($this->_product_id!=null)
  	{
  		$query->where('scd.product_id = "'. (int)$this->_product_id .'"');
  	}
  	if($this->_published!=null)
  	{
  		$query->where('sch.state between  0 AND "'.(int)$this->_published.'"');
  	}
  	
    return $query;
  }

  
  public function storeCartData($formdata = null)
  {

  	//Get form data
  	$formdata = $formdata ? $formdata : JRequest::getVar('jform', array(), 'post', 'array');
  	//Is there a shopping cart setup?
  	$sc = $this->listItems();
	
  	if(count($sc) == 0)
  	{
  		if($this->_session->get('ddclocation',null)==null)
  		{
  			return array(false,1,JText::_('COM_DDC_POSTCODE_NOT_SET'),null);
  		}
  		if($this->isValidPostCodeFormat($this->_session->get('ddclocation',null)))
  		{
  			$stldistance = $this->getPostcodesDistance($this->_session->get('ddclocation',null), $formdata['shop_post_code'])/1000;
  			if(number_format($stldistance,3) > number_format($this->_params->get('distance_limit'),3))
  			{
  				return array(false,2,JText::_('COM_DDC_LOCATED_TOO_FAR'),number_format($stldistance,3),$this->_params->get('distance_limit'));
  			}
  		}
  		else 
  		{
  			return array(false,1,JText::_('COM_DDC_PLEASE_ENTER_VALID_POSTCODE'),null);
  		}
  		//Setup cart if does not exist
  		$data = array(
  					'user_id' => $this->_user_id,
  					'state' => 1,
  					'catid' => null,
  					'session_id' => $this->_session->getId(),
  					'table' => 'shoppingcartheaders');
  		$row = $this->store($data);
  		$this->_session->set('shoppingcart_header_id',$row->ddc_shoppingcart_header_id);
  	}
  	elseif(count($sc) > 0)
  	{
  		//Cart if does exist
  		//User clicks Continue
  		if($formdata['table']=='ddcCheckout')
  		{
  			if($formdata['state'] == 2)
  			{
  				$data = array(
  						'ddc_shoppingcart_header_id' => $sc[0]->ddc_shoppingcart_header_id,
  						'user_id' => $this->_user_id,
  						'state' => $formdata['state'],
  						'catid' => null,
  						'delivery_type' => $formdata['shipping_method'],
  						'delivery_date' => $formdata['delivery_date'],
  						'delivery_time' => $formdata['delivery_time'],
  						'shipping_cost' => $formdata['delivery_price'],
  						'session_id' => $this->_session->getId(),
  						'table' => 'shoppingcartheaders');
  			}
  			if($formdata['state'] == 3)
  			{
  				if(($formdata['address_line_1']==null) || ($formdata['town']==null) || ($formdata['post_code']==null) || ($formdata['email_to']==null) || ($formdata['mobile_no']==null) || ($formdata['first_name']==null) || ($formdata['last_name']==null))
  				{
  					return false;
  				}
  				
  				$data = array(
  						'ddc_shoppingcart_header_id' => $sc[0]->ddc_shoppingcart_header_id,
  						'user_id' => $this->_user_id,
  						'catid' => null,
  						'first_name' => $formdata['first_name'],
  						'last_name' => $formdata['last_name'],
  						'address_line_1' => $formdata['address_line_1'],
  						'address_line_2' => $formdata['address_line_2'],
  						'shipping_cost' => $formdata['delivery_price'],
  						'town' => $formdata['town'],
  						'county' => $formdata['county'],
  						'post_code' => $formdata['post_code'],
  						'mobile_no' => $formdata['mobile_no'],
  						'telephone_no' => $formdata['telephone_no'],
  						'email_to' => $formdata['email_to'],
  						'comment' => $formdata['comment'],
  						'payment_method'=> $formdata['payment_method'],
  						'session_id' => $this->_session->getId(),
  						'table' => 'shoppingcartheaders');
  			}			
  		}
  		
  		else 
  		{
  			//user is still shopping
  			$data = array(
  					'ddc_shoppingcart_header_id' => $sc[0]->ddc_shoppingcart_header_id,
  					'user_id' => $this->_user_id,
  					'state' => 1,
  					'catid' => null,
  					'session_id' => $this->_session->getId(),
  					'table' => 'shoppingcartheaders');
  		}
  		$row = $this->store($data);
  		$this->_session->set('shoppingcart_header_id',$row->ddc_shoppingcart_header_id);
  		
  		if(isset($formdata['payment_method']) && ($formdata['payment_method'] == 1))
  		{
	  		$paypal = new DdcshopboxModelsDdcpaypal();
	  		$paypallogo = $this->_params->get('payment_logo');
	  		$sc = $this->listItems();
	  		if($sc[0]->header_state==3)
	  		{
  				if($this->_app->input->get('paypalsuccess',null)==="false")
  				{
  					$data = array(
  							'ddc_shoppingcart_header_id' => $sc[0]->ddc_shoppingcart_header_id,
  							'user_id' => $this->_user_id,
  							'state' => 2,
  							'catid' => null);
  					$row = $this->store($data);
  					echo JText::_('COM_DDC_PAYMENT_CANCELLED')."<br>";
  					echo '<a href="'.JUri::root().'index.php?option=com_ddcshopcart&view=shopcart">
					<img src="'.$paypallogo.'" style="height:80px;" /></a>';
  				}
  				elseif($this->_app->input->get('paypalsuccess',null)==="true")
  				{
  					if($sc[0]->header_state==3)
  					{
  						$paypal->makePaypalPayment();
  					}
  				}
  				elseif($this->_app->input->get('paypalsuccess',null)===null)
  				{
  					if($sc[0]->header_state==3){
  						$paypal->createPaypalPayment();
  					}
  				}
	  		}
  		}
	  	elseif(isset($formdata['payment_method']) && ($formdata['payment_method']==2))
	  	{
	  		$ddcstripe = new DdcshopboxModelsDdcstripe();
	  		$ddcprofile = new DdcshopboxModelsProfiles();
	  		$user_id = 0;
	  		//check if logged in
	  		if(JFactory::getUser()->id!=0)
	  		{
	  			//user logged in
	  			$user_id = JFactory::getUser()->id;
	  			//check if user is setup as stripe customer
	  			if($ddcstripe->isStripeCustomer())
	  			{
	  				if($formdata['stripeCusToken']=='false')
	  				{
	  					$ddcstripe->updateStripeCustomer();
	  				}
	  				
	  				$dbCustomer = $ddcstripe->getStripeCustomer();
	  				$stripeCustomerToken = json_decode($dbCustomer->profile_value);
	  					
	  				//save payment for later
	  				$result = $this->storePaymentForLater($stripeCustomerToken->stripeCustomerToken);
	  				if($result == true)
	  				{
	  					//update shopping cart as complete
	  					$this->updateShopcartAsComplete();
	  				}
	  				
// 	  				//create stripe payment by charging customer
// 	  				$result = $ddcstripe->chargeStripeCustomer();
// 	  				if($result)
// 	  				{
// 	  					//update shopping cart as paid
// 	  					$this->updateShopcartAsPaid();
// 	  				}
	  			}
	  			else 
	  			{
	  				//convert token to customer token and save
	  				$return = $ddcstripe->createStripeCustomer();
	  				
	  				$dbCustomer = $ddcstripe->getStripeCustomer();
	  				$stripeCustomerToken = json_decode($dbCustomer->profile_value);
	  				
	  				//save payment for later
	  				$result = $this->storePaymentForLater($stripeCustomerToken->stripeCustomerToken);
	  				if($result == true)
	  				{
	  					//update shopping cart as complete
	  					$this->updateShopcartAsComplete();
	  				}
	  				
// 	  				//create stripe payment by charging customer
// 	  				try
// 	  				{
// 	  					$result = $ddcstripe->chargeStripeCustomer();
// 	  				}
// 	  				catch(Exception $e)
// 	  				{
// 	  					echo $e->getMessage();
// 	  				}
// 	  				if($result == true)
// 	  				{
// 	  					//update shopping cart as paid
// 	  					$this->updateShopcartAsComplete();
// 	  				}
	  			}
	  		}
	  		else 
	  		{
	  				//create stripe payment by charging one time payment
	  				//$result = $ddcstripe->chargeStripePayment();
	  				
	  				//save payment for later
	  				$result = $this->storePaymentForLater();
	  				if($result == true)
	  				{
	  					//update shopping cart as complete
	  					$this->updateShopcartAsComplete();
	  				}
	  		}
	  		return array(true,$sc[0]->header_state,JText::_('COM_DDC_PAYMENT_COMPLETE'),null);
	  	}
  	}
  	
  	if($formdata['table']!='ddcCheckout')
  	{
  		$productFound = 0;
  		foreach($sc as $row)
  		{
  			if($row->product_id == $formdata['ddc_vendor_product_id'])
  			{
  				if($row->header_state < 4):
  				//product is in cart update row
  				$data = array(
  						'shoppingcart_header_id' => $row->ddc_shoppingcart_header_id,
  						'ddc_shoppingcart_detail_id' => $row->ddc_shoppingcart_detail_id,
  						'product_id' => $formdata['ddc_vendor_product_id'],
  						'product_weight' => $formdata['product_weight'],
  						'product_weight_uom' => $formdata['product_weight_uom'],
  						'product_quantity' => $row->product_quantity+$formdata['product_quantity'],
  						'price' => $formdata['product_price'],
  						'state' => 1,
  						'session_id' => $this->_session->getId(),
  						'table' => 'shoppingcartdetails');
  				
  				$row = $this->store($data);
  				//$this->_session->set('shoppingcart_header_id',$row->ddc_shoppingcart_header_id);
  				else:
  					return array(false);
  				endif;
  				$productFound = 1;
  			}
  		}
  		if($productFound == 0)
  		{  			
  			if($this->_session->get('ddclocation',null)==null)
  			{
  				return array(false,1,JText::_('COM_DDC_POSTCODE_NOT_SET'));
  			}
  			if($this->isValidPostCodeFormat($this->_session->get('ddclocation',null)))
  			{
  				$stldistance = $this->getPostcodesDistance($this->_session->get('ddclocation',null), $formdata['shop_post_code'])/1000;
  				if(number_format($stldistance,3) > number_format($this->_params->get('distance_limit'),3))
  				{
  					return array(false,2,JText::_('COM_DDC_LOCATED_TOO_FAR'),number_format($stldistance,3));
  				}
  			}
  			else
  			{
  				return array(false,JText::_('COM_DDC_PLEASE_ENTER_VALID_POSTCODE'));
  			}
  			//product not in cart insert row
  			$data = array(
  					'shoppingcart_header_id' => $row->ddc_shoppingcart_header_id,
  					'product_id' => $formdata['ddc_vendor_product_id'],
  					'product_quantity' => $formdata['product_quantity'],
  					'price' => $formdata['product_price'],
  					'state' => 1,
  					'catid' => null,
  					'session_id' => $this->_session->getId(),
  					'table' => 'shoppingcartdetails');
  			$this->_session->set('shoppingcart_header_id',$row->ddc_shoppingcart_header_id);
  			$row = $this->store($data);
  		}
  	}
  	if(count($sc)==0)
  	{
  		$headerstate = 1;
  	}
  	else 
  	{
  		$headerstate = $sc[0]->header_state;
  	}
	return array(true,$headerstate,JText::_('COM_DDC_PRODUCT_ADDED_TO_CART'),null);
  }
  
  public function storePaymentForLater($stripeChargeToken = null)
  {
  	//get customer variable back and save to db
  	if($stripeChargeToken==null)
  	{
  		$stripeChargeToken = $this->_data['stripeToken'];
  	}
  	$date = date("Y-m-d H:i:s");
  	//add customer value to #__user_profiles table
  	// Get a db connection.
  	$db = JFactory::getDbo();
  	// Create a new query object.
  	$query = $db->getQuery(true);
  	// Insert columns.
  	$columns = array('ref','ref_id', 'token', 'state', 'created', 'modified', 'created_by', 'modified_by');
  	// Insert values.
  	$values = array($db->quote('ddcshopbox'),$this->_session->get('shoppingcart_header_id',null),$db->quote($stripeChargeToken),1,$db->quote($date),$db->quote($date),0,0);
  	// Prepare the insert query.
  	$query
  	->insert($db->quoteName('#__ddc_payments'))
  	->columns($db->quoteName($columns))
  	->values(implode(',', $values));
  	// Set the query using our newly populated query object and execute it.
  	$db->setQuery($query);
  	$result = $db->execute();
  	
  	return $result;
  }
  
  public function updateShopcartAsComplete()
  {
  	$db = JFactory::getDBO();
  	$query = $db->getQuery(TRUE);
  	// Fields to update.
  	$fields = array($db->quoteName('state') . ' = 3',$db->quoteName('modified_on'). ' = '.$db->quote($date));
  		
  	// Conditions for which records should be updated.
  	$conditions = array(
  			$db->quoteName('ddc_shoppingcart_header_id') . ' = '.$this->_session->get('shoppingcart_header_id',null)
  	);
  	$query->update($db->quoteName('#__ddc_shoppingcart_headers'))->set($fields)->where($conditions);
  	$db->setQuery($query);
  	$result = $db->execute();
  	
  	$sent = false;
  	$modelSh = new DdcshopboxModelsShopcartheaders();
  	$sent = $modelSh->sendshopcartEmail($this->_session->get('shoppingcart_header_id',null));
  	
  	$this->_session->clear('shoppingcart_header_id');
  	
  	// Add a message to the message queue
  	$result = JText::_('COM_DDC_PAYMENT_COMPLETE');
  	$result .= '<br><a href="'.JUri::root().'">Click here</a> to return to the homepage.';
  	
  	return $result;
  }
  
  public function removeCartItem($id)
  {
  	// Get a db connection.
	$db = JFactory::getDbo();
 
	// Create a new query object.
	$query = $db->getQuery(true);
	
	// delete all custom keys for user 1001.
	$conditions = array(
			$db->quoteName('ddc_shoppingcart_detail_id') . ' = '.$id			
	);
	
	$query->delete($db->quoteName('#__ddc_shoppingcart_details'));
	$query->where($conditions);
	
	$db->setQuery($query);
	
	$result = $db->execute();
	
	return $result;
  }
  public function updateCartItem($id, $val)
  {
  	// Get a db connection.
  	$db = JFactory::getDbo();
  	// Create a new query object.
  	$query = $db->getQuery(true);
  	// Fields to update.
  	$fields = array(
  			$db->quoteName('product_quantity') . ' = '.$val[0],
  			$db->quoteName('price') . ' = "'.$val[1] .'"'
  	);
  	// For which the following conditions are true.
  	$conditions = array(
  			$db->quoteName('ddc_shoppingcart_detail_id') . ' = '.$id
  	);
  	$query->update($db->quoteName('#__ddc_shoppingcart_details'))->set($fields)->where($conditions);
  	$db->setQuery($query);
  	$result = $db->execute();
  	return $result;
  }
}