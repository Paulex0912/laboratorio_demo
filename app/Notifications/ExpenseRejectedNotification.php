<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseRejectedNotification extends Notification
{
    use Queueable;

    public $expense;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($expense, $reason)
    {
        $this->expense = $expense;
        $this->reason = $reason;
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
            ->subject('Rendición de Gastos Rechazada - ' . $this->expense->title)
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu rendición de gastos por S/ ' . number_format($this->expense->total, 2) . ' ha sido rechazada por el siguiente motivo:')
            ->line('"' . $this->reason . '"')
            ->line('Por favor, revisa tus comprobantes o corrige lo necesario y vuelve a enviar.')
            ->action('Ver Rendición', route('expenses.show', $this->expense->id));
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
