<?php
namespace App\Models;
use App\Models\DefaultModel;

class SportscompsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_vendor_id	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('g.id, g.game_no,g.group,g.game_date,g.team1,g.team2')
			->select('t1.country as country1,t1.rank as rank1, t1.flag as flag1')
			->select('t2.country as country2,t2.rank as rank2, t2.flag as flag2')
			->select('ug.id as guess_id,ug.scoreA,ug.scoreB')
			->from($this->db->quoteName('#__ddc_games', 'g'))
			->leftJoin('#__ddc_teams as t1 on t1.id = g.team1')
			->leftJoin('#__ddc_teams as t2 on t2.id = g.team2')
			->leftJoin('#__ddc_userguesses as ug on (ug.game_id = g.id) AND (ug.user_id = '.$this->input->get('user_id',0,'string').')')
			->group('g.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id1, $id2)
	{
		if($this->input->get('comp_id',null,'string')!=null)
		{
			$query->where('c.comp_id = "'.$this->input->get('comp_id', null,'string').'"');
		}
		return $query;
	}

	public function sportscompusers($id){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('c.*')
			->select('u.first_name, u.username')
			->select('ugm.state')
			->from($this->db->quoteName('#__ddc_competitions', 'c'))
			->leftJoin('#__ddc_user_competition as uc on uc.comp_id = c.id')
			->leftJoin('#__ddc_users as u on u.id = uc.user_id')  
			->leftJoin('#__ddc_user_usergroup_map as ugm on u.id = ugm.user_id')
			->group('c.id,u.id');
		if((int)$id > 0){
			$query->where('c.id = "'.(int)$id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function compgames($id,$comp_id){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('g.id, g.game_no, g.group, g.game_date, g.team1, g.team2, g.score1, g.score2')
			->select('t1.country as country1, t1.rank as rank1, t1.flag as flag1')
			->select('t2.country as country2, t2.rank as rank2, t2.flag as flag2')
			->select('ug.id as guess_id, ug.scoreA, ug.scoreB')
			->from($this->db->quoteName('#__ddc_games', 'g'))
			->leftJoin('#__ddc_teams as t1 on t1.id = g.team1')
			->leftJoin('#__ddc_teams as t2 on t2.id = g.team2')
			->leftJoin('#__ddc_userguesses as ug on (ug.game_id = g.id) AND (ug.user_id = '.$this->input->get('user_id',0,'string').')')
			->group('g.id')
			->where('g.game_date > Now()');
		if((int)$id > 0){
			$query->where('g.id = "'.(int)$id.'"');
		}
		if((int)$comp_id > 0){
			$query->where('g.comp_id = "'.(int)$comp_id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function pastgames($id,$comp_id,$user_id=0){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('g.id, g.game_no, g.group, g.game_date, g.team1, g.team2, g.score1, g.score2')
			->select('u.first_name,u.username')
			->select('ug.id as guess_id, ug.scoreA, ug.scoreB,ug.points')
			->from($this->db->quoteName('#__ddc_games', 'g'));
		if((int)$user_id > 0){
			$query->rightJoin('#__ddc_userguesses as ug on (ug.game_id = g.id) AND (ug.user_id = "'.$user_id.'")');
		}else{
			$query->leftJoin('#__ddc_userguesses as ug on (ug.game_id = g.id)');
		}
		$query->leftJoin('#__ddc_users as u on (u.id = ug.user_id)');
		$query->group('g.id, ug.id')
			->order('u.first_name ASC')
			->where('g.game_date < Now()');
		if((int)$id > 0){
			$query->where('g.id = "'.(int)$id.'"');
		}
		if((int)$comp_id > 0){
			$query->where('g.comp_id = "'.(int)$comp_id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function getGames($id = null, $comp_id = null){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('g.id, g.game_no, g.group, g.game_date, g.team1, g.team2, g.score1, g.score2')
			->select('t1.country as country1, t1.rank as rank1, t1.flag as flag1')
			->select('t2.country as country2, t2.rank as rank2, t2.flag as flag2')
			->from($this->db->quoteName('#__ddc_games', 'g'))
			->leftJoin('#__ddc_teams as t1 on t1.id = g.team1')
			->leftJoin('#__ddc_teams as t2 on t2.id = g.team2')
			->group('g.id')
			->order('g.game_date');
		if($id > 0){
			$query->where('g.id IN ('.$id.')');
		}
		if((int)$comp_id > 0){
			$query->where('g.comp_id = "'.(int)$comp_id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function getBonusQuestions($id=null, $comp_id=1){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('bq.*')
			->from($this->db->quoteName('#__ddc_bonus_questions', 'bq'))
			->group('bq.id');
		if((int)$id > 0){
			$query->where('bq.id = "'.(int)$id.'"');
		}
		if((int)$comp_id > 0){
			$query->where('bq.comp_id = "'.(int)$comp_id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function getBonusGuesses($id=null, $user_id=null, $comp_id=1){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('bg.*')
			->select('u.first_name,bq.question_no')
			->from($this->db->quoteName('#__ddc_user_bonus_guesses', 'bg'))
			->leftJoin('#__ddc_users as u on u.id = bg.user_id')
			->leftJoin('#__ddc_bonus_questions as bq on bq.id = bg.question_id')
			->group('bg.id')
			->order('u.first_name ASC, bg.id ASC');

		if((int)$id > 0){
			$query->where('bg.question_id = "'.(int)$id.'"');
		}
		if((int)$user_id > 0){
			$query->where('bg.user_id = "'.(int)$user_id.'"');
		}
		if((int)$comp_id > 0){
			$query->where('bg.comp_id = "'.(int)$comp_id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function getAnswer($object){
		$response = null;
		if($object->question_no==1){
			$result = $this->getTeams($object->answer);
			$response = $result[0]->country;
		}
		if($object->question_no==2){
			$result = $this->getPlayers($object->answer);
			$response = $result[0]->player_name;
		}
		if($object->question_no==3){
			$result = $this->getGames($object->answer);
			for($i=0;$i<count($result);$i++){
				$response = $response . $result[$i]->country1." vs ".$result[$i]->country2."; ";
			}
			
		}
		if($object->question_no==4){
			$result = $this->getTeams($object->answer);
			$response = $result[0]->country;
		}
		if($object->question_no==5){
			$result = $this->getTeams($object->answer);
			for($i=0;$i<count($result);$i++){
				$response = $response . $result[$i]->country."; ";
			}
		}
		if($object->question_no==6){
			$result = $this->getTeams($object->answer);
			$response = $result[0]->country;
		}
		return $response;
	}

	public function getPlayers($id=null){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('p.*')
			->select('t.country, t.flag')
			->from($this->db->quoteName('#__ddc_players', 'p'))
			->leftJoin('#__ddc_player_team as pt on pt.player_id = p.id')
			->leftJoin('#__ddc_teams as t on t.id = pt.team_id')
			->group('p.id');
		if((int)$id > 0){
			$query->where('p.id = "'.(int)$id.'"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}

	public function getTeams($id=null, $comp_id=1){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('t.*')
			->from($this->db->quoteName('#__ddc_teams', 't'))
			->group('t.id')
			->where('t.rank < 999');
		if($id > 0){
			$query->where('t.id IN ('.$id.')');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}
}