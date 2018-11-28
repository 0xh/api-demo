<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class CityController extends Controller
{
	/**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/getAllCitiesOfCountry/{code}",
     *      summary="Get a listing of the Animals.",
     *      tags={"City"},
     *      description="Get all Cities from Country code",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="code",
     *          description="code of Country",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/City")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function getCities($code){
    	$cities = City::where('country_code',$code)->get();
    	return $cities;
    }
}
