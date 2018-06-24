<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\MessagesModel;
use App\Models\ProfilesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class MessagesController extends DefaultController
{
	public function index()
	{
		$usertoken = null;
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		if(!$user['success']){
			//user did not validate
			return array('success'=>false, 'msg'=> 'Authentication failed');
		}
		$this->getInput()->set('user_id',$user['user_id']);
		$date = date('Y-m-d H:i:s');
		$id = $this->getInput()->getString('id');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			$input = $this->getInput()->json;
			//get function
			if($id==null){
				//add new record
				$table = '#__ddc_messages';
				$data = array(
					$this->getInput()->getString('user_id'),
					$input->get('user_to'),
					$input->get('message_id',0),
					$input->get('group_id',0),
					$input->get('state',1),
					$input->get('subject','','string'),
					$input->get('message',null,'string'),
					$date,
					$user['user_id'],
					$date,
					$user['user_id']
				);
				$columns = array("user_id_from", "user_id_to", "parent_id", "group_id", "state", "subject","message","created_on","created_by","modified_on","modified_by");
				$item = $promodel->insert($table,$columns,$data);
			}
			else{
				//update the existing record
				$table = '#__ddc_messages';
				$fields = array(
					$this->getContainer()->get('db')->qn('subject'). " = ". $input->get('subject',''),
					$this->getContainer()->get('db')->qn('message'). " = ". $input->get('message',null),
					$this->getContainer()->get('db')->qn('modified_on'). " = ". $this->getContainer()->get('db')->q($date),
					$this->getContainer()->get('db')->qn('modified_by'). " = ". $this->getInput()->getString('user_id'),
				);
				$conditions = array(
					$this->getContainer()->get('db')->qn('id'). ' = '. $id,
					$this->getContainer()->get('db')->qn('user_id_from'). ' = '. $user['user_id']
					);
				$item = $promodel->update($table,$fields,$conditions);
			}
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get messages
			$group = $this->getInput()->getString('group','0');
			$model = new MessagesModel($this->getInput(), $this->getContainer()->get('db'));
			$items = $model->listItems(null,null,null,$group);
			return $items;
		}
		
	}
	
	public function edit()
	{
		$id = $this->getInput()->getString('id');
		$item = null;
		if($id!=null)
		{
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$item = $model->getItem($id);
		}
		
		return array('item'=>$item);
	}

	public function userguesses(){
		$item = new \StdClass();
		$item->success = false;
		$h = getallheaders();
		$date = date('Y-m-d H:i:s');
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$token = $this->getInput()->json->get('apptoken',null);
		if(!$user = $promodel->authenticate_token($usertoken)){
			//user did not validate
			return array('success'=>false, 'msg'=> 'Authentication failed');
		}
		$id = $this->getInput()->getString('id',null);
		$input = $this->getInput()->json;
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//get function
			if($id==null){
				//add new record
				$table = '#__ddc_userguesses';
				$data = array(
					$user['user_id'],
					$input->get('game_id'),
					$input->get('teamA',0),
					$input->get('teamB',0),
					$input->get('scoreA',0),
					$input->get('scoreB',0),
					$date,
					$user['user_id'],
					$date,
					$user['user_id']
				);
				$columns = array("user_id", "game_id", "teamA", "teamB", "scoreA", "scoreB","created_on","created_by","modified_on","modified_by");
				$item = $promodel->insert($table,$columns,$data);
			}
			else{
				//update the existing record
				$table = '#__ddc_userguesses';
				$fields = array(
					$this->getContainer()->get('db')->qn('scoreA'). " = ". $input->get('scoreA',0),
					$this->getContainer()->get('db')->qn('scoreB'). " = ". $input->get('scoreB',0),
					$this->getContainer()->get('db')->qn('modified_on'). " = ". $this->getContainer()->get('db')->q($date),
					$this->getContainer()->get('db')->qn('modified_by'). " = ". $user['user_id'],
				);
				$conditions = array(
					$this->getContainer()->get('db')->qn('id'). ' = '. $id,
					$this->getContainer()->get('db')->qn('user_id'). ' = '. $user['user_id'],
					$this->getContainer()->get('db')->qn('game_id'). ' = '. $input->get('game_id')
					);
				
				$data = array(

					$date,
					$user['user_id']
				);
				$columns = array("id","user_id", "game_id", "teamA", "teamB", "scoreA", "scoreB","modified_on","modified_by");
				$item = $promodel->update($table,$fields,$conditions);
			}
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='DELETE'){

		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$items = $model->listItems();
			return $items;
		}
	}

	public function bonusquestions()
	{
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		$this->getInput()->set('user_id',$user['user_id']);
		$id = urldecode($this->getInput()->getString('id'));
		$input = $this->getInput()->json;
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//get function
			if($id==null){
				//add new record
				$table = '#__ddc_user_bonus_guesses';
				$data = array(
					$user['user_id'],
					'1',
					$input->get('question_id',null),
					$input->get('user_guess',null),
					$date,
					$user['user_id'],
					$date,
					$user['user_id']
				);
				$columns = array("user_id", "comp_id", "question_id", "answer","created_on","created_by","modified_on","modified_by");
				$item = $promodel->insert($table,$columns,$data);
			}
			else{
				//update the existing record
				$table = '#__ddc_user_bonus_guesses';
				$fields = array(
					$this->getContainer()->get('db')->qn('answer'). " = ". $input->get('user_guess'),
					$this->getContainer()->get('db')->qn('modified_on'). " = ". $this->getContainer()->get('db')->q($date),
					$this->getContainer()->get('db')->qn('modified_by'). " = ". $user['user_id']
				);
				$conditions = array(
					$this->getContainer()->get('db')->qn('question_id'). ' = '. $input->get('question_id'),
					$this->getContainer()->get('db')->qn('user_id'). ' = '. $user['user_id']
					);
				
				$data = array(

					$date,
					$user['user_id']
				);
				$columns = array("id","user_id", "game_id", "teamA", "teamB", "scoreA", "scoreB","modified_on","modified_by");
				$item = $promodel->update($table,$fields,$conditions);
			}
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$items = $model->getBonusQuestions();
			return $items;
		}
	}

	public function players()
	{
		$date = date('Y-m-d H:i:s');
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$item = new \StdClass();
		$item->success = false;
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		$this->getInput()->set('user_id',$user['user_id']);
		$id = urldecode($this->getInput()->getString('id'));
		$input = $this->getInput()->json;
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			
			
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$items = $model->getPlayers();
			return $items;
		}
	}

	public function teams()
	{
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		$this->getInput()->set('user_id',$user['user_id']);
		$id = urldecode($this->getInput()->getString('id'));
		$token = $this->getInput()->json->get('apptoken',null);
		if(($input = $this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//get function
			$item->method = 'POST';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			//get user group map
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$items = $model->getTeams();
			return $items;
		}
		
	}

}