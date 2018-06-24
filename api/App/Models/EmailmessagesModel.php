<?php
namespace App\Models;
use App\Models\DefaultModel;

class EmailmessagesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('em.*')
  			->from($this->db->quoteName('#__ddc_email_messages', 'em'))
  			->group('em.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id)
	{
		if($id > 0)
		{
			$query->where('em.id = '.$id);
		}
		
		return $query;
	}	
}