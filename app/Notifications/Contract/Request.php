<?php

namespace App\Notifications\Contract;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Request extends Notification
{
    use Queueable;

    protected $contract;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->contract->contragent->title . ' запрашивает разрешение на подписание договора с ' . 
            $this->contract->contractTemplate->contragent->title . '.')
            ->greeting($this->contract->contragent->title . ' запрашивает разрешение на подписание договора с ' . 
            $this->contract->contractTemplate->contragent->title . '.')
            ->action('Перейти', "http://project.cross-contract.ru/personal/contracts" . $this->contract->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
