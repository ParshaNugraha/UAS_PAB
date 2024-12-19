<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionEndedNotification extends Notification
{
    use Queueable;

    protected $auction;

    public function __construct($auction)
    {
        $this->auction = $auction;
    }

    public function via($notifiable)
    {
        return ['mail']; // Anda bisa menambahkan 'database' atau 'broadcast' jika diperlukan
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lelang Telah Berakhir')
            ->line('Lelang untuk barang: ' . $this->auction->nama_barang . ' telah berakhir.')
            ->action('Lihat Detail Lelang', url('/lelangs/' . $this->auction->id))
            ->line('Terima kasih telah berpartisipasi dalam lelang kami!');
    }

    // Jika Anda ingin menyimpan notifikasi ke database
    public function toArray($notifiable)
    {
        return [
            'auction_id' => $this->auction->id,
            'nama_barang' => $this->auction->nama_barang,
        ];
    }
}
