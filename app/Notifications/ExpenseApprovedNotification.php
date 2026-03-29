<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseApprovedNotification extends Notification
{
    use Queueable;

    public $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct($expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rendición de Gastos Aprobada - ' . $this->expense->title)
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Te informamos que tu rendición de gastos por el monto de S/ ' . number_format($this->expense->total, 2) . ' ha sido APROBADA.')
            ->line('En breve, nuestra área de tesorería procederá con la liquidación respectiva.')
            ->action('Ver Detalle', route('expenses.show', $this->expense->id))
            ->line('Gracias por tu gestión.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
