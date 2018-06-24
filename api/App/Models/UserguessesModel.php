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
		$query->select('ug.*')
			->select('t1.country as country1,t1.rank as rank1')
			->select('t2.country as country2,t2.rank as rank2')
			->select('g.id,g.game_no,g.group,g.game_date')
			->from($this->db->quoteName('#__ddc_userguesses', 'ug'))
			->leftJoin('#__ddc_games as g on g.id = ug.game_id')
			->leftJoin('#__ddc_teams as t1 on t1.id = g.team1')
			->leftJoin('#__ddc_teams as t2 on t2.id = g.team2')
			->group('ug.id')
			->where('ug.user_id = '.$this->input->get('user_id',0,'string'));
		return $query;
	}	
	protected function _buildWhere(&$query, $id1, $id2)
	{
		if((int)$id1 > 0)
		{
			$query->where('ug.id = "'.$id1.'"');
		}
		return $query;
	}

	public function sportscompusers($id){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('c.*')
			->select('u.first_name')
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

	public function compgames($id,$comp_id,$user_id){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('g.id,g.game_no,g.group,g.game_date')
			->select('t1.country as country1,t1.rank as rank1')
			->select('t2.country as country2,t2.rank as rank2')
			->select('ug.id as guess_id,ug.scoreA,ug.scoreB')
			->from($this->db->quoteName('#__ddc_games', 'g'))
			->leftJoin('#__ddc_teams as t1 on t1.id = g.team1')
			->leftJoin('#__ddc_teams as t2 on t2.id = g.team2')
			->rightJoin('#__ddc_userguesses as ug on (ug.game_id = g.id) AND (ug.user_id = '.$user_id.')')
			->group('g.id');
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
}