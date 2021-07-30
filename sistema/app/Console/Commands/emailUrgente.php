<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReminderEmailDigest;
use App\Mail\PedidosUrgentes;
use \Mailjet\Resources;

class emailUrgente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urgente:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Emails urgentes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $usuarios = ["raisotoprogra@gmail.com","cbastias@spsgroup.cl"];
      foreach($usuarios as $item){
        for ($i = 5; $i <= 9; $i++) {
            $pedidosCreadosUrgente = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
                $i
            ));
              if(!empty($pedidosCreadosUrgente)){
                $this->emailPedidoCreado($pedidosCreadosUrgente,$i,$item);
              }
        }
      }
    }

     private function emailPedidoCreado($pedidos,$tipoCorreo,$usuario)
    {
        foreach ($pedidos as $item) {
          if($tipoCorreo==5 && $item->idEstadoMail==5){
            $mensaje="El pedido ".$item->idPedido." esta atrasado para el cliente ".$item->emp_nombre;
            }elseif($tipoCorreo==6 && $item->idEstadoMail==6){
              $mensaje="Se ha suspendido el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }elseif($tipoCorreo==7 && $item->idEstadoMail==7){
              $mensaje="Se ha modificado el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }elseif($tipoCorreo==8 && $item->idEstadoMail==8){
              $mensaje="Se ha registrado la salida del Pedido ".$item->idPedido." perteneciente al cliente".$item->emp_nombre;
            }elseif($tipoCorreo==9 && $item->idEstadoMail==9){
              $mensaje="Se ha pasado a historico el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }

          Mail::to($usuario)->send(new PedidosUrgentes($mensaje));
        }
    }
}
