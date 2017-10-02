<?php

namespace App\Infrastructure\Repositories;

use App\Location;

class LocationRepository
{

	/**
	 * @var App\Location
	 */
	protected $model;

	/**
	 * LocationRepository constructor
	 * @param Location $model [description]
	 */
	public function __construct(Location $model)
	{
		$this->model = $model;
	}

	/**
	 * get all data from resource (support pagination)
	 * @param  Array $request 
	 * @return App\Location          
	 */
	public function all($request)
	{
		return $this->model->orderBy('created_at', 'desc')->offset($request['offset'])->limit($request['limit'])->get();
	}
}