<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\TransactionsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class TransactionsController extends DefaultController
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
			$data = array(
				$this->getInput()->json->get('record_date',null,'string'),
				$this->getInput()->json->get('transaction_type',null,'string'),
				$this->getInput()->json->get('transaction_description',null,'string'),
				$this->getInput()->json->get('account_from',null),
				$this->getInput()->json->get('account_to',null,'string'),$user['user_id'],
				$this->getInput()->json->get('transaction_value',null),
				$this->getInput()->json->get('state',1,'string'),
				$date,$user['user_id'],$date,$user['user_id']
			);
			$columns = array('record_date','transaction_type','transaction_description','account_from','account_to','user_id','transaction_value','state','created_on','created_by','modified_on','modified_by');
			$table = '#__ddc_transactions';
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
				$item[$i]->transaction_value = (float)$item[$i]->transaction_value;
				
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
				$item[$i]->target_value = (float)$item[$i]->target_value;
				if($item[$i]->target_value==null){
					$item[$i]->target_value = 0;
				}
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