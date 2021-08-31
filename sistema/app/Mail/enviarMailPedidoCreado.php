<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;


class enviarMailPedidoCreado extends Mailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pedido;
    protected $tipo;
    protected $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $pedido = $request->idPedido;      
        $tipo = $request->tipo;     
        $usuario = $request->usuario; 
    }

    public function __construct($pedido,$tipo,$usuario)
    {
        $this->pedido = $pedido;
        $this->tipo = $tipo;
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('formatosEmail.pedidoCreado')->with('pedido', $this->pedido)->with('tipo', $this->tipo)->with('usuario', $this->usuario);
    }
}
