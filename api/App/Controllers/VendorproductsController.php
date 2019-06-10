<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\VendorproductsModel;
use App\Models\ProfilesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VendorproductsController extends DefaultController
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
		$model = new VendorproductsModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$ddc_vendor_product_id = $this->getInput()->json->get('ddc_vendor_product_id',0,'integer');
			$vendor_id = $this->getInput()->json->get('vendor_id',0,'integer');
			$product_id = $this->getInput()->json->get('product_id',0,'integer');
			$product_type = $this->getInput()->json->get('product_type',null,'string');
			$distrib_cat_id = $this->getInput()->json->get('distrib_cat_id',null,'string');
			$vendor_product_sku = $this->getInput()->json->get('vendor_product_sku',null,'string');
			$product_gtin = $this->getInput()->json->get('product_gtin',null,'string');
			$product_asin = $this->getInput()->json->get('product_asin',null,'string');
			$product_mpn = $this->getInput()->json->get('product_mpn',null,'string');
			$vendor_product_name = $this->getInput()->json->get('vendor_product_name',null,'string');
			$vendor_product_alias = $this->getInput()->json->get('vendor_product_alias',null,'string');
			$product_description_small = $this->getInput()->json->get('product_description_small',null,'string');
			$product_description = $this->getInput()->json->get('product_description',null,'string');
			$category_id = $this->getInput()->json->get('category_id',null,'string');
			$product_weight = $this->getInput()->json->get('product_weight',null,'string');
			$product_weight_uom = $this->getInput()->json->get('product_weight_uom',null,'string');
			$product_length = $this->getInput()->json->get('product_length',null,'string');
			$product_width = $this->getInput()->json->get('product_width',null,'string');
			$product_height = $this->getInput()->json->get('product_height',null,'string');
			$product_lwh_uom = $this->getInput()->json->get('product_lwh_uom',null,'string');
			$low_stock_notification = $this->getInput()->json->get('low_stock_notification',null,'string');
			$product_available_date = $this->getInput()->json->get('product_available_date',null,'string');
			$product_availability = $this->getInput()->json->get('product_availability',null,'string');
			$product_special = $this->getInput()->json->get('product_special',null,'string');
			$product_base_uom = $this->getInput()->json->get('product_base_uom',null,'string');
			$product_packaging = $this->getInput()->json->get('product_packaging',null,'string');
			$product_params = $this->getInput()->json->get('product_params',null,'string');
			$hits = $this->getInput()->json->get('hits',null,'string');
			$intnotes = $this->getInput()->json->get('intnotes',null,'string');
			$metarobot = $this->getInput()->json->get('metarobot',null,'string');
			$metaauthor = $this->getInput()->json->get('metaauthor',null,'string');
			$layout = $this->getInput()->json->get('layout',null,'string');
			$published = $this->getInput()->json->get('published',null,'string');
			$pordering = $this->getInput()->json->get('pordering',null,'string');
			$created_on = $this->getInput()->json->get('created_on',null,'string');
			$created_by = $this->getInput()->json->get('created_by',null,'string');
			$modified_on = $this->getInput()->json->get('modified_on',null,'string');
			$modified_by = $this->getInput()->json->get('modified_by',null,'string');
			$locked_on = $this->getInput()->json->get('locked_on',null,'string');
			$locked_by = $this->getInput()->json->get('locked_by',null,'string');
			$ddc_product_price_id = $this->getInput()->json->get('ddc_product_price_id',null,'string');
			$product_price = $this->getInput()->json->get('product_price',null,'string');
			$product_currency = $this->getInput()->json->get('product_currency',null,'string');
			$product_price_estimate = $this->getInput()->json->get('product_price_estimate',null,'string');
			if($vendor_product_name==null || $vendor_id==0)
			{
				return array("success"=>false);
			}
			if($ddc_vendor_product_id==0){
				$columns = array('ddc_vendor_product_id','product_id','product_type','distrib_cat_id',
				'vendor_id','vendor_product_sku','product_gtin','product_asin','product_mpn',
				'vendor_product_name','vendor_product_alias','product_description_small','product_description',
				'category_id','product_weight','product_weight_uom',
				'product_length','product_width','product_height','product_lwh_uom',
				'low_stock_notification','product_available_date','product_availability',
				'product_special','product_base_uom','product_packaging','product_params','hits',
				'intnotes','metarobot','metaauthor','layout','published','pordering','created_on',
				'created_by','modified_on','modified_by','locked_on','locked_by');
				$data = array($ddc_vendor_product_id,$product_id,$product_type,$distrib_cat_id,
					$vendor_id,$vendor_product_sku,$product_gtin,$product_asin,$product_mpn,
					$vendor_product_name,preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $vendor_product_name))),$product_description_small, $product_description,
					$category_id,$product_weight,$product_weight_uom,
					$product_length,$product_width,$product_height,$product_lwh_uom,
					$low_stock_notification,$product_available_date,$product_availability,
					$product_special,$product_base_uom,$product_packaging,$product_params,$hits,
					$intnotes,$metarobot,$metaauthor,$layout,$published,$pordering,$date,
					$user['user_id'], $date,$user['user_id'],null,null);
				$table = '#__ddc_vendor_products';
				if($row = $model->insert($table,$columns,$data)){
					$columns = array('ddc_product_price_id','product_id','product_price','product_currency');
					$data = array($ddc_product_price_id,$row->id,$product_price,$product_currency);
					$table = '#__ddc_product_prices';
					$model->insert($table,$columns,$data);
					$item = $model->listItems($row->id);
				}
				else{
					$item->success = false;
				}
			}
			else{
				$table = '#__ddc_vendor_products';
				$fields = array(
					$this->getContainer()->get('db')->qn('product_id'). " = ". $this->getContainer()->get('db')->q($product_id),
					$this->getContainer()->get('db')->qn('product_type'). " = ". $this->getContainer()->get('db')->q($product_type),
					$this->getContainer()->get('db')->qn('distrib_cat_id'). " = ". $this->getContainer()->get('db')->q($distrib_cat_id),
					$this->getContainer()->get('db')->qn('vendor_id'). " = ". $this->getContainer()->get('db')->q($vendor_id),
					$this->getContainer()->get('db')->qn('vendor_product_sku'). " = ". $this->getContainer()->get('db')->q($vendor_product_sku),
					$this->getContainer()->get('db')->qn('product_gtin'). " = ". $this->getContainer()->get('db')->q($product_gtin),
					$this->getContainer()->get('db')->qn('product_asin'). " = ". $this->getContainer()->get('db')->q($product_asin),
					$this->getContainer()->get('db')->qn('product_mpn'). " = ". $this->getContainer()->get('db')->q($product_mpn),
					$this->getContainer()->get('db')->qn('vendor_product_name'). " = ". $this->getContainer()->get('db')->q($vendor_product_name),
					$this->getContainer()->get('db')->qn('vendor_product_alias'). " = ". $this->getContainer()->get('db')->q(preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $vendor_product_name)))),
					$this->getContainer()->get('db')->qn('product_description_small'). " = ". $this->getContainer()->get('db')->q($product_description_small),
					$this->getContainer()->get('db')->qn('product_description'). " = ". $this->getContainer()->get('db')->q($product_description),
					$this->getContainer()->get('db')->qn('category_id'). " = ". $this->getContainer()->get('db')->q($category_id),
					$this->getContainer()->get('db')->qn('product_weight'). " = ". $this->getContainer()->get('db')->q($product_weight),
					$this->getContainer()->get('db')->qn('product_weight_uom'). " = ". $this->getContainer()->get('db')->q($product_weight_uom),
					$this->getContainer()->get('db')->qn('product_length'). " = ". $this->getContainer()->get('db')->q($product_length),
					$this->getContainer()->get('db')->qn('product_width'). " = ". $this->getContainer()->get('db')->q($product_width),
					$this->getContainer()->get('db')->qn('product_height'). " = ". $this->getContainer()->get('db')->q($product_height),
					$this->getContainer()->get('db')->qn('product_lwh_uom'). " = ". $this->getContainer()->get('db')->q($product_lwh_uom),
					$this->getContainer()->get('db')->qn('low_stock_notification'). " = ". $this->getContainer()->get('db')->q($low_stock_notification),
					$this->getContainer()->get('db')->qn('product_available_date'). " = ". $this->getContainer()->get('db')->q($product_available_date),
					$this->getContainer()->get('db')->qn('product_availability'). " = ". $this->getContainer()->get('db')->q($product_availability),
					$this->getContainer()->get('db')->qn('product_special'). " = ". $this->getContainer()->get('db')->q($product_special),
					$this->getContainer()->get('db')->qn('product_base_uom'). " = ". $this->getContainer()->get('db')->q($product_base_uom),
					$this->getContainer()->get('db')->qn('product_packaging'). " = ". $this->getContainer()->get('db')->q($product_packaging),
					$this->getContainer()->get('db')->qn('product_params'). " = ". $this->getContainer()->get('db')->q($product_params),
					$this->getContainer()->get('db')->qn('hits'). " = ". $this->getContainer()->get('db')->q($hits),
					$this->getContainer()->get('db')->qn('intnotes'). " = ". $this->getContainer()->get('db')->q($intnotes),
					$this->getContainer()->get('db')->qn('metarobot'). " = ". $this->getContainer()->get('db')->q($metarobot),
					$this->getContainer()->get('db')->qn('metaauthor'). " = ". $this->getContainer()->get('db')->q($metaauthor),
					$this->getContainer()->get('db')->qn('layout'). " = ". $this->getContainer()->get('db')->q($layout),
					$this->getContainer()->get('db')->qn('published'). " = ". $this->getContainer()->get('db')->q($published),
					$this->getContainer()->get('db')->qn('pordering'). " = ". $this->getContainer()->get('db')->q($pordering),
					$this->getContainer()->get('db')->qn('modified_on'). " = ". $this->getContainer()->get('db')->q($date),
					$this->getContainer()->get('db')->qn('modified_by'). " = ". $this->getContainer()->get('db')->q($modified_by)
				);
				$conditions = array($this->getContainer()->get('db')->qn('ddc_vendor_product_id'). ' = '. $ddc_vendor_product_id);
				if($model->update($table,$fields,$conditions)){
					if($ddc_product_price_id==null){
						$columns = array('ddc_product_price_id','product_id','product_price','product_currency');
						$data = array($ddc_product_price_id,$ddc_vendor_product_id,$product_price,$product_currency);
						$table = '#__ddc_product_prices';
						$model->insert($table,$columns,$data);
					}
					else{
						$table = '#__ddc_product_prices';
						$fields = array(
							$this->getContainer()->get('db')->qn('product_price'). " = ". $this->getContainer()->get('db')->q($product_price),
							$this->getContainer()->get('db')->qn('product_currency'). " = ". $this->getContainer()->get('db')->q($product_currency),
						);
						$conditions = array($this->getContainer()->get('db')->qn('ddc_product_price_id'). ' = '. $ddc_product_price_id);
						$model->update($table,$fields,$conditions);
					}
					$item = $model->listItems($ddc_vendor_product_id);
				}
			}
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//List all vendors
			$item = $model->listItems($id);
		}

		return $item;
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
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$item = $model->getProductTypes($id, null, $pc);
		}
		return $item;

	}

	public function shop(){
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
		$model = new VendorproductsModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('id',null,'string');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){

			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//List all vendors
			$item = $model->listItems(null,$id);
			for($i=0;$i<count($item);$i++)
			{
				if($item[$i]->image_link==null)
				{
					$item[$i]->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
		}

		return $item;
	}
}