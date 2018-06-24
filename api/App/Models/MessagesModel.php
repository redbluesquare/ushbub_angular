<?php
namespace App\Models;
use App\Models\DefaultModel;

class MessagesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('m.*')
			->select('uf.first_name as user_from')
			->select('ut.first_name as user_to')
			->from($this->db->quoteName('#__ddc_messages', 'm'))
			->leftjoin('#__ddc_users as uf on uf.id = m.user_id_from')
			->leftjoin('#__ddc_users as ut on ut.id = m.user_id_to')
			->group('m.id')
			->order('m.id DESC');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $from, $to, $group)
	{
		if((int)$id > 0)
		{
			$query->where('m.id = '.(int)$id);
		}
		if((int)$group > 0)
		{
			$query->where('m.group_id = '.$group);
		}
		
		return $query;
	}	
}