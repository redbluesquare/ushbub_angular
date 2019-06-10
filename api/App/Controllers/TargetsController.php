<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\TransactionsModel;
use App\Models\TargetsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class TargetsController extends DefaultController
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
		$model = new TargetsModel($this->getInput(), $this->getContainer()->get('db'));
		$tmodel = new TransactionsModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//Check if account is valid
			if(!$tmodel->isValidAccount($this->getInput()->json->get('account_id',null),$user['user_id'])){
				return $item;
			}
			$data = array(
				$this->getInput()->json->get('target_date',null,'string'),
				$this->getInput()->json->get('account_id',null,'string'),
				$this->getInput()->json->get('dr_cr','dr','string'),
				$this->getInput()->json->get('target_balance',null),
				$this->getInput()->json->get('state',1,'string'),
				$date,$user['user_id'],$date,$user['user_id']
			);
			$columns = array('target_date','account_id','dr_cr','target_balance','state','created_on','created_by','modified_on','modified_by');
			$table = '#__ddc_targets';
			if($result = $model->insert($table,$columns,$data)){
				$item = $model->listItems($result->id);
			}
			else{
				$item->msg = 'Sorry, it did not save. Please try again';
			}
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user accounts
			$item = $model->listItems($id,$user['user_id']);
			for($i=0;$i<count($item);$i++){
				$item[$i]->target_balance = (float)$item[$i]->target_balance;
			}

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

	public function summary()
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
		$model = new TransactionsModel($this->getInput(), $this->getContainer()->get('db'));
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		if($usertoken!=''){
			$user = $promodel->authenticate_token($usertoken);
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('task');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//Check if account is valid
			if(!$model->isValidAccount($this->getInput()->json->get('account_from',null),$user['user_id']) || !$model->isValidAccount($this->getInput()->json->get('account_to',null),$user['user_id'])){
				return $item;
			}

		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get transaction summary
			$account_to = $this->getInput()->getString('account_to',0);
			$from_date = $this->getInput()->getString('from_date',null);
			$to_date = $this->getInput()->getString('to_date',null);
			$item = $model->transationSummary($account_to,$from_date,$to_date);
			for($i=0;$i<count($item);$i++){
				$item[$i]->transaction_total = (float)$item[$i]->transaction_total;
			}

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
}