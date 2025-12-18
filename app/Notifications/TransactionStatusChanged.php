<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Transaction $transaction,
        public string $label
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'kind' => 'transaction_status',
            'transaction_id' => $this->transaction->id,
            'product_id' => $this->transaction->product_id,
            'status' => $this->transaction->status,
            'escrow_status' => $this->transaction->escrow_status,
            'label' => $this->label,
            'url' => route('products.show', $this->transaction->product_id),
        ];
    }
}
