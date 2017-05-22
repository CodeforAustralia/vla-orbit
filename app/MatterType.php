<?php
namespace App;

Class MatterType
{
	function MatterType($params)
	{
	
		$this->CreatedBy = $params['CreatedBy'];
		$this->CreatedOn = $params['CreatedOn'];
		$this->MatterTypeID = $params['MatterTypeID'];
		$this->MatterTypeName = $params['MatterTypeName'];
		$this->UpdatedBy = $params['UpdatedBy'];
		$this->UpdatedOn= $params['UpdatedOn'];	
	}
}

