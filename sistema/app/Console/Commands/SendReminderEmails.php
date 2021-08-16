<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReminderEmailDigest;
use App\Mail\enviarMailPedidoCreado;
use \Mailjet\Resources;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'reminder:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recordatorio correos';

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
      $usuarios = ["raisotoprogra@gmail.com","nbastias@spsgroup.cl"];
      foreach($usuarios as $item){
        for ($i = 1; $i <= 4; $i++) {
          $pedidosCreados = DB::Select('call spGetPedidosCorreo(?)',array(
              $i
          ));
          if(!empty($pedidosCreados)){
            $this->emailPedidoCreado($pedidosCreados,$i,$item);
          }
        }
      }
    }


    private function emailPedidoCreado($pedido,$id,$usuario)
    {
        if($id==1){
          $tipo="Hola xxxx, tienes nuevos pedidos para ser aprobados de crédito. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos";
        }elseif($id==2){
          $tipo="Hola, tu cliente  ha subido nuevos pedidos a QL now! y requieren tu atención. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos.";
        }elseif($id==3){
          $tipo="Pedidos aprobados para el cliente";
        }elseif($id==4){
          $tipo="Pedidos aprobados";
        }

        Mail::to($usuario)->send(new enviarMailPedidoCreado($pedido,$tipo));
    }

}
