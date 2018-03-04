<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ShoppingcartheadersModel;
use App\Models\ShoppingcartdetailsModel;
use App\Models\ProfilesModel;
use App\Models\ProductsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;
use App\Models\App\Models;

class ShoppingcartsController extends DefaultController
{
	
	public function index()
	{
		$id = $this->getInput()->getString('id',null);
		$usertoken = $this->getInput()->getString("usertoken", null);
		$model = new ShoppingcartheadersModel($this->getInput(), $this->getContainer()->get('db'));
		$detailModel = new ShoppingcartdetailsModel($this->getInput(), $this->getContainer()->get('db'));
		$modelProfile = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems((int)$id);
		for($i=0;$i<count($items);$i++)
		{
			$items[$i]->totalitems = 0;
			$items[$i]->totalprice = 0;
			unset($items[$i]->session_id);
			$items[$i]->address = $modelProfile->getShipAddress($usertoken);
			$card = $modelProfile->getStripeCustomer($usertoken);
			unset($items[$i]->address->token);
			if($card!=null){
				$card = json_decode($card->profile_value);
				unset($card->stripeCustomerToken);
				$items[$i]->card = $card;
			}
			$items[$i]->details = $detailModel->listItems(null,$items[$i]->ddc_shoppingcart_header_id);
			for($j=0;$j<count($items[$i]->details);$j++)
			{
				$items[$i]->details[$j]->product_params = json_decode($items[$i]->details[$j]->product_params);
				$items[$i]->totalitems = $items[$i]->totalitems+$items[$i]->details[$j]->product_quantity;
				$items[$i]->totalprice = $items[$i]->totalprice+$items[$i]->details[$j]->product_quantity*$items[$i]->details[$j]->price;
				if($items[$i]->details[$j]->image_link==null)
				{
					$items[$i]->details[$j]->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
			$items[$i]->del_dates = $model->getDelDates();
		}
		if($items == null){
			$items = array(0 => array('totalitems' => 0, 'totalprice' => 0));
		}
		return array("shoppingcarts"=> $items);
	}
	
	public function delete()
	{
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = $this->getInput()->getString("usertoken", null);
		$user = $model->authenticate_token($usertoken);
		$cartItem_id = $this->getInput()->getString('itemId',null);
		$date = Date("Y-m-d H:i:s");
		$item = null;
		$items = null;
		if($user['success']!=false){
			$detailModel = new ShoppingcartdetailsModel($this->getInput(), $this->getContainer()->get('db'));
			$table = "#__ddc_shoppingcart_details";
			$fields = array($this->getContainer()->get('db')->qn('state'). " = '-1'",$this->getContainer()->get('db')->qn('modified_on'). " = '". $date ."'");
			$conditions = array($this->getContainer()->get('db')->qn('product_id'). ' = '. $this->getContainer()->get('db')->q($cartItem_id) );
			$row = $detailModel->update($table, $fields, $conditions);
		}
		return array("shoppingcarts"=> "Item deleted");
	}
	
	public function edit()
	{
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = $this->getInput()->getString("usertoken", null);
		$user = $model->authenticate_token($usertoken);
		$item = null;
		$items = null;
		if($user['success']!=false){
			$state = $this->getInput()->getString('state',null);
			$product_id = $this->getInput()->getString('product_id',null);
			$date = Date("Y-m-d H:i:s");
			if((int)$state == 1){
				$model = new ShoppingcartheadersModel($this->getInput(), $this->getContainer()->get('db'));
				$detailModel = new ShoppingcartdetailsModel($this->getInput(), $this->getContainer()->get('db'));
				$modelProduct = new ProductsModel($this->getInput(), $this->getContainer()->get('db'));
				$item = $modelProduct->getItemById($product_id);
				$item->product_price = $modelProduct->getProductPrice($product_id);
				$sch = $model->getItemById();
				if( $sch!=null )
				{
					$scdItem = $detailModel->getItemById(null, $sch->ddc_shoppingcart_header_id, $product_id);
					$table = "#__ddc_shoppingcart_details";
					if( count($scdItem) == 0)
					{
						$columns = array("shoppingcart_header_id", "product_id", "product_quantity", "product_weight", "product_weight_uom", "price", "created_on", "modified_on");
						$data = array($sch->ddc_shoppingcart_header_id, $product_id, $modelProduct->getpartjsonfield($item->product_params, 'min_order_level'), $item->product_weight, $item->product_weight_uom, $item->product_price, $date, $date);
						$detailModel->insert($table, $columns, $data);
					}
					else
					{
						$fields = array($this->getContainer()->get('db')->qn('product_quantity'). " = '". ($scdItem->product_quantity+1)."'",$this->getContainer()->get('db')->qn('modified_on'). " = '". $date ."'");
						$conditions = array($this->getContainer()->get('db')->qn('product_id'). ' = '. $this->getContainer()->get('db')->q($product_id) );
						$row = $detailModel->update($table, $fields, $conditions);
							
					}
				}
				else{
					$table = "#__ddc_shoppingcart_headers";
					$columns = array("user_id", "session_id", "created_on", "modified_on", "state");
					$data = array($user['user_id'], $usertoken,  $date, $date, 1);
					$row = $model->insert($table, $columns, $data);
					$sch = $model->getItemById();
					$table = "#__ddc_shoppingcart_details";
					$columns = array("shoppingcart_header_id", "product_id", "product_quantity", "product_weight", "product_weight_uom", "price", "created_on", "modified_on");
					$data = array($sch->ddc_shoppingcart_header_id, $product_id, $modelProduct->getpartjsonfield($item->product_params, 'min_order_level'), $item->product_weight, $item->product_weight_uom, $item->product_price, $date, $date);
					$detailModel->insert($table, $columns, $data);
				}
				$items = $model->listItems();
				for($i=0;$i<count($items);$i++)
				{
					unset($items[$i]->session_id);
					$items[$i]->totalitems = 0;
					$items[$i]->details = $detailModel->listItems(null);
					for($j=0;$j<count($items[$i]->details);$j++)
					{
						$items[$i]->details[$j]->product_params = json_decode($items[$i]->details[$j]->product_params);
						$items[$i]->totalitems = $items[$i]->totalitems+$items[$i]->details[$j]->product_quantity;
					}
				}	
			}
		}
		
		return array("shoppingcarts"=> $items);
	}
	
	public function updateshoppingcart()
	{
		$shopcartheadermodel = new ShoppingcartheadersModel($this->getInput(), $this->getContainer()->get('db'));
		$model = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$usertoken = $this->getInput()->getString("usertoken", null);
		$user = $model->authenticate_token($usertoken);
		$return = new \StdClass();
		$return->success = false;
		if($user['success']!=false){
			$state = $this->getInput()->getString('state',null);
			$date = Date("Y-m-d H:i:s");
			if($state == 3){
				$address = $model->getShipAddress($usertoken);
				$address = $address['info'];
				$pm = 2;
				$deldate = $this->getInput()->getString('del_date',null);
				$deltime = '00:00:00';
				$deltype = 1;
				$shippingcost = $this->getInput()->getString('del_cost',null);
				$email = $address->email;
				$mobile_no = $address->phone_2;
				$tel_no = $address->phone_1;
				$fname = $address->first_name;
				$lname = $address->last_name;
				$add1 = $address->address_1;
				$add2 = $address->address_2;
				$add3 = null;
				$town = $address->city;
				$county = $address->county;
				$postcode = $address->zip;
				$country = 222;
				$comment = $this->getInput()->getString('comment',null);
				$coupon_id = null;
				$coupon_value = null;
				$state = $this->getInput()->getString('state',null);
				$row = $shopcartheadermodel->getItemById();
				$shopcartheadermodel->sendshopcartEmail($row->ddc_shoppingcart_header_id,"sales@ushbub.co.uk","Ushbub Limited", "Ushbub");
				
				$table = "#__ddc_shoppingcart_headers";
				$fields = array(
					$this->getContainer()->get('db')->qn('payment_method'). " = '". ($pm)."'",
					$this->getContainer()->get('db')->qn('delivery_date'). " = '". ($deldate)."'",
					$this->getContainer()->get('db')->qn('delivery_time'). " = '". ($deltime)."'",
					$this->getContainer()->get('db')->qn('delivery_type'). " = '". ($deltype)."'",
					$this->getContainer()->get('db')->qn('shipping_cost'). " = '". ($shippingcost)."'",
					$this->getContainer()->get('db')->qn('email_to'). " = '". ($email)."'",
					$this->getContainer()->get('db')->qn('first_name'). " = '". ($fname)."'",
					$this->getContainer()->get('db')->qn('last_name'). " = '". ($lname)."'",
					$this->getContainer()->get('db')->qn('mobile_no'). " = '". ($mobile_no)."'",
					$this->getContainer()->get('db')->qn('telephone_no'). " = '". ($tel_no)."'",
					$this->getContainer()->get('db')->qn('address_line_1'). " = '". ($add1)."'",
					$this->getContainer()->get('db')->qn('address_line_2'). " = '". ($add2)."'",
					$this->getContainer()->get('db')->qn('address_line_3'). " = '". ($add3)."'",
					$this->getContainer()->get('db')->qn('town'). " = '". ($town)."'",
					$this->getContainer()->get('db')->qn('county'). " = '". ($county)."'",
					$this->getContainer()->get('db')->qn('post_code'). " = '". ($postcode)."'",
					$this->getContainer()->get('db')->qn('country'). " = '". ($country)."'",
					$this->getContainer()->get('db')->qn('comment'). " = '". ($comment)."'",
					$this->getContainer()->get('db')->qn('coupon_id'). " = '". ($coupon_id)."'",
					$this->getContainer()->get('db')->qn('coupon_value'). " = '". ($coupon_value)."'",
					$this->getContainer()->get('db')->qn('state'). " = '".$this->getInput()->getString('state',null)."'",
					$this->getContainer()->get('db')->qn('modified_on'). " = '". $date ."'"
				);
				$conditions = array('session_id = "'.$this->getInput()->getString('usertoken',null).'"');
				$row = $shopcartheadermodel->update($table, $fields, $conditions);
				$return->info = $row;
				$return->success = true;
				return array("process"=>$return);
			}
		}
		
	}
}