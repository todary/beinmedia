<?php

namespace App\Jobs;

use App\helpers\utility;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class send_email implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $subject;
    public $body;
    public $from;
    public $name;
    public $reply_to;
    public $reply_to_name;
    public $smtp_setting;

    public function __construct($email,$subject,$body,$from="",$name="",$reply_to="",$reply_to_name="",$smtp_setting=[])
    {
        $this->email=$email;
        $this->subject=$subject;
        $this->body=$body;
        $this->from=$from;
        $this->name=$name;
        $this->reply_to=$reply_to;
        $this->reply_to_name=$reply_to_name;
        $this->smtp_setting=$smtp_setting;
    }


    public function handle()
    {

        if(isset_and_array($this->smtp_setting)&&$this->smtp_setting["tested"]){
            $config = [
                'driver' => 'smtp',
                'host' => $this->smtp_setting["host"],
                'port'=>$this->smtp_setting["port"],
                'username' => $this->smtp_setting["username"],
                'password' => $this->smtp_setting["password"],
                'encryption'=>$this->smtp_setting["encryption"]
            ];

            if(filter_var($this->smtp_setting["username"], FILTER_VALIDATE_EMAIL)){
                $this->from=$this->smtp_setting["username"];
            }
            else{
                $this->from=$this->smtp_setting["user_email"];
            }


            \Config::set('mail', $config);

            try{
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();

                $this->send_to_email();
            }
            catch (\Swift_TransportException $exception){
                echo "error when sending to $this->email \n";

                return;
            }

        }
        else{
            $this->send_to_email();
        }

        echo "sent to $this->email \n";
    }

    public function send_to_email(){
        utility::send_email_to_custom(
            [$this->email],
            $this->body,
            $this->subject,
            $this->from,
            "",
            $this->name,
            $this->reply_to,
            $this->reply_to_name
        );
    }


}
