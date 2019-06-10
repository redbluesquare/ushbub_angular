<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\AccountsModel;
use App\Models\BalancesModel;
use App\Models\AccountTypesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class AccountsController extends DefaultController
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
		$model = new AccountsModel($this->getInput(), $this->getContainer()->get('db'));
		$bmodel = new BalancesModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$data = array(
				$this->getInput()->json->get('account_name',null,'string'),
				preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $this->getInput()->json->get('account_name',null,'string')))),
				$this->getInput()->json->get('account_number',null,'string'),$this->getInput()->json->get('sort_code',null,'string'),
				$this->getInput()->json->get('account_type',null),$user['user_id'],
				$this->getInput()->json->get('interest_rate',null,'string'),$this->getInput()->json->get('currency',0),
				$this->getInput()->json->get('notes',null,'string'),$this->getInput()->json->get('state',1,'string'),
				$date,$user['user_id'],$date,$user['user_id']
			);
			$columns = array('account_name','alias','account_number','sort_code','account_type','user_id','interest_rate','currency_id','notes','state','created_on','created_by','modified_on','modified_by');
			$table = '#__ddc_accounts';
			if($this->getInput()->json->get('account_id',0)==0){
				//Add a new account
				if($result = $model->insert($table,$columns,$data)){
					$item = $model->listItems($result->id);
				}
				else{
					$item->msg = 'Sorry, it did not save. Please try again';
				}
			}else{
				//Update the current account
				$fields = array(
					$this->getContainer()->get('db')->qn('account_name'). " = ".$this->getContainer()->get('db')->q($this->getInput()->json->get('account_name',null,'string')),
					$this->getContainer()->get('db')->qn('account_number'). " = ".$this->getContainer()->get('db')->q($this->getInput()->json->get('account_number',null,'string')),
					$this->getContainer()->get('db')->qn('sort_code'). " = '".$this->getInput()->json->get('sort_code',null,'string')."'",
					$this->getContainer()->get('db')->qn('interest_rate'). " = '".$this->getInput()->json->get('interest_rate',null,'string')."'",
					$this->getContainer()->get('db')->qn('currency_id'). " = '".$this->getInput()->json->get('currency',null,'string')."'",
					$this->getContainer()->get('db')->qn('notes'). " = ".$this->getContainer()->get('db')->q($this->getInput()->json->get('notes',null,'string'))
				);
				$conditions = array($this->getContainer()->get('db')->qn('ddc_account_id'). ' = '.$this->getInput()->json->get('account_id',0));
				if($result = $model->update($table, $fields, $conditions)){
					$item = $model->listItems($this->getInput()->json->get('account_id',0));
				}
				else{
					$item->msg = 'Sorry, it did not save. Please try again';
				}
			}
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user accounts
			$item = $model->listItems($id,$user['user_id']);

		}
		if(($input = $this->getInput()->getMethod()==='DELETE') &&($user['user_id'])){
			$conditions = array(
				$this->getContainer()->get('db')->qn('id'). ' = '. $id
				);
			$table = '#__ddc_accounts';
			if($result = $model->delete($table,$conditions)){
				$item->success = true;
			}

		}
		return $item;
	}

	public function types()
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
		$model = new AccountTypesModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$item = $model->listItems($id);

		}
		if($input = $this->getInput()->getMethod()==='DELETE'){

		}
		return $item;
	}

	public function balances()
	{
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		$usertoken = '';
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){$usertoken = $value;}
		}
		$item = new \StdClass();
		$item->success = false;
		$bmodel = new BalancesModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$table = '#__ddc_balances';
			$dc = '+';
			if($this->getInput()->json->get('dr_cr',null,'string')=='cr'){$dc='-';}
			if($this->getInput()->json->get('ddc_balance_id',0) == ''){
				//save a new balance
				$data = array(
					$this->getInput()->json->get('ddc_account_id',null,'string'),
					$dc.$this->getInput()->json->get('balance_value',null),
					$this->getInput()->json->get('dr_cr',null,'string'),
					$this->getInput()->json->get('record_date',null),
					$this->getInput()->json->get('state',1),$date,$user['user_id'],$date,$user['user_id']
				);
				$columns = array('account_id','balance','dr_cr','record_date','state','created_on','created_by','modified_on','modified_by');
				if($result = $bmodel->insert($table,$columns,$data)){
					$item = $bmodel->listItems(0,$user['user_id'],$this->getInput()->json->get('ddc_account_id',0));
				}
			}else{
				//update an existing balance
				$fields = array(
					$this->getContainer()->get('db')->qn('balance'). " = '".$this->getInput()->json->get('balance_value',null)."'",
					$this->getContainer()->get('db')->qn('dr_cr'). " = '".$this->getInput()->json->get('dr_cr',null,'string')."'",
					$this->getContainer()->get('db')->qn('record_date'). " = '".$this->getInput()->json->get('record_date',null)."'",
					$this->getContainer()->get('db')->qn('modified_on'). " = '".$date."'",
					$this->getContainer()->get('db')->qn('modified_by'). " = '".$user['user_id']."'"
				);
				$conditions = array($this->getContainer()->get('db')->qn('ddc_balance_id'). ' = '.$this->getInput()->json->get('ddc_balance_id',0));
				if($result = $bmodel->update($table, $fields, $conditions)){
					$item = $bmodel->listItems(0,$user['user_id'],$this->getInput()->json->get('ddc_account_id',0));
				}
			}
			
			
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get balances
			$item = $bmodel->listItems($id,$user['user_id'],$this->getInput()->getString('account_id',null));

		}
		if($input = $this->getInput()->getMethod()==='DELETE'){

		}
		return $item;
	}
}