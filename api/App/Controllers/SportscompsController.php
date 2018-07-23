<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\SportscompsModel;
use App\Models\UsergroupsModel;
use Joomla\Event\Dispatcher;
use Joomla\Session\Session;

class SportscompsController extends DefaultController
{
	public function index()
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
		$id = $this->getInput()->getString('id');
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
			$items = $model->compgames($id,null);
			return $items;
		}
		
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
			$items = $model->compgames($id,null);;
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
			for($i=0;$i<count($items);$i++){
				$items[$i]->answer_value = $model->getAnswer($items[$i]);
				$items[$i]->userguesses = $model->getBonusGuesses($items[$i]->id);
				for($j=0;$j<count($items[$i]->userguesses);$j++){
					$items[$i]->userguesses[$j]->answer_value = $model->getAnswer($items[$i]->userguesses[$j]);
				}
			}
			return $items;
		}
	}

	public function games()
	{
		$comp_id = 1;
		$usertoken = null;
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		if($user['success']){
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('id');
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
			$items = $model->getGames($id,$comp_id);
			for($i=0;$i<count($items);$i++){
				$items[$i]->guesses = $model->pastgames($items[$i]->id,null);
			}
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

	public function matchday()
	{
		$date = date('Y-m-d H:i:s');
		$usertoken = null;
		$h = getallheaders();
		foreach($h as $name => $value){
			if(ucfirst($name) == 'Bearer'){
				$usertoken = $value;
			}
		}
		$input = $this->getInput()->json;
		if(($input->get('game_id',0)==0) || ($input->get('secret',null,'string')!='YouGotzToBeKiddingMe#18')){
			return array();
		}
		$promodel = new ProfilesModel($this->getInput(), $this->getContainer()->get('db'));
		$user = $promodel->authenticate_token($usertoken);
		$ugmodel = new UsergroupsModel($this->getInput(), $this->getContainer()->get('db'));
		if($user['success']){
			$this->getInput()->set('user_id',$user['user_id']);
		}
		$id = $this->getInput()->getString('id');
		$token = $this->getInput()->json->get('apptoken',null);
		if(($this->getInput()->getMethod()==='POST') && ($token == "ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t")){
			//function to update the matches
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$table = '#__ddc_games';
			$fields = array(
				$this->getContainer()->get('db')->qn('score1'). " = '". $input->get('score1',0) ."'",
				$this->getContainer()->get('db')->qn('score2'). " = '". $input->get('score2',0) ."'"
			);
			$conditions = array($this->getContainer()->get('db')->qn('id'). ' = '. $input->get('game_id'	));
			$model->update($table,$fields,$conditions);
			$participants = $ugmodel->getParticipants(3);
			for($j=0;$j<count($participants);$j++){
				$games = $model->pastgames($id,null,$participants[$j]->user_id);
				if(count($games)==0){
					$table = '#__ddc_userguesses';
					$data = array(
						$participants[$j]->user_id,
						$id,
						0,
						0,
						$date,
						0,
						$date,
						0
					);
					$columns = array("user_id", "game_id", "scoreA", "scoreB","created_on","created_by","modified_on","modified_by");
					$promodel->insert($table,$columns,$data);
				}
			}
			$games = $model->pastgames($id,null);
			if($games[0]->score1 > $games[0]->score2){$game_result = 'w';}
			elseif($games[0]->score1 == $games[0]->score2){$game_result = 'd';}
			else{$game_result = 'l';}
			for($i=0;$i<count($games);$i++){
				//check for win lose or draw
				if($games[$i]->scoreA > $games[$i]->scoreB){$guess_result = 'w';}
				elseif($games[$i]->scoreA == $games[$i]->scoreB){$guess_result = 'd';}
				else{$guess_result = 'l';}
				$resultA = 0;
				$resultB = 0;
				$resultC = 0;
				if($game_result == $guess_result){$resultA = 1;}
				if($games[0]->score1 == $games[$i]->scoreA){$resultB = 1;}
				if($games[0]->score2 == $games[$i]->scoreB){$resultC = 1;}
				$points = $resultA+$resultB+$resultC;				
				//update the existing record
				$table = '#__ddc_userguesses';
				$fields = array($this->getContainer()->get('db')->qn('points'). " = '". $points ."'");
				$conditions = array($this->getContainer()->get('db')->qn('id'). ' = '. $games[$i]->guess_id);
				$item = $model->update($table,$fields,$conditions);
			}
			$games = $model->pastgames($id,null);
			return $games;
		}
		if($input = $this->getInput()->getMethod()==='PUT'){
			//get function
			$item->method = 'PUT';
			return $item;
		}
		if($input = $this->getInput()->getMethod()==='GET'){
			$model = new SportscompsModel($this->getInput(), $this->getContainer()->get('db'));
			$games = $model->pastgames($id,0);
			return $games;
		}
	}

	public function teams()
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