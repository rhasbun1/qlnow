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
      /*
      //Usuarios
      $usuariosGerente=DB::Select('call spGetUsuarioPerfilesEmail(?)',array(
        11
      ));
      $TodosLosUsuarios=DB::Select('call spGetUsuarios()');
      $usuariosJefeBodegaPlanta=DB::Select('call spGetUsuarioJefePlantaBodegaCorreo()');
     
      //Pedidos
      $pedidosCreadosReciente = DB::Select('call spGetPedidosCorreo(?)',array(
        1
      ));

      $pedidosCreadosCliente = DB::Select('call spGetPedidosCorreo(?)',array(
        2
      ));

      $pedidosPlantaBodega = DB::Select('call spGetPedidosCorreo(?)',array(
        4
      ));

      //if(!empty($pedidosCreadosReciente)){
          $this->enviarPedidoRecienCreado($pedidosCreadosReciente,$usuariosGerente);
      //}
     // if(!empty($pedidosCreadosCliente)){
          $this->enviarPedidoCliente($pedidosCreadosCliente,$TodosLosUsuarios);
   //   }

         $this->enviarPedidoAprobadoJefeBodegaPlanta($pedidosPlantaBodega,$usuariosJefeBodegaPlanta);
        

        */
        for ($i = 1; $i <= 4; $i++) {
          $pedidosCreados = DB::Select('call spGetPedidosCorreo(?)',array(
              $i
          ));
          if(!empty($pedidosCreados)){
            $this->test($pedidosCreados,$i);
          }
      }
    }




    public function test($pedidos,$tipoCorreo){
      if($tipoCorreo==1){
          $mensaje="<table>
          <h2>Hola xxxx, tienes nuevos pedidos para ser aprobados de crédito. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos.</h2>
          <table id='tablaDetalle' class='table table-hover table-condensed table-responsive'>
              <th>id pedido</th>
              <th>Cliente<th>
          </thead>
          <tbody>";
      }elseif($tipoCorreo==2){
        $mensaje="<table>
          <h2>Hola, tu cliente  ha subido nuevos pedidos a QL now! y requieren tu atención. Ingresa por favor a qlnow.quimicalatinoamericana.cl para gestionarlos. </h2>
          <table id='tablaDetalle' class='table table-hover table-condensed table-responsive'>
              <th>id pedido</th>
              <th>Cliente<th>
          </thead>
          <tbody>";
      }elseif($tipoCorreo==3){
        $mensaje="<table>
          <h2>Pedidos aprobados para el cliente</h2>
          <table id='tablaDetalle' class='table table-hover table-condensed table-responsive'>
              <th>id pedido</th>
              <th>Cliente<th>
          </thead>
          <tbody>";
      }elseif($tipoCorreo==4){
        $mensaje="<table>
          <h2>Pedidos aprobados</h2>
          <table id='tablaDetalle' class='table table-hover table-condensed table-responsive'>
              <th>id pedido</th>
              <th>Cliente<th>
          </thead>
          <tbody>";
      }


        foreach ($pedidos as $item) {
             $mensaje=$mensaje."<tr><td>".$item->idPedido."</td><td>".$item->emp_nombre."</td></tr>";
        }
        $mensaje=$mensaje."</tbody>";

        

      $para=[];
      $para[] = [
               'Email' => "cbastias@spsgroup.cl",
               'name' => "cbastias@spsgroup.cl"
      ];

  

      $mj = new \Mailjet\Client('c189998f90a8e631ef61bdebb25de8bc','b98b35dd33194c6797b25e372664db71',true,['version' => 'v3.1']);
        $body = [
          'Messages' => [
            [
              'From' => [
                'Email' => "prueba@soporteportal.cl",
                'Name' => "prueba@soporteportal.cl"
              ],
              'To' => $para,
              'Subject' => "Pedidos creados en los ultimos 30 minutos.",
              'TextPart' => "Pedidos",
              'HTMLPart' => $mensaje,
              'CustomID' => "AppGettingStartedTest"
            ]
          ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }

}
