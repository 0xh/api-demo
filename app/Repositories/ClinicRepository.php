<?php

namespace App\Repositories;

use App\Models\Clinic;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\ClinicHasService;

class ClinicRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'address',
        'phone'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Clinic::class;
    }
    public function createClinic(array $attributes){
        $clinic = Clinic::create($attributes);
        if(count($attributes['services'])){
            foreach ($attributes['services'] as $key => $service) {
                $clinic->hasServices()->create([
                'clinic_service_id' => $service['id'],
                ]);
            }
        }
        $clinicReturns = $clinic->hasServices;
        if($clinicReturns){
            foreach ($clinicReturns as $key => $clinicReturn) {
                $clinicReturn->service;
            }
        }
        return $clinic;
    }
    public function getClinics(){
        $clinics = Clinic::orderBy('id','DESC')->get();
        foreach ($clinics as $key => $clinic) {
            $hasServices = $clinic->hasServices;
            if(!empty($hasServices->toArray())){
                foreach ($hasServices as $key => $hasService) {
                    $service =  $hasService->service;
                }
            }
        }
        return $clinics;
    }
    public function updateClinic(array $attributes,$id){
        $del = ClinicHasService::where('clinic_id',$id)->delete();
        $clinic = Clinic::find($id);
        $clinic->update($attributes);
        if(count($attributes['services'])){
            foreach ($attributes['services'] as $key => $service) {
                $clinic->hasServices()->create([
                'clinic_service_id' => $service['id'],
                ]);
            }
        }
        $clinicReturns = $clinic->hasServices;
        if($clinicReturns){
            foreach ($clinicReturns as $key => $clinicReturn) {
                $clinicReturn->service;
            }
        }
        return $clinic;
    }
    public function deleteClinic($id){
        $del = ClinicHasService::where('clinic_id',$id)->delete();
        $clinic = Clinic::find($id)->delete();
        return $clinic;
    }
    public function showClinic($id){
        $clinic = Clinic::find($id);
        $hasServices = $clinic->hasServices;
        $ratings = $clinic->ratings;
        if($ratings){
            foreach ($ratings as $key => $rating) {
                $rating->user;
            }
        }
        if($hasServices){
            foreach ($hasServices as $key => $hasService) {
                $hasService->service;
            }
        }
        return $clinic;
    }
}
