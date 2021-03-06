<?php /** * DataRaw * */
class DataRaw extends Eloquent
{	
	protected $table = "raw_data";	/*public function getBySiteName()	{		return $this->belongsTo('SiteNames','SiteID');	}*/


	public function scopeCustomFilters($query, $person_type, $gender, $age, $programme,$lcs)
	{
		//PERSON TYPE FILTER
		return $query->where(function($query) use ($person_type)
		{
			if($person_type == 0)
			{
				return $query;
			}
			else
			{
				return $query->where('PersonType', $person_type);
			}
		})
		//GENDER FILTER
		->where(function($query) use ($gender)
		{
			if($gender == 0)
			{
				return $query;
			}
			elseif(count($gender) == 1)
			{
				return $query->where('Gender', $gender[0]);
			}
			else
			{
				return $query->wherein('Gender', $gender);
			}
		})
		//AGE FILTER
		->where(function($query) use ($age)
		{
			if($age == 0)
			{
				return $query;
			}
			else
			{
				for ($i=0; $i < count($age); $i++) 
				{ 
					$query->orWhereBetween('Age', $age[$i]);
				}
				return $query;
			}
		})
		//PROGRAMME FILTER
		->where(function($query) use ($programme)
		{
			if($programme == 0)
			{
				return $query;
			}
			elseif(is_array($programme))
			{
				return $query->wherein('TemplateName', $programme);
			}
			else
			{
				return $query->where('TemplateName', $programme);
			}
		})
		//LC FILTER
		->where(function($query) use ($lcs)
		{
			if(count($lcs) == 1)
			{
				if($lcs[0] === 0)
				{
					return $query;
				}
				else
				{
					return $query->where('SiteID', $lcs[0]);
				}
			}
			else
			{
				return $query->wherein('SiteID', $lcs);
			}
		});
	}

} ?>