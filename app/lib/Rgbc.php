<?php

final class Rgac{
	
	public static function pass()
	{
		//超管
		if ($_SESSION['user']['type']===0)
			return true;
			
		//普管
		if ($_SESSION['user']['type']===1)
		{
			
		}
		
		//普用
		if ($_SESSION['user']['type']===2)
		{
			return true;	
		}
		
		return false;
	}	
	
	
}

?>