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
      //$usuarios = ["nbastias@spsgroup.cl","raisotoprogra@gmail.com"];
      $usuariosEjecutivoCredito = DB::Select('call spGetUsuarioPerfilesEmail(?)',array(11));
      $usuariosTransporte= DB::Select('call spGetUsuariosEmailSuspendidosTransporte()');
      $pedidosAtrasados = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
        5
      ));
      $pedidosSuspendidos = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
        6
      ));
      $pedidosModificados = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
        7
      ));
      $pedidosSalida = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
        8
      ));
      $pedidosHistorico = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
        9
      ));

   

      foreach($pedidosSuspendidos as $item){
        if(!empty($pedidosSuspendidos)){
            $usuarioAcargo=DB::Select('call spGetDatosUsuario(?)',array($item->idUsuarioEncargado));;
            foreach($usuarioAcargo as $item2){
              $this->emailPedidoCreado($pedidosSuspendidos,6,$item2);       
            }
        }
      }


   //tipo 7
      foreach($pedidosModificados as $item){
        if(!empty($pedidosModificados)){
            $usuarioBodegaAcargo=DB::Select('call spGetUsuariosEmailSuspendidosBodega(?)',array($item->idPlanta));;
            $this->emailPedidoCreado($pedidosModificados,7,$usuarioBodegaAcargo);       
        }
      }

      foreach($pedidosModificados as $item){
        if(!empty($pedidosModificados)){
            $usuarioAcargo=DB::Select('call spGetDatosUsuario(?)',array($item->idUsuarioEncargado));;
            foreach($usuarioAcargo as $item2){
              $this->emailPedidoCreado($pedidosModificados,7,$item2);       
            }
        }
      }	  


      //tipo 8
      foreach($pedidosSalida as $item){
        if(!empty($pedidosSalida)){
            $usuarioAcargo=DB::Select('call spGetDatosUsuario(?)',array($item->idUsuarioEncargado));;
            foreach($usuarioAcargo as $item2){
              $this->emailPedidoCreado($pedidosSalida,8,$item2);       
            }
        }
      }
      //tipo 9
      foreach($pedidosHistorico as $item){
        if(!empty($pedidosHistorico)){
            $usuarioAcargo=DB::Select('call spGetDatosUsuario(?)',array($item->idUsuarioEncargado));;
            foreach($usuarioAcargo as $item2){
              $this->emailPedidoCreado($pedidosHistorico,9,$item2);       
            }
        }
      }
          


      
    }

    private function emailPedidoCreado($pedidos,$tipoCorreo,$usuario)
    {
        $mensaje="";
        foreach ($pedidos as $item) {
          if($tipoCorreo==5 && $item->idEstadoMail==5){
            $mensaje=" Se ha creado el Pedido ".$pedido->idPedido."para el cliente ".$item->emp_nombre.", el cual debe ser despachado con URGENCIA Se solicita tu aprobación de crédito";
            }elseif($tipoCorreo==6 && $item->idEstadoMail==6){
              $mensaje="Se ha suspendido el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }elseif($tipoCorreo==7 && $item->idEstadoMail==7 && $item->idEstadoPedido==7){
              $mensaje="Se ha modificado el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }elseif($tipoCorreo==8 && $item->idEstadoMail==8){
              $mensaje="Estimado Usuario,Se ha registrado la salida del Pedido ".$item->idPedido." perteneciente al cliente".$item->emp_nombre;
            }elseif($tipoCorreo==9 && $item->idEstadoMail==9){
              $mensaje="Se ha pasado a historico el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo;
            }
          if($usuarios->correo_novedades=1){
          Mail::to($usuario->usu_email)->send(new PedidosUrgentes($mensaje,$usuario));
          }
        }
    }
}
