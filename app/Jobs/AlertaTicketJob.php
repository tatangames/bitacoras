<?php

namespace App\Jobs;

use App\Mail\AlertaTicketMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AlertaTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private array $data) {}

    public function handle(): void
    {
        // Correo
        Mail::to('no-reply@finca3pinos.com')->send(new AlertaTicketMail($this->data));

        // Notificación push (aquí la agregas después)
        // $this->enviarPush($this->data);
    }
}
