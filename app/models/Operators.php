<?php 



/**
 * Operators
 *
 */
class Operators extends Eloquent

{	

	protected $table = "f_operator";

    public function sites()
    {
    	return $this->hasMany('SiteName', 'operator_id');
    }

}



?>