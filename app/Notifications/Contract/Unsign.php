<?php

namespace App\Notifications\Contract;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Unsign extends Notification
{
    use Queueable;

    protected $contract;
    protected $c1;
    protected $c2;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contract, $c1, $c2)
    {
        $this->contract = $contract;
        $this->c1 = $c1;
        $this->c2 = $c2;
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
            ->subject('Расторжение договора между ' .
                $this->c1->title . ' и ' .
                $this->c2->title . ' редакция №' . $this->contract->contractTemplate->version / 10)
            ->greeting('Расторжение договора между ' .
                $this->c1->title . ' и ' .
                $this->c2->title . ' редакция №' . $this->contract->contractTemplate->version / 10)
            ->line($this->c1->title . ' уведомляет ' . $this->c2->title . ' о расторжении ' . $this->contract->contractTemplate->title . ' в редакции № ' . $this->contract->contractTemplate->version / 10)
            ->action('Перейти', "https://project.cross-contract.ru/personal/contracts" . $this->contract->id);
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
