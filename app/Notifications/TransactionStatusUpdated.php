<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Transaction $transaction,
        public string $label
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'transaction',
            'transaction_id' => $this->transaction->id,
            'product_id' => $this->transaction->product_id,
            'status' => $this->transaction->status,
            'label' => $this->label,
        ];
    }
}
