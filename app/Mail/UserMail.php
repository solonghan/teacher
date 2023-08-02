<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Order;
use App\Models\Page;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $order;
    private $title;
    private $content;
    private $payment_des;
    public function __construct($user_id, $order_id, $title, $content)
    {
        $this->user = User::where('id',$user_id)->with('manage_user')->first();
        $this->order = Order::where("id", $order_id)->with('cart.items')->with('user')->first();
        $this->order = Order::status_str_mapping($this->order);
        $this->title = $title;
        $this->content = $content;

        $this->payment_des = $this->user->transaction_des();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.user_order')
                    ->subject($this->title)
                    ->with([
                        'order'       => $this->order,
                        'cart'        => $this->order->cart,
                        'title'       => $this->title,
                        'msg'         => $this->content,
                        'user'        => $this->user,
                        'payment_des' => $this->payment_des
                    ]);
    }
}
