<?php
namespace App\Validators;
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
class UserValidator extends LaravelValidator
{
    const RULE_LOGIN_FACEBOOK = "login_facebook";
    const RULE_RESET_PASSWORD = 'reset_password';
    const RULE_CHANGE_AVATAR = 'change_avatar';
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email' => 'required|email|unique:users|max:250',
            'password' => 'required|min:6|max:250'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users'
        ],
        UserValidator::RULE_LOGIN_FACEBOOK => [
            'first_name' => 'required',
            'email' => 'required|email|unique:users'
        ],
        UserValidator::RULE_RESET_PASSWORD => [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ],
        UserValidator::RULE_CHANGE_AVATAR => [
            'avatar' => 'required|file|image'
        ]
    ];
}