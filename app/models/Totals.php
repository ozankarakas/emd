<?php 
class Totals extends Eloquent
{	
	protected $table = "total_counts";
	protected $primaryKey = 'name';
	protected $fillable = array('name','count');
	public $timestamps = false;

}

