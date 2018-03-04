<?php
namespace App\Models;
use App\Models\DefaultModel;

class SubscriptionsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('cd.*')
  			->from($this->db->quoteName('#__contact_details', 'cd'))
  			->group('cd.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $email, $catid)
	{
		if($id > 0)
		{
			$query->where('cd.id = '.$id);
		}
		if($email != null)
		{
			$query->where('cd.email_to = "'.$email.'"');
		}
		if($catid != null)
		{
			$query->where('cd.catid = "'.$catid.'"');
		}
		
		return $query;
	}	
}