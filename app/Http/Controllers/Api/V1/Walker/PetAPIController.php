<?php

namespace App\Http\Controllers\Api\V1\Walker;

use App\Http\Requests\API\CreatePetAPIRequest;
use App\Http\Requests\API\UpdatePetAPIRequest;
use App\Models\Pet;
use App\Repositories\PetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PetController
 * @package App\Http\Controllers\Api\V1
 */

class PetAPIController extends AppBaseController
{
    /** @var  PetRepository */
    private $petRepository;

    public function __construct(PetRepository $petRepo)
    {
        $this->petRepository = $petRepo;
    }
    public function getPetOfTheCompanyClient($id){
    	$user = \App\Models\User::find($id);
    	if($user->id){
    		$petAssigns = \App\Models\PetAssign::select('id','pet_id','company_id','walker_id')->where('company_id',$user->company_id)->get();
    		foreach ($petAssigns as $key => $petAssign) {
    			$petAssigns[$key]['pet']  = $petAssign->user();
    			if($petAssign->walker_id){
    				$petAssigns[$key]['walker'] = $petAssign->walker();
    			}else{
    				$petAssigns[$key]['walker'] = null;
    			}
    		}
    	}else{
    		$petAssigns = null;
    	}
    	return $this->sendResponse($petAssigns, 'Pets retrieved successfully');
    	
    }
}
