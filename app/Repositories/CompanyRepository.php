<?php

namespace App\Repositories;

use App\Models\Company;
use InfyOm\Generator\Common\BaseRepository;
use App\Events\CompanyInvitation;
use App\Models\User;
use App\Models\EmployeeRequest;
use App\Models\Notification;

class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address',
        'city_id',
        'country_id',
        'user_id',
        'postal',
        'description',
        'subscription_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }
    public function getCompaniesWithRating($id){
        $companies = Company::orderBy('id', 'DESC')->where('user_id',$id)->with('ratings')->first();
        return $companies;
    }

    public function getCompaniesOfUserHasMany($id){
        $company = Company::orderBy('id', 'DESC')->where('user_id',$id)->first();
        if($company){
            $company->country;
            $company->city;
            $company->ratings;
            $employees = $company->employees;
            foreach ($employees as $key => $employee) {
                $employee['avatar'] = $employee->getAvatar();
                $name = $employee->name($employee['id']);
                if($name){
                    $employee['name'] = $name;
                }else{
                    $employee['name'] = $employee['email'];
                }
                
            }
            if($company->ratings){
                foreach ($company->ratings as $key => $rating) {
                    $rating->user;
                }
            }
        }

        return $company;
    }

    public function getAllCompanies(){
        $companies = Company::orderBy('id', 'DESC')->with('country','city')->get();
        foreach ($companies as $key => $company) {
            $company->user;
        }

        return $companies;
    }
    public function addEmployee($attibutes){
        $employeeRequest = EmployeeRequest::create($attibutes);
        $sender = Company::find($attibutes['company_id']);
        if($sender){
            $notification = Notification::create([
                'content'=> 'Invitation from '.$sender->name. ' company !!',
                'status'=>false,
                'sender'=>$attibutes['company_id'],
                'receiver'=> $attibutes['user_id'],
                'type' => 'employee_request',
                'employee_request' => $employeeRequest->id
            ]);
        }
        event(new CompanyInvitation($notification));
        return $employeeRequest;
    }
    public function removeEmployee($attibutes){
        $remove = User::find($attibutes['id'])->update(['company_id'=>null]);
        return $remove;
    }
    public function sendInvitation($attibutes){
        
        $company = Company::find($attibutes['company_id']);
        $company->user;
        $data = [
            'email'     => $attibutes['email'],
            'company'   => $company->name,
            'company_id'=> $company->id,
            'owner'     => $company->user->email,
            'url'       => url('/api/v1/comfirmInvitationCompanyByEmail'),
        ];
        \Mail::send('mails.companyInvitation',$data, function ($masg) use ($attibutes) {
            $masg->to($attibutes['email'])
                ->subject('SYSTEM');
        });
        return $attibutes;
    }
}
