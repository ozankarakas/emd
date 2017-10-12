<?php 



/**
 * UserPreferences
 *
 */
class UserPreferences extends Eloquent

{	

	protected $table = "user_preferences_emd";

	protected $primaryKey = 'filter_id';

	protected $fillable = array('user_id','filters');

	public $timestamps = false;

}



?>