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
      //$usuarios = ["nbastias@spsgroup.cl","raisotoprogra@gmail.com"]; 
      $todosUsuarios =  DB::Select('call spGetUsuarios()');
      $usuariosGerente = DB::Select('call spGetUsuarioPerfilesEmail(?)',array(2));
      $usuariosEjecutivoCredito = DB::Select('call spGetUsuarioPerfilesEmail(?)',array(11));

      $pedidosCreados = DB::Select('call spGetPedidosCorreo(?)',array(
        1
      ));
      $pedidosAprobadosParaCliente = DB::Select('call spGetPedidosCorreo(?)',array(
        3
      ));
      $pedidosAprobados = DB::Select('call spGetPedidosCorreo(?)',array(
        4
      ));
      //tipo 1
      foreach($usuariosGerente as $item){
        if(!empty($pedidosCreados)){
          $this->emailPedidoCreado($pedidosCreados,1,$item);
        }
      }
	  //tipo 2
      foreach($todosUsuarios as $item){
        $pedidosCreadoCliente = DB::Select('call spGetPedidosCorreoCreadoPorCliente(?)',array(
          $item->usu_codigo
        ));
        if(!empty($pedidosCreadoCliente)){
          $this->emailPedidoCreado($pedidosCreadoCliente,2,$item);
        }
      }
      //tipo 3
      foreach($usuariosGerente as $item){
        if(!empty($pedidosAprobadosParaCliente)){
          $this->emailPedidoCreado($pedidosAprobadosParaCliente,3,$item);
        }
      }
      //tipo 4
      foreach($usuariosEjecutivoCredito as $item){
        if(!empty($pedidosAprobados)){
          $this->emailPedidoCreado($pedidosAprobados,4,$item);
        }
      }
      



    
    }


    private function emailPedidoCreado($pedido,$id,$usuario)
    {

        if($id==1){
          $tipo="Hola ".$usuario->usu_nombre.", tienes nuevos pedidos para ser aprobados de crédito. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos";
        }elseif($id==2){
          $tipo="Hola ".$usuario->usu_nombre." tu cliente ha subido nuevos pedidos a QL now! y requieren tu atención. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos.";
        }elseif($id==3){
          $tipo="Pedidos aprobados para clientes";
        }elseif($id==4){
          $tipo="Pedidos aprobados";
        }
        if($usuario->correo_avisoDespacho=1){
        Mail::to($usuario->usu_email)->send(new enviarMailPedidoCreado($pedido,$tipo,$usuario));
        }
    }

}
