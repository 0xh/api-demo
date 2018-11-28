<?php

namespace App\Http\Controllers\Api\V1\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Repositories\CompanyRepository;
use App\Http\Requests\API\EmployeeAPIRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\EmployeeRequest;
use App\Models\Notification;
class CompanyAPIController extends AppBaseController
{
	private $companyRepository;

    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepository = $companyRepo;
    }
    public function addEmployee(EmployeeAPIRequest $request){
    	$input = $request->all();
        $receiver = User::where('email',$input['email'])->first();
        if($receiver){
            if($receiver->company_id == $input['company_id']){
                return $this->sendResponseWithCode([],201);
            }else{
                $find_request = EmployeeRequest::where('company_id',$input['company_id'])->where('user_id',$receiver['id'])->first();
                if($find_request){
                    return $this->sendResponseWithCode([],202);
                }else{
                    $input['user_id'] = $receiver['id'];
                    $invitation = $this->companyRepository->addEmployee($input);
                }
                
            }
        }else{
            $invitation = $this->companyRepository->sendInvitation($input);
            
            return $this->sendResponse($invitation, 'Convitation retrieved successfully');
        }
        return $this->sendResponse($invitation, 'Convitation retrieved successfully');
    }
    public function confirmInvitationCompany(Request $request){
        $input = $request->all();

        // return $input;
        if($input['accept']){

            $noti = Notification::find($input['notification_id'])->delete();

            $employee_request = EmployeeRequest::find($input['employee_request']);

            $user = User::find($employee_request->user_id)->update(['company_id'=> $employee_request->company_id]);

            $employee_request->delete();
            // event(new AcceptTrueFriend($noti));

            return array(
                'accept' => true,
            );

        }else{

            $noti = Notification::find($input['notification_id'])->delete();
            EmployeeRequest::find($input['employee_request'])->delete();
            return array(
                'accept' => false,
            );
        }
    }
    private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $str .= $keyspace[random_int(0, $max)];
            }
            return $str;
        }
    public function comfirmInvitationCompanyByEmail(Request $request){
        // dd(str_randum(23));
        $input = $request->all();
        if($input['accept']){
            $check = User::where('email',$input['email'])->first();
            if($check){
                return $this->sendResponse($check, 'The user already exists'); 
            }else{
                $random_password = $this->random_str(12);
                $createUser = User::create([
                        'email'         => $input['email'],
                        'password'      => bcrypt($random_password),
                        'company_id'    => $input['company_id']
                    ]);
                $account = ['email'=>$input['email'],'password'=> $random_password];
                \Mail::send('mails.create_user',$account, function ($masg) use ($input) {
                    $masg->to($input['email'])
                        ->subject('SYSTEM');
                });
            }
            
        }
        return $this->sendResponse($createUser, 'Create user successfully');
    }
    public function removeEmployee(Request $request){
    	$input = $request->all();
    	$remove = $this->companyRepository->removeEmployee($input);
    	return $this->sendResponse($remove, 'Remove employee successfully');
    }
}
