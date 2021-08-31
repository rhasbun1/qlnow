<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class PedidosUrgentes extends Mailable
{
    use Queueable, SerializesModels;

    protected $mensaje;
    protected $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mensaje,$usuario)
    {
        $this->mensaje = $mensaje;
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('formatosEmail.PedidosUrgentes')->with('mensaje', $this->mensaje)->with('usuario', $this->usuario);
    }
}
