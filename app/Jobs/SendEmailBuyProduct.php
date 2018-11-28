<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;

class SendEmailBuyProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $order;
    protected $carts;
    protected $sum;

    public function __construct($order,$carts,$sum)
    {
        $this->order = $order;
        $this->carts = $carts;
        $this->sum = $sum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $admins = User::where('role', 1)->get();

        $mailContent = array(
            'order' => $this->order,
            'user' => $this->order->user->profile,
            'carts' => $this->carts,
            'sum' => $this->sum
        );


        Mail::send('email.checkout.checkout',$mailContent, function($message){
            $message->to($this->order->user->email)->subject('Subject');
        });

        foreach($admins as $admin){
            Mail::send('email.checkout.checkoutAdmin',$mailContent, function($message) use($admin){
                $message->to($admin->email)->subject('Subject');
            });
        }
    }
}
