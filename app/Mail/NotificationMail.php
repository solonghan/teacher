<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;
use App\Models\Order;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    private $member;
    private $type;
    private $relation_id;
    private $title;
    private $content;
    public function __construct($member_id, $type, $relation_id, $title, $content)
    {
        $this->member = Member::find($member_id);
        $this->type = $type;
        $this->relation_id = $relation_id;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type == 'new_user'){
            return $this->view('mail.text')
                        ->subject($this->title)
                        ->with([
                            'title' =>  $this->title,
                            'msg'   =>  $this->content
                        ]);;
        }else{
            $order = Order::where("id", $this->relation_id)->with('cart.items')->with('user')->first();
            $msg = "";
            return $this->view('mail.order')
                        ->subject($this->title)
                        ->with([
                            'order' =>  $order,
                            'cart'  =>  $order->cart,
                            'title' =>  $this->title,
                            'msg'   =>  $this->content,
                            'user'  =>  $order->user
                        ]);;
        }
    }
}
