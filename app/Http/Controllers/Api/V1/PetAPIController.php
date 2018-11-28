<?php

namespace App\Http\Controllers\Api\V1;

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

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/pets",
     *      summary="Get a listing of the Pets.",
     *      tags={"Pet"},
     *      description="Get all Pets",
     *      produces={"application/json"},
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
     *                  @SWG\Items(ref="#/definitions/Pet")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->petRepository->pushCriteria(new RequestCriteria($request));
        $this->petRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pets = $this->petRepository->all();

        return $this->sendResponse($pets->toArray(), 'Pets retrieved successfully');
    }

    /**
     * @param CreatePetAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/pets",
     *      summary="Store a newly created Pet in storage",
     *      tags={"Pet"},
     *      description="Store Pet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Pet that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Pet")
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
     *                  ref="#/definitions/Pet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    
    

    public function store(CreatePetAPIRequest $request)
    {
        $input = $request->all();

        $pets = $this->petRepository->create($input);

        return $this->sendResponse($pets->toArray(), 'Pet saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/pets/{id}",
     *      summary="Display the specified Pet",
     *      tags={"Pet"},
     *      description="Get Pet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet",
     *          type="integer",
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
     *                  ref="#/definitions/Pet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Pet $pet */
        $pet = $this->petRepository->getPet($id);

        if (empty($pet)) {
            return $this->sendError('Pet not found');
        }

        return $this->sendResponse($pet->toArray(), 'Pet retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePetAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/pets/{id}",
     *      summary="Update the specified Pet in storage",
     *      tags={"Pet"},
     *      description="Update Pet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Pet that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Pet")
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
     *                  ref="#/definitions/Pet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($uuid, UpdatePetAPIRequest $request)
    {
        $input = $request->all();

        /** @var Pet $pet */
        $pet = $this->petRepository->findByField('UUID',$uuid)->first();

        if (empty($pet)) {
            return $this->sendError('Pet not found');
        }

        $pet = $this->petRepository->update($input, $pet->id);

        return $this->sendResponse($pet->toArray(), 'Pet updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/pets/{id}",
     *      summary="Remove the specified Pet from storage",
     *      tags={"Pet"},
     *      description="Delete Pet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet",
     *          type="integer",
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
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($uuid)
    {
        /** @var Pet $pet */
        $pet = $this->petRepository->findByField('UUID',$uuid)->first();

        $pet->delete();

        return $this->sendResponse($uuid, 'Pet deleted successfully');
    }

    
    public function getPets(Request $request,$userID){
        $this->petRepository->pushCriteria(new RequestCriteria($request));
        $this->petRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pets = $this->petRepository->getPets($userID);
        if (empty($pets)) {
            return $this->sendError('Pets not found');
        }
        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }

    public function getJumpsOfPet($id){
        $jumps = $this->petRepository->getJumpsOfPet($id);

        if(is_array($jumps) && !$jumps['success']){
            return $this->sendError($jumps['message']);
        }

        return $this->sendResponse($jumps->toArray(), 'Jumps retrieved successfully');
    }

    public function getNapsOfPet($id){
        $naps = $this->petRepository->getNapsOfPet($id);

        if(is_array($naps) && !$naps['success']){
            return $this->sendError($naps['message']);
        }

        return $this->sendResponse($naps->toArray(), 'Naps retrieved successfully');
    }

    public function getRollsOfPet($id){
        $rolls = $this->petRepository->getRollsOfPet($id);

        if(is_array($rolls) && !$rolls['success']){
            return $this->sendError($rolls['message']);
        }

        return $this->sendResponse($rolls->toArray(), 'Rolls retrieved successfully');
    }

    public function getSmilesOfPet($id){
        $smiles = $this->petRepository->getSmilesOfPet($id);

        if(is_array($smiles) && !$smiles['success']){
            return $this->sendError($smiles['message']);
        }

        return $this->sendResponse($smiles->toArray(), 'Smiles retrieved successfully');
    }

    public function getJumpsInTimeOfPet(Request $request,$id){

        if($request->input('day')){

            $input = $request->all();
            $input['startDay']= null;
            $input['endDay']= null;


        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $input['day']= null;

        }else{
            return $this->sendError('Item not found');
        }
        
        $jumps = $this->petRepository->getJumpsInTimeOfPet($input,$id);

        if(empty($jumps)){
            return $this->sendError('Item not found');
        }

        if(is_array($jumps) && !$jumps['success']){

            return $this->sendError($jumps['message']);
        }

        return $this->sendResponse($jumps->toArray(), 'Jumps retrieved successfully');
    }
    public function getNapsInTimeOfPet(Request $request,$id){

        if($request->input('day')){

            $input = $request->all();
            $input['startDay']= null;
            $input['endDay']= null;


        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $input['day']= null;

        }else{
            return $this->sendError('Item not found');
        }
        
        $naps = $this->petRepository->getNapsInTimeOfPet($input,$id);

        if(empty($naps)){
            return $this->sendError('Item not found');
        }

        if(is_array($naps) && !$naps['success']){

            return $this->sendError($naps['message']);
        }

        return $this->sendResponse($naps->toArray(), 'Naps retrieved successfully');
    }
    public function getRollsInTimeOfPet(Request $request,$id){

        if($request->input('day')){

            $input = $request->all();
            $input['startDay']= null;
            $input['endDay']= null;


        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $input['day']= null;

        }else{
            return $this->sendError('Item not found');
        }
        
        $rolls = $this->petRepository->getRollsInTimeOfPet($input,$id);

        if(empty($rolls)){
            return $this->sendError('Item not found');
        }

        if(is_array($rolls) && !$rolls['success']){

            return $this->sendError($rolls['message']);
        }

        return $this->sendResponse($rolls->toArray(), 'Rolls retrieved successfully');
    }
    public function getSmilesInTimeOfPet(Request $request,$id){

        if($request->input('day')){

            $input = $request->all();
            $input['startDay']= null;
            $input['endDay']= null;


        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $input['day']= null;

        }else{
            return $this->sendError('Item not found');
        }
        
        $smiles = $this->petRepository->getSmilesInTimeOfPet($input,$id);

        if(empty($smiles)){
            return $this->sendError('Item not found');
        }

        if(is_array($smiles) && !$smiles['success']){

            return $this->sendError($smiles['message']);
        }

        return $this->sendResponse($smiles->toArray(), 'Smiles retrieved successfully');
    }

    public function getPetLocation(Request $request,$userID){

        $pets = $this->petRepository->getPetLocation($userID);
        if (empty($pets)) {
            return $this->sendError('Pets not found');
        }
        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }
    public function getPetOfUserWithAvatar(Request $request,$id){
        $pets = $this->petRepository->getPetOfUserWithAvatar($id);
        if (empty($pets)) {
            return $this->sendError('Pets not found');
        }
        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }
    public function getAllPets(){
        $pets = $this->petRepository->getAllPets();

        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }

    public function getPetsNoFence($id){
        $pets = $this->petRepository->getPetsNoFence($id);
      
        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }

    public function getPetLocationPaginate(Request $request, $userId, $per_page = 10){

        $pets = $this->petRepository->getPetLocationPaginate($userId, $per_page);
        if (empty($pets)) {
            return $this->sendError('Pets not found');
        }

        return $this->sendResponse($pets, 'Pets retrieved successfully');
    }
    // NEW API GET ACTIVITY Pet
    // -- JUMPS --
    public function mergedJumpsOfPet(Request $request,$id){
        $input = $request->all();

        if($request->input('day')){
            $jumps = $this->petRepository->getJumpsByDay($input, $id);

        }elseif($request->input('startDay') && $request->input('endDay')){
            $jumps = $this->petRepository->getJumpsByRangeDay($input, $id);

        }elseif($request->input('month')){
            $jumps = $this->petRepository->getJumpsByMonth($input, $id);

        }elseif($request->input('startMonth') && $request->input('endMonth')){
            $jumps = $this->petRepository->getJumpsByRangeMonth($input, $id);

        }else{
            return $this->sendError('Parameter is wrong');

        }
        if (empty($jumps)) {
            return $this->sendError('Jumps not found');

        }
        return $this->sendResponse($jumps, 'Jumps retrieved successfully');
    }
    // -- NAPS --
    public function mergedNapsOfPet(Request $request,$id){
        $input = $request->all();

        if($request->input('day')){
            $jumps = $this->petRepository->getNapsByDay($input, $id);

        }elseif($request->input('startDay') && $request->input('endDay')){
            $jumps = $this->petRepository->getNapsByRangeDay($input, $id);

        }elseif($request->input('month')){
            $jumps = $this->petRepository->getNapsByMonth($input, $id);

        }elseif($request->input('startMonth') && $request->input('endMonth')){
            $jumps = $this->petRepository->getNapsByRangeMonth($input, $id);

        }else{
            return $this->sendError('Parameter is wrong');

        }
        if (empty($jumps)) {
            return $this->sendError('Naps not found');

        }
        return $this->sendResponse($jumps, 'Naps retrieved successfully');
    }
    // -- SMILES --
    public function mergedSmilesOfPet(Request $request,$id){
        $input = $request->all();

        if($request->input('day')){
            $jumps = $this->petRepository->getSmilesByDay($input, $id);

        }elseif($request->input('startDay') && $request->input('endDay')){
            $jumps = $this->petRepository->getSmilesByRangeDay($input, $id);

        }elseif($request->input('month')){
            $jumps = $this->petRepository->getSmilesByMonth($input, $id);

        }elseif($request->input('startMonth') && $request->input('endMonth')){
            $jumps = $this->petRepository->getSmilesByRangeMonth($input, $id);

        }else{
            return $this->sendError('Parameter is wrong');

        }
        if (empty($jumps)) {
            return $this->sendError('Smiles not found');

        }
        return $this->sendResponse($jumps, 'Smiles retrieved successfully');
    }
    // -- ROLLS --
    public function mergedRollsOfPet(Request $request,$id){
        $input = $request->all();

        if($request->input('day')){
            $jumps = $this->petRepository->getRollsByDay($input, $id);

        }elseif($request->input('startDay') && $request->input('endDay')){
            $jumps = $this->petRepository->getRollsByRangeDay($input, $id);

        }elseif($request->input('month')){
            $jumps = $this->petRepository->getRollsByMonth($input, $id);

        }elseif($request->input('startMonth') && $request->input('endMonth')){
            $jumps = $this->petRepository->getRollsByRangeMonth($input, $id);

        }else{
            return $this->sendError('Parameter is wrong');

        }
        if (empty($jumps)) {
            return $this->sendError('Rolls not found');

        }
        return $this->sendResponse($jumps, 'Rolls retrieved successfully');
    }
}
