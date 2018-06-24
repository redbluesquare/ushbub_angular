<?php
namespace App\Models;
use App\Models\DefaultModel;
use Joomla\Application;
use Joomla\Github\Package\Authorization;
use Stripe\Charge;
use Stripe\Order;
use Stripe\Stripe;
use Stripe\Customer;
use Joomla\Crypt\Crypt;
use Joomla\Crypt\Password\Simple;

class UsergroupsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_token = 'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t';
	protected $_secretKey = 'sk_live_SyweUzmJZphKqKkaDeT1RtUq';
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('ugm.user_id, ugm.group_id, ugm.token, ugm.state')
			->select('ug.title')
			->from($this->db->quoteName('#__ddc_user_usergroup_map', 'ugm'))
			->rightJoin('#__ddc_usergroups as ug on ug.id = ugm.group_id')
  			->group('ugm.user_id, ugm.group_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $u_id, $g_id, $p_id)
	{
		if((int)$u_id > 0)
		{
			$query->where('ugm.user_id = '.(int)$u_id);
		}
		if((int)$g_id > 0)
		{
			$query->where('ugm.group_id = '.(int)$g_id);
		}
		if((int)$p_id > 0)
		{
			$query->where('ug.parent_id = '.(int)$p_id);
		}
		return $query;
	}

	public function getUsergroups($id = null, $title = null, $p_id = null){
		$query = $this->db->getQuery(true)
			->select('ug.id, ug.parent_id, ug.lft, ug.rgt, ug.title')
			->from('#__ddc_usergroups as ug');
		if(is_int($id) > 0){
			$query->where('ug.id = '.(int)$id);
		}
		if($title!=null){
			$query->where('ug.title = "'.$title.'"');
		}
		if((int)$p_id > 0)
		{
			$query->where('ug.parent_id = '.(int)$p_id);
		}
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		
		return $result;
	}

	public function getParticipants($id){
		$query = $this->db->getQuery(true)
			->select('u.first_name, u.username,ug.title,ug.id as group_id,ugm.state,sum(uguess.points) as points,u.id as user_id')
			->from('#__ddc_user_usergroup_map as ugm')
			->rightJoin('#__ddc_usergroups as ug on ug.id = ugm.group_id')
			->rightJoin('#__ddc_users as u on u.id = ugm.user_id')
			->leftJoin('#__ddc_userguesses as uguess on u.id = uguess.user_id')
			->group('ugm.user_id,ugm.group_id')
			->order('ugm.state, points DESC')
			->where('ugm.state >= 0');
		if((int)$id > 0)
		{
			$query->where('ugm.group_id = '.(int)$id);
		}
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		
		return $result;
	}
}