<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReminderEmailDigest;
use App\Mail\enviarMailPedidoCreado;
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
        for ($i = 5; $i <= 9; $i++) {
            $pedidosCreadosUrgente = DB::Select('call spGetPedidosCorreoUrgente(?)',array(
                $i
            ));
              if(!empty($pedidosCreadosUrgente)){
                $this->test($pedidosCreadosUrgente,$i);
              }
        }
    }

    public function test($pedidos,$tipoCorreo){
      $mensaje="";
      $para=[];
      $para[] = [
        'Email' => "cbastias@spsgroup.cl",
        'name' => "cbastias@spsgroup.cl"
      ];
        foreach ($pedidos as $item) {
            if($tipoCorreo==5 && $item->idEstadoMail==5){
            $mensaje="<h2>El pedido ".$item->idPedido." esta atrasado para el cliente ".$item->emp_nombre."</h2>";
            }elseif($tipoCorreo==6 && $item->idEstadoMail==6){
              $mensaje="<h2>Se ha suspendido el pedido".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo."</h2>";
            }elseif($tipoCorreo==7 && $item->idEstadoMail==7){
              $mensaje="<h2>Se ha modificado el pedido".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo."<h2>";
            }elseif($tipoCorreo==8 && $item->idEstadoMail==8){
              $mensaje="<h2>Se ha registrado la salida del Pedido ".$item->idPedido." perteneciente al cliente".$item->emp_nombre."</h2>";
            }elseif($tipoCorreo==9 && $item->idEstadoMail==9){
              $mensaje="<h2>Se ha suspendido el pedido ".$item->idPedido." para el cliente ".$item->emp_nombre." por el motivo ".$item->motivo."</h2>";
            }
            $mj = new \Mailjet\Client('c189998f90a8e631ef61bdebb25de8bc','b98b35dd33194c6797b25e372664db71',true,['version' => 'v3.1']);
            $body = [
              'Messages' => [
                [
                  'From' => [
                    'Email' => "prueba@soporteportal.cl",
                    'Name' => "prueba@soporteportal.cl"
                  ],
                  'To' => $para,
                  'Subject' => "Pedido urgente",
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
}
