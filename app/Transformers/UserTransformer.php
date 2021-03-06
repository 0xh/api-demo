<?php
namespace App\Transformers;
use League\Fractal\TransformerAbstract;
use App\User;
class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
             'id'      => (int) $user->id,
             'name'   => $user->name,
             'email' => $user->email,
             'token_id' => $user->token_id,
             'created_at' => $user->created_at->format('c'),
             'updated_at' => $user->updated_at->format('c')
         ];
    }
}