<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Pedido;
use SoapClient;
use File;
use \Mailjet\Resources;
use App\Producto;
use App\Unidad;
use App\Planta;
use App\Parametro;
use App\Console\Commands\SendReminderEmails;
use Illuminate\Support\Facades\Mail;
use App\Mail\enviarMailPedidoCreado;


class PedidoController extends Controller
{
    //
    public function index($idNotaVenta)
    {
        //
        $NotadeVenta=DB::Select('call spGetNotaVenta(?)', array($idNotaVenta) );
        $NotadeVentaDetalle=DB::Select('call spGetNotaVentaDetalle(?)', array($idNotaVenta) );
        $param=Parametro::All();
        $plantas=DB::table('plantas')->select('idPlanta', 'nombre')->get();
        $formaEntrega=DB::table('formasdeentrega')->select('idFormaEntrega', 'nombre')->get();
        $parametros=DB::table('parametros')->select('iva', 'maximo_toneladas_por_pedido', 'maximo_productos_por_pedido', 'notaPedido1', 'notaPedido2','consideracionesPedidosGranel','version')->get();
        return view('gestionarpedido')->with('NotadeVenta', $NotadeVenta)
                                      ->with('NotadeVentaDetalle', $NotadeVentaDetalle)
                                      ->with('FormasdeEntrega', $formaEntrega)
                                      ->with('Plantas', $plantas)
                                      ->with('parametros', $parametros)
                                      ->with('param', $param);
    }


    public function detalleNotaVenta(Request $datos){
        if($datos->ajax()){
            $NotadeVentaDetalle=DB::Select('call spGetNotaVentaDetalle(?)', array($datos->input('idNotaVenta')) );
            return $NotadeVentaDetalle;
        }
    }
    public function traerPlantaNotaVenta(Request $datos){
        if($datos->ajax()){
            $NotadeVentaDetalle=DB::Select('call spGetNotaVentaDetalleporpedido(?,?)', array($datos->input('idNotaVenta'),$datos->input('idProducto')) );
            return $NotadeVentaDetalle;
        }
    }
    

    public function clienteGestionarPedido($idNotaVenta)
    {
        //
        $NotadeVenta=DB::Select('call spGetNotaVenta(?)', array($idNotaVenta) );
        $NotadeVentaDetalle=DB::Select('call spGetNotaVentaDetalle(?)', array($idNotaVenta) );
        $plantas=DB::table('plantas')->select('idPlanta', 'nombre')->get();
        $formaEntrega=DB::table('formasdeentrega')->select('idFormaEntrega', 'nombre')->get();
        $parametros=DB::table('parametros')->select('iva', 'maximo_toneladas_por_pedido', 'maximo_productos_por_pedido', 'notaPedido1', 'notaPedido2')->get();
        return view('cliente_gestionarpedido')->with('NotadeVenta', $NotadeVenta)
                                      ->with('NotadeVentaDetalle', $NotadeVentaDetalle)
                                      ->with('FormasdeEntrega', $formaEntrega)
                                      ->with('Plantas', $plantas)
                                      ->with('parametros', $parametros);
    }

    // Vista por pedido para los usuarios Ejecutivos de Crédito    
    public function listaPedidos(){
        if( Session::get('idPerfil')=='11' ){
            $pedidos=DB::Select('call spGetProductosconPedidoPendiente(?,?,?)', array(0, Session::get('idUsuario'), Session::get('idPerfil')  ) );
        }else{
            $pedidos=DB::Select('call spGetProductosconPedidoPendiente(?,?,?)', array(0, Session::get('idUsuario'), Session::get('idPerfil')  ) );
        }
        
        $pedidosIngresoCliente=DB::Select('call spGetpedidosIngresadosporClientesSinAprobar');
        $pedidosAgrupados = DB::Select('call spGetpedidosIngresadosporClientesSinAprobarCount');
        
        $cantidadIngresoCliente=count($pedidosAgrupados);



        $parametros=DB::table('parametros')->select('version')->get();
        return view('listaPedidos')->with('pedidos', $pedidos)
                                    ->with('cantidadIngresoCliente', $cantidadIngresoCliente)
                                    ->with('parametros', $parametros);    	
    }

    public function historicoPedidos(){
        $fecha_termino = date('Y-m-d'); 
        $fecha_inicio = date("Y-m-d",strtotime($fecha_termino."- 60 days"));
        if( Session::get('empresaUsuario')!='0' ){
            $clientes=DB::table('empresas')->select('emp_codigo', 'emp_nombre')->where('emp_codigo',"=",Session::get('empresaUsuario') )->orderBy(DB::raw("TRIM(emp_nombre)"))->get();
        }else{
            $clientes=DB::Select('call spGetClientesPlantas(?)', array( Session::get('idUsuario') ) );
        }

        if( Session::get('idPerfil')=='14' or Session::get('idPerfil')=='15' ){
            $pedidos=DB::Select('call spGetHistoricoPedidoDetalleCliente(?,?,?,?,?,?,?,?,?,?,?)', array($fecha_inicio, $fecha_termino, 0,0,0,0,0,0, Session::get('idUsuario'), Session::get('idPerfil'), 1 ) );
         }else {

            if( Session::get('idPerfil')=='11' ){
                $pedidos=DB::Select('call spGetHistoricoPedidoDetalle(?,?,?,?,?,?,?,?,?,?,?)', array($fecha_inicio, $fecha_termino, Session::get('empresaUsuario'), 0, 0,0,0,0, Session::get('idUsuario'), Session::get('idPerfil'), 1 ) );
            }else{
                $pedidos=DB::Select('call spGetHistoricoPedidoDetalle(?,?,?,?,?,?,?,?,?,?,?)', array($fecha_inicio, $fecha_termino, Session::get('empresaUsuario'), Session::get('idPlanta'), 0,0,0,0, Session::get('idUsuario'), Session::get('idPerfil'), 1 ) );
            }    

        }   

        $parametros=DB::table('parametros')->select('version')->get();     
        $plantas=DB::Select('call spGetUsuarioPerfilPlantas(?,?)', array( Session::get('idUsuario'), Session::get('idPerfil') ));

        return view('historicoPedidos')->with('pedidos', $pedidos)
                                    ->with('parametros', $parametros)
                                    ->with('clientes', $clientes)
                                    ->with('plantas', $plantas)
                                    ->with('fecInicio', $fecha_inicio)
                                    ->with('fecTermino', $fecha_termino);     
    }


    public function obtenerHistoricoPedidos(Request $datos){
        $fechaInicio=strtotime( str_replace('/', '-', $datos->input("salidaDesde") ) );
        $fechaTermino=strtotime( str_replace('/', '-', $datos->input("salidaHasta") ) );
        $fechaInicio = date('Y-m-d', $fechaInicio);
        $fechaTermino = date('Y-m-d', $fechaTermino); 

        if( Session::get('idPerfil')=='14' or Session::get('idPerfil')=='15' ){
            $pedidos=DB::Select('call spGetHistoricoPedidoDetalleCliente(?,?,?,?,?,?,?,?,?,?,?)', array($fechaInicio, $fechaTermino, 0, 0, $datos->input("pedidoDesde"), $datos->input("pedidoHasta"), $datos->input("guiaDesde"), $datos->input("guiaHasta"), Session::get('idUsuario'), Session::get('idPerfil'), $datos->input("opcion") ) );
         }else {               
            if( Session::get('idPerfil')=='11' ){
                $pedidos=DB::Select('call spGetHistoricoPedidoDetalle(?,?,?,?,?,?,?,?,?,?,?)', array($fechaInicio, $fechaTermino, $datos->input("emp_codigo"), $datos->input("idPlanta"), $datos->input("pedidoDesde"), $datos->input("pedidoHasta"), $datos->input("guiaDesde"), $datos->input("guiaHasta"), Session::get('idUsuario'), Session::get('idPerfil'), $datos->input("opcion") ) );
            }else{
                $pedidos=DB::Select('call spGetHistoricoPedidoDetalle(?,?,?,?,?,?,?,?,?,?,?)', array($fechaInicio, $fechaTermino, $datos->input("emp_codigo"), 
                    $datos->input('idPlanta'), $datos->input("pedidoDesde"), $datos->input("pedidoHasta"), $datos->input("guiaDesde"), $datos->input("guiaHasta"), Session::get('idUsuario'), Session::get('idPerfil'), $datos->input("opcion") ) );
            }
        }      
        return $pedidos; 
    }



    // Vista para Pre Aprobar pedidos ingresados por Clientes  
    public function listaIngresosClienteporAprobar(){
        $pedidos=DB::Select('call spGetpedidosIngresadosporClientesSinAprobar');
        return view('listaIngresosClienteporAprobar')->with('pedidos', $pedidos);          
    }

    public function listaPedidosDetallado(){
        $pedidos=DB::Select('call spGetProductosconPedidoPendiente(?)', array(0) );
        $pedidosIngresoCliente=DB::Select('call spGetpedidosIngresadosporClientesSinAprobar');
        $cantidadIngresoCliente=count($pedidosIngresoCliente);
        $parametros=DB::table('parametros')->select('version')->get();
        return view('listaPedidos')->with('pedidos', $pedidos)
                ->with('cantidadIngresoCliente', $cantidadIngresoCliente)
                ->with('parametros', $parametros);
    }    
 

    // Vista para Aprobar pedidos por Usuarios Comerciales  
    public function clientePedidos(){
        $parametros=DB::table('parametros')->select('version')->get();
        $pedidos=DB::Select('call spGetPedidosClientedash(?)', array( Session::get('idUsuario') ) );
        $listaPedidoSinAprobarClientes=DB::Select('call spGetPedidoSinAprobarClientes(?)', array(Session::get('idUsuario')));

        return view('cliente_pedidos')->with('pedidos', $pedidos)->with('listaPedidoSinAprobarClientes',$listaPedidoSinAprobarClientes)->with('parametros',$parametros);  
    }    

    public function programacion(){
        $pedidos=DB::Select('call spGetProductosconPedidoPendiente(?,?,?)', array(0,Session::get('idUsuario' ), Session::get('idPerfil')  ) );
        $parametros=DB::table('parametros')->select('version')->get();
        return view('programacion')->with('pedidos', $pedidos)->with('parametros', $parametros);     
    }



    public function productosconPedidoPendientePorFechaEntrega(Request $datos){
        if($datos->ajax()){ 
            $pedidos=DB::Select('call spGetProductosconPedidoPendientePorFechaEntrega(?,?,?,?,?,?)', array(0,Session::get('idPlanta' ), $datos->input('fechaInicio'), $datos->input('fechaTermino'), Session::get('idUsuario' ), Session::get('idPerfil')  ) );
            return $pedidos;
        }
    }

    public function productosconPedidoPendientePorFechaCarga(Request $datos){
        if($datos->ajax()){ 
            $pedidos=DB::Select('call spGetProductosconPedidoPendientePorFechaCarga(?,?,?,?,?,?)', array(0,Session::get('idPlanta' ), $datos->input('fechaInicio'), $datos->input('fechaTermino'), Session::get('idUsuario' ), Session::get('idPerfil')  ) );
            return $pedidos;
        }
    }


    /* Este procedimiento esta obsoleto*/
    public function pedidosEnProceso(){
        $pedidos=DB::Select('call spGetProductosconPedidoPendiente(?)', array(0) );
        $parametros=DB::table('parametros')->select('version')->get();
        return view('pedidosEnProceso')->with('pedidos', $pedidos)->with('parametros', $parametros);    
    }    


    public function AprobarPedidos(){
        $listaPedidos=DB::Select('call spGetAprobarPedidos');
        $parametros=DB::table('parametros')->select('version')->get();
        return view('aprobarpedidos')->with('pedidos', $listaPedidos)->with('parametros', $parametros);
    } 

     public function aprobarPedidoCliente(Request $datos){
        if($datos->ajax()){
            $pedido=DB::Select("call spUpdAprobarPedidoCliente(?, ?)", array( $datos->input('idPedido'), Session::get('idUsuario') ) );
            return response()->json([
                "identificador" => $pedido[0]->idPedido
            ]);
        }
    }    


    public function aprobarPedido(Request $datos){
        if($datos->ajax()){
            $pedido=DB::Select("call spUpdAprobarPedido(?,?)", array( $datos->input('idPedido'), Session::get('idUsuario')  ));
         //   $this->avisoPedidoAprobado($datos->input('idPedido'));                         
            return response()->json([
                "identificador" => $pedido[0]->idPedido
            ]);
        }
    }

    public function desaprobarPedido($idPedido){
        $pedido=DB::Select('call spUpdDesaprobarPedido(?,?)', array( $idPedido, Session::get('idUsuario') ) );      
        return redirect('listarPedidos');
    }

    public function suspenderPedido(Request $datos){
        if($datos->ajax()){
            $pedido=DB::Select('call spUpdSuspenderPedido(?,?,?)', array( $datos->input('idPedido'), $datos->input('motivo'), Session::get('idUsuario') ) );
            $this->correoPedidoSuspendido( $datos->input('idPedido'), $datos->input('motivo'), $pedido[0]->usu_correo );

            return response()->json([ 
                        "url" => asset('/').'listarPedidos'
                    ]);
        }   
        
    }

    public function cerrarPedido(Request $datos){

        if($datos->ajax()){

            $pedido=DB::Select('call spUpdCerrarPedido(?,?,?)', array( $datos->input('idPedido'), Session::get('idUsuario'), $datos->input('motivo') ) ); 

            if($pedido[0]->cancelado==1){
                $this->correoPedidoSuspendido( $datos->input('idPedido'), $datos->input('motivo'), $pedido[0]->usu_correo );
            }
            

            return $pedido;
        }

    }

    public function editarPedido($idPedido){
        $pedido=DB::Select('call spGetPedido(?)', array($idPedido) );
        $listaDetallePedido=DB::Select('call spGetPedidoDetalle(?)', array($idPedido) );
        $log = DB::Select('call spGetPedidoLog(?)', array($idPedido) );
        $emptransporte = DB::table('empresastransporte')->select('idEmpresaTransporte','nombre')->get();
        $plantas=DB::table('plantas')->select('idPlanta', 'nombre')->get();
        $formasdeentrega=DB::table('formasdeentrega')->select('idFormaEntrega', 'nombre')->get();
        return view('editarpedido')->with('pedido', $pedido)
                                ->with('listaDetallePedido', $listaDetallePedido)
                                ->with('emptransporte', $emptransporte)
                                ->with('log', $log)
                                ->with('plantas', $plantas)
                                ->with('formasdeentrega', $formasdeentrega);
    }      

    public function verpedido($idPedido, $accionPedidoNota){
        $accion = explode('-', $accionPedidoNota)[0];
        $accionNota = explode('-', $accionPedidoNota)[0];
        $pedido=DB::Select('call spGetPedido(?)', array($idPedido) );
        $listaDetallePedido=DB::Select('call spGetPedidoDetalle(?)', array($idPedido) );
        $log = DB::Select('call spGetPedidoLog(?)', array($idPedido) );
        $notas = DB::Select('call spGetPedidoNotas(?)', array($idPedido) );
        $emptransporte = DB::table('empresastransporte')->select('idEmpresaTransporte','nombre')->get();
        $parametros=DB::table('parametros')->select('version')->get();
        return view('verpedido')->with('pedido', $pedido)
                                ->with('listaDetallePedido', $listaDetallePedido)
                                ->with('accion', $accion)
                                ->with('emptransporte', $emptransporte)
                                ->with('log', $log)
                                ->with('notas', $notas)
                                ->with('parametros', $parametros)
                                ->with('plantilla', 'plantilla')
                                ->with('accionNota', $accionNota);
    }

    public function verpedidoNuevaVentana($idPedido, $accion){
        $pedido=DB::Select('call spGetPedido(?)', array($idPedido) );
        $listaDetallePedido=DB::Select('call spGetPedidoDetalle(?)', array($idPedido) );
        $log = DB::Select('call spGetPedidoLog(?)', array($idPedido) );
        $notas = DB::Select('call spGetPedidoNotas(?)', array($idPedido) );
        $emptransporte = DB::table('empresastransporte')->select('idEmpresaTransporte','nombre')->get();
        $parametros=DB::table('parametros')->select('version')->get();
        return view('verpedido')->with('pedido', $pedido)
                                ->with('listaDetallePedido', $listaDetallePedido)
                                ->with('accion', $accion)
                                ->with('emptransporte', $emptransporte)
                                ->with('log', $log)
                                ->with('notas', $notas)
                                ->with('parametros', $parametros)
                                ->with('plantilla', 'plantilla2')
                                ->with('accionNota', 4);
    }


    public function clienteVerPedido($idPedido, $accion){
        $pedido=DB::Select('call spGetPedido(?)', array($idPedido) );
        $parametros=DB::table('parametros')->select('version')->get();
        $listaDetallePedido=DB::Select('call spGetPedidoDetalle(?)', array($idPedido) );
        $emptransporte = DB::table('empresastransporte')->select('idEmpresaTransporte','nombre')->get();
        $log = DB::Select('call spGetPedidoLog(?)', array($idPedido) );
        return view('cliente_verpedido')->with('pedido', $pedido)
                                ->with('listaDetallePedido', $listaDetallePedido)
                                ->with('accion', $accion)
                                ->with('emptransporte', $emptransporte)
                                ->with('log', $log)
                                ->with('parametros', $parametros);
    }
    
    public function programarpedido($idPedido){
        $pedido=DB::Select('call spGetPedido(?)', array($idPedido) );
        $listaDetallePedido=DB::Select('call spGetPedidoDetalle(?)', array($idPedido) );
        $log = DB::Select('call spGetPedidoLog(?)', array($idPedido) );
        $notas = DB::Select('call spGetPedidoNotas(?)', array($idPedido) );

        $emptransporte = DB::table('empresastransporte')->select('idEmpresaTransporte','nombre', 'habilitada')->orderBy('nombre', 'ASC')->get();

        $plantas=DB::table('plantas')->select('idPlanta', 'nombre')->orderBy('nombre', 'asc')->get();
        $parametros=DB::table('parametros')->select('version')->get();
        $ramplas=DB::Select('call spGetRamplas()');
        return view('programacionPedido')->with('pedido', $pedido)
                                ->with('listaDetallePedido', $listaDetallePedido)
                                ->with('emptransporte', $emptransporte)
                                ->with('log', $log)
                                ->with('notas', $notas)
                                ->with('plantas', $plantas)
                                ->with('parametros', $parametros)
                                ->with('ramplas', $ramplas);
    }
    
    public function verificarFlete(Request $datos){
        $verificarFleteYhora=DB::Select('call spGetFleteNotasDeVentas(?,?)', array(
            $datos->input('idNotaVenta'),
            $datos->input('planta')
    ));
        return $verificarFleteYhora;
    }


    public function grabarNuevoPedido(Request $datos){
        if($datos->ajax()){
            $extension="";
            $archivo=$datos->file("upload-demo");
            if(isset($archivo)){
                $extension = $archivo->getClientOriginalExtension();               
            }
             
            //$detalle = json_decode($datos->input('detalle'));
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);


            $fechaEntrega=$datos->input('fechaEntrega');

            $parametros=DB::Select('call spGetParametros');

            $fechaActual = date('Y-m-d'); 
            $datetime1 = date_create($fechaEntrega);
            $datetime2 = date_create($fechaActual);
            $diff = ($datetime2->diff($datetime1));

            $idPedido=DB::Select('call spInsPedido(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
                            $datos->input('idNotaVenta'),
                            $datos->input('fechaEntrega'),
                            $datos->input('observaciones'),
                            $datos->input('horarioEntrega'),
                            $datos->input('idEstadoPedido'),
                            $datos->input('usu_codigo_estado'),
                            $datos->input('totalNeto'),
                            $datos->input('contacto'),
                            $datos->input('telefono'),
                            $datos->input('email'),
                            $datos->input('noExcederCantidad'),
                            $datos->input('tipoCarga'),
                            $datos->input('tipoTransporte'),
                            Session::get('empresaUsuario'),
                            $datos->input('ordenCompra'),
                            $extension,
                            $datos->input('incluyeFleteFalso'),
                            $datos->input('valorFleteFalso'),
                            $datos->input('cantidadFleteFalso'),
                            $datos->input('atrasado')
                            ) 
                        ); 
          
            if ($detalle != null){
                foreach ( $detalle as $item){
                    DB::Select("call spInsPedidoDetalle(?,?,?,?,?,?,?,?)", array( $idPedido[0]->idPedido, $item->idNotaVenta, $item->prod_codigo, $item->u_codigo, $item->cantidad, $item->precio, $item->idPlanta, $item->idFormaEntrega) );
                }
            }
            

            if(!isset($archivo)){
                if($idPedido[0]->nombreArchivo<>''){
                    if( File::exists( public_path('ocompra/nventa/'.$idPedido[0]->nombreArchivo) )){
                        $origen=public_path('ocompra/nventa/'.$idPedido[0]->nombreArchivo);
                        $pos = strripos($idPedido[0]->nombreArchivo, ".");
                        $extension=substr($idPedido[0]->nombreArchivo,$pos);

                        $destino=public_path('ocompra/pedido/'."OC".$idPedido[0]->idPedido.$extension);

                        File::copy($origen, $destino);

                        $pd = Pedido::find($idPedido[0]->idPedido);
                        $pd->nombreArchivoOC = "OC".$idPedido[0]->idPedido.$extension;
                        $pd->save();
                    }                     
                }

            }else{
                if($idPedido[0]->nombreArchivo!=""){
                    //Storage::disk('ocpedido')->put($idPedido[0]->nombreArchivo, \File::get( $archivo) );
                    $nombreArchivo_upload=public_path().'/ocompra/pedido/'.$idPedido[0]->nombreArchivo;
                    file_put_contents($nombreArchivo_upload, \File::get( $archivo) );
                }
            }

           
            if($datos->input('atrasado')==1){
                $this->correoAutorizacionPedidoUrgente($idPedido[0]->idPedido, Session::get('idUsuario') );
            }

            $usuario = "2eee28cdd9-c936e7@inbox.mailtrap.io";
            //Mail::to($usuario)->queue(new enviarMailPedidoCreado($idPedido));

          

            return response()->json([
                "identificador" => $idPedido[0]->idPedido,
                "nombreArchivo" => $idPedido[0]->nombreArchivo,
                "atrasado" => $datos->input('atrasado')
            ]);
        }
    }

    public function actualizarPedido(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            
            $idPedido=DB::Select('call spUpdPedido(?,?,?,?,?,?,?,?,?)', array(
                            $datos->input('idPedido'),
                            $datos->input('fechaEntrega'),
                            $datos->input('horarioEntrega'),
                            $datos->input('observaciones'),
                            $datos->input('totalNeto'),
                            Session::get('idUsuario'),
                            $datos->input('motivo'),
                            $datos->input('ordenCompraCliente'),
                            $datos->input('atrasado')
                            ) 
                        );  

            foreach ( $detalle as $item){
                DB::Select("call spUpdPedidoDetalle(?,?,?,?,?,?)", array( $idPedido[0]->idPedido, $item->prod_codigo,  $item->cantidad, $item->idPlanta, $item->idFormaEntrega, Session::get('idUsuario') ) );
            }

            return response()->json([
                "identificador" => $idPedido[0]->idPedido
            ]);
        }
    }

    public function agregarNota(Request $datos){
        if($datos->ajax()){
            
            $nota=DB::Select('call spInsPedidoNota(?,?,?)', array(
                            $datos->input('idPedido'),
                            $datos->input('nota'),
                            Session::get('idUsuario')
                            ) 
                        );  

            return $nota;
        }
    }

    public function eliminarNota(Request $datos){
        if($datos->ajax()){
            $nota=DB::Select('call spDelPedidoNota(?)', array(
                            $datos->input('idNota')
                            ) 
                        );  
            return $nota;
        }
    }


    public function guardarDatosProgramacion(Request $datos){
        if($datos->ajax()){

            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);           
            foreach ( $detalle as $item){
                DB::Select("call spUpdPedidoProgramacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array( 
                    $datos->input('idPedido'),
                    $item->prod_codigo, 
                    $item->idEmpresaTransporte, 
                    $item->idCamion, 
                    $item->idConductor, 
                    $item->fechaCarga, 
                    $item->horaCarga,
                    $item->peso,
                    Session::get('idUsuario'),
                    $item->nombreEmpresaTransporte,
                    $item->patente,
                    $item->nombreConductor,
                    $item->idPlanta,
                    $item->numeroRampla
                ) 
            );
            }

            return response()->json([
                "identificador" =>  $datos->input('idPedido')
            ]);
        } 

    }

    public function bajarOCpedido($nombreArchivo){
        $pathtoFile = public_path().'/ocompra/pedido/'.$nombreArchivo;
        return response()->download($pathtoFile);        
    }

    function subirOCpedido(Request $datos){
        if($datos->ajax()){
              
            $archivo=$datos->file("upload-demo");
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo= "OC".$datos->input('idPedido').".".$extension;
            if( File::exists(public_path('ocompra/pedido/'."OC".$datos->input('idPedido').'.*'))){
                File::delete(public_path('ocompra/pedido/'."OC".$datos->input('idPedido').'.*'));
            }            
            //Storage::disk('ocpedido')->put($nombreArchivo, \File::get( $archivo) );
            $nombreArchivo_upload=public_path().'/ocompra/pedido/'.$nombreArchivo;
            file_put_contents($nombreArchivo_upload, \File::get( $archivo) );
            $pd = Pedido::find($datos->input('idPedido'));
            $pd->nombreArchivoOC = $nombreArchivo;
            $pd->save();            
            return response()->json([
                "nombreArchivo" => $nombreArchivo
            ]);            
        }
    }


    public function avisoPedidoAprobado($idPedido){
        $pedido= DB::Select('call spGetPedido(?)', array( $idPedido ));
        $destinatarios= DB::Select('call spGetListaDestinatarios(?)', array( 'P' ));        
        Mail::to($destinatarios)->send(new PedidoAprobado($pedido) );
        return;
    }

    public function verResumenGranel(){  
        $plantas=DB::Select('call spGetUsuarioPerfilPlantas(?,?)', array( Session::get('idUsuario'), Session::get('idPerfil') ));
        return view("resumenGranel")->with('plantas', $plantas);
    }

    public function resumenGranel(Request $datos){
        if($datos->ajax()){    
            $resumen= DB::Select('call spGetResumenGranel(?,?,?,?,?,?)', array( $datos->input('idPlanta'), $datos->input('fechaInicio'), $datos->input('fechaTermino'), $datos->input('incluirSinFecha'), Session::get('idUsuario'), Session::get('idPerfil') ));
            return $resumen;
        }    
    }

    public function verPedidosDespachados(){  
        $plantas=DB::Select('call spGetUsuarioPerfilPlantas(?,?)', array( Session::get('idUsuario'), Session::get('idPerfil') ));
        return view("pedidosDespachados")->with('plantas', $plantas);
    }

    public function obtenerPedidosDespachados(Request $datos){
        if($datos->ajax()){    
            $resumen= DB::Select('call spGetPedidosDespachados(?,?,?,?,?)', array( $datos->input('idPlanta'), $datos->input('fechaInicio'), $datos->input('fechaTermino'), Session::get('idUsuario'), Session::get('idPerfil')  ));
            return $resumen;
        }
    }

    public function actualizarNumeroAuxiliar(Request $datos){
        if($datos->ajax()){
             DB::Select('call spUpdNumeroAuxiliar(?,?)', array($datos->input('idPedido'), $datos->input('numeroAuxiliar')) );
             return;
        }
    }

    public function costosMensuales(){
        $costosMensuales=DB::Select('call spGetCostosMensuales()');
        $periodo=date('Ym');
        $unidades=Unidad::orderBy('u_nombre','asc')->get();
        $plantas=Planta::orderBy('nombre','asc')->get();

        $productos=Producto::orderBy('prod_nombre', 'ASC')->get();

        return view('costosMensuales')->with('costosMensuales', $costosMensuales)
            ->with('periodoActual', $periodo)
            ->with('unidades', $unidades)
            ->with('plantas', $plantas)
            ->with('productos', $productos);
    }

    public function costosMensualesProductos(Request $datos){
        if($datos->ajax()){
            $costosMensualesProductos=DB::Select('call spGetCostosMensuales_Productos(?,?)', array($datos->input('ano'), $datos->input('mes')) );
            return $costosMensualesProductos;
        }
    }

    public function crearCostosMensuales(Request $datos){
        if($datos->ajax()){
            $resp=DB::Select('call spInsCostosMensuales(?,?,?)', array($datos->input('ano'), $datos->input('mes'), Session::get('idUsuario') ) );
            return $resp;
        }        
    }

    public function correoPedidoSuspendido($idPedido, $motivo, $correoDestinatario){
        $mj = new \Mailjet\Client('a72036914344c6d8d14466fdd90a9515','a058d7e4f920dfc4a0f34995c92034f8',true,['version' => 'v3.1']);

        $mensaje="<h3>AVISO DE PEDIDO SUSPENDIDO</h3><br><br>";
        $mensaje=$mensaje."Estimado Usuario,<br><br>";
        $mensaje=$mensaje."Se ha suspendido el pedido Nº ".strVal($idPedido).".";
        $mensaje=$mensaje."Motivo: ".$motivo."<br><br>";

        $perfilesNotificacion='5, 7';
        $usuariosDestinatarios=DB::Select('call spGetDestinatariosNotificacion(?,?)', array( $idPedido, $perfilesNotificacion ) ) ;

        $destinatarios=[];
        foreach ( $usuariosDestinatarios as $usuario){
            $destinatarios[]=[ 
                                'Email' => $usuario->usu_correo,
                                'Name' => $usuario->usu_correo
                            ];
        }
        $destinatarios[]=[ 
                            'Email' => 'daviddiaz1402@gmail.com',
                            'Name' => 'David Diaz'
                        ];

        $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => "no-reply@soporteportal.cl",
                  'Name' => "no-reply@soporteportal.cl"
                ],
                'To' => $destinatarios,
                'Subject' => "AVISO DE PEDIDO SUSPENDIDO",
                'TextPart' => "My first Mailjet email",
                'HTMLPart' => $mensaje,
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();       
        return $response->getData();
    }

    public function correoAutorizacionPedidoUrgente( $idPedido, $idUsuario){
        $accion=1; 
        //accion 1 indica que corresponde a una solicitud de autorización de un pedido urgente
        $datos=DB::Select('call spInsSolicitudCorreo(?,?,?)', array( $idUsuario, $idPedido, $accion ) );

        $mj = new \Mailjet\Client('a72036914344c6d8d14466fdd90a9515','a058d7e4f920dfc4a0f34995c92034f8',true,['version' => 'v3.1']);

        $correoDestinatario=$datos[0]->correoUsuarioAutoriza;

        $url=asset('/')."autorizarPedidoUrgente/".$datos[0]->token."/";
        $mensaje="<div style='width:80%'>";
        $mensaje=$mensaje."<h3>SOLICITUD DE AUTORIZACION DE PEDIDO URGENTE</h3><br><br>";
        $mensaje=$mensaje."Estimado Usuario,<br><br>";
        $mensaje=$mensaje."Se ha creado el Pedido Nº <b>".strval($idPedido)."</b>, para el cliente <b>".$datos[0]->nombreCliente."</b>, el cual debe ser despachado con urgencia. Se solicita tu aprobación de crédito, la cual puedes realizar a traves link que esta a continuación del detalle del pedido.<br><br>" ;

        $mensaje=$mensaje."<div style='padding-top:20px;'>";
        $mensaje=$mensaje."<b>DETALLE DE PRODUCTOS INCLUIDOS EN EL PEDIDO</b>";
        $mensaje=$mensaje."<table border='1' cellspacing='0'>";
        $mensaje=$mensaje."<thead>";
        $mensaje=$mensaje."<th>Producto</th>";
        $mensaje=$mensaje."<th>Cantidad</th>";
        $mensaje=$mensaje."<th>Unidad</th>";
        $mensaje=$mensaje."<th>Precio unit.($)</th>";
        $mensaje=$mensaje."<th>Total Neto ($)</th>";
        $mensaje=$mensaje."</thead>";
        $mensaje=$mensaje."<tbody>";
        $total=0;
        foreach ( $datos as $item){
            $mensaje=$mensaje."<tr>";
            $mensaje=$mensaje."<td style='width: 100px;'>".$item->prod_nombre."</td>";
            $mensaje=$mensaje."<td style='width: 80px;text-align: right'>".number_format( $item->cantidad, 0 , "," , "." )."</td>";
            $mensaje=$mensaje."<td style='width: 100px;'>".$item->unidad."</td>";
            $mensaje=$mensaje."<td style='width: 80px;text-align: right'>".number_format( $item->precio, 0 , "," , "." )."</td>";
            $mensaje=$mensaje."<td style='width: 80px;text-align: right'>".number_format( $item->subtotal, 0 , "," , "." )."</td>";
            $total=$total+$item->subtotal;
            $mensaje=$mensaje."</tr>";
        }

        $mensaje=$mensaje."</tbody>";       
        $mensaje=$mensaje."</table><br>";
        $mensaje=$mensaje."<b>Total del pedido $ ".number_format( $total, 0 , "," , "." )."</b>";
        $mensaje=$mensaje."</div><br><br>";
        $mensaje=$mensaje."<div>";
        $mensaje=$mensaje."<a href='".$url."'><img src='".asset('/')."img/aprobar3.png' border='0' style='cursor:pointer; cursor: hand' width='70' height='72'></a>";
        $mensaje=$mensaje."</div>";
        $mensaje=$mensaje."</div><br><br>";
        $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => "no-reply@soporteportal.cl",
                  'Name' => "no-reply@soporteportal.cl"
                ],
                'To' => [
                    [
                        'Email' => $correoDestinatario,
                        'Name' => $correoDestinatario
                    ]
                ],
                'Subject' => "Solicitud de Autorización de Pedido",
                'TextPart' => "",
                'HTMLPart' => $mensaje,
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success(); 

        return $response->getData();
    }

    public function autorizarPedidoUrgente($token){
        
        $datos=DB::Select('call spUpdAutorizaPedidoUrgente(?)', array( $token ) );

        $mj = new \Mailjet\Client('a72036914344c6d8d14466fdd90a9515','a058d7e4f920dfc4a0f34995c92034f8',true,['version' => 'v3.1']);

        $perfilesNotificacion='5, 7';
        $usuariosDestinatarios=DB::Select('call spGetDestinatariosNotificacion(?,?)', array( $datos[0]->idPedido, $perfilesNotificacion ) ) ;

        $destinatarios=[];
        foreach ( $usuariosDestinatarios as $usuario){
            $destinatarios[]=[ 
                                'Email' => $usuario->usu_correo,
                                'Name' => $usuario->usu_correo
                            ];
        }
        $destinatarios[]=[ 
                            'Email' => 'daviddiaz1402@gmail.com',
                            'Name' => 'David Diaz'
                        ];

        $mensaje="<h3>AVISO DE PEDIDO URGENTE</h3><br><br>";
        $mensaje=$mensaje."Estimado Usuario,<br><br>";
        $mensaje=$mensaje."Se ha autorizado el Pedido Nº  ".strval($datos[0]->idPedido).", para el cliente ".$datos[0]->nombreCliente.", destino ".$datos[0]->nombreObra." , el cual debe ser despachado con urgencia. Ingresa a <a href='https://qlnow.quimicalatinoamericana.cl'>QlNow</a> para gestionarlos.";

        $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => "no-reply@soporteportal.cl",
                  'Name' => "no-reply@soporteportal.cl"
                ],
                'To' => $destinatarios,
                'Subject' => "AVISO DE PEDIDO URGENTE",
                'TextPart' => "",
                'HTMLPart' => $mensaje,
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();       
        $response->getData();      
        return view('pedidoAutorizado');
    }


    public function correoResumenActividad(){
        $mj = new \Mailjet\Client('a72036914344c6d8d14466fdd90a9515','a058d7e4f920dfc4a0f34995c92034f8',true,['version' => 'v3.1']);

        $correoDestinatario='nbastias@spsgroup.cl';

        $mensaje="<h3>EJEMPLO DE CORREO RESUMEN DE ACTIVIDAD</h3><br><br>";
        $mensaje=$mensaje."Estimado Usuario,<br><br>";
        $mensaje=$mensaje."Se ha creado el Pedido Nº , el cual debe ser despachado con urgencia, se solicita su autorización por medio del siguiente link";

        $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => "no-reply@soporteportal.cl",
                  'Name' => "no-reply@soporteportal.cl"
                ],
                'To' => [
                  [
                    'Email' => 'daviddiaz1402@gmail.com',
                    'Name' => 'David Diaz'
                  ],
                   [
                    'Email' => $correoDestinatario,
                    'Name' => $correoDestinatario
                  ]
                ],
                'Subject' => "RESUMEN DE ACTIVIDAD QLNOW",
                'TextPart' => "",
                'HTMLPart' => $mensaje,
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();       
        return $response->getData();
    }
/*
    public function obtenerIdProductoListaPrecio($codigo_prod, $nombre_unidad, $codigo_planta) {
        dd($codigo_prod);
        $idProductoListaPrecio=DB::Select('call spGetIdProductoListaPrecios(?,?,?)', array($codigo_prod), array($nombre_unidad), array($codigo_planta) );

        return $idProductoListaPrecio;
        
        $idProductoListaPrecio= DB::Select('call spGetIdProductoListaPrecios(?,?,?)', array( 
                    $datos->input('codigo_prod'), 
                    $datos->input('nombre_unidad'), 
                    $datos->input('codigo_planta')  
                ));
            var_dump($idProductoListaPrecio);
            return $idProductoListaPrecio;

        
    }*/ 

    public function obtenerIdProductoListaPrecio(Request $datos) {
        if ($datos->ajax()){
            $idProductoListaPrecio= DB::Select('call spGetIdProductoListaPrecios(?,?,?)', array( 
                    $datos->input('codigo_prod'), 
                    $datos->input('nombre_unidad'), 
                    $datos->input('codigo_planta'),
                ));
            return $idProductoListaPrecio;
        }
    }

    public function guardarProductoListaPrecio(Request $datos) {
        if ($datos->ajax()){
            DB::Select('call spInsCostosMensualesProductos(?,?,?,?)', array( 
                    $datos->input('ano'), 
                    $datos->input('mes'), 
                    $datos->input('idproductolistaprecio'),
                    $datos->input('costo'),
                ));
            return;
        }
    }

    public function buscarTiempoProduccion(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spGetTiempoProduccion(?,?,?)', array(
                            $datos->input('nombreProducto'),
                            $datos->input('idPlanta'),
                            $datos->input('nombre')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }

    public function buscarFeriados(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spGetFeriadosFiltro(?)', array(
                            $datos->input('ano')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }

    public function verificarFeriado(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spGetVerificarFeriado(?)', array(
                            $datos->input('fecha')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }

    public function buscarTiempoTraslado(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spGetTiempoTraslado(?,?)', array(
                            $datos->input('notaVenta'),
                            $datos->input('idPlanta')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }

    public function guardarAcciones(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spInsAcciones(?,?)', array(
                            Session::get('idUsuario'),
                            $datos->input('idPedido')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }
   
    public function verificarMesEn0(Request $datos){
        if($datos->ajax()){
            $costosEn0=DB::Select('call spGetVerificarMesEn0(?,?)', array($datos->input('ano'), $datos->input('mes')) );
            return $costosEn0;
        }
    }
    public function costosMensualesProductosen0(Request $datos){
        if($datos->ajax()){
            $costosMensualesProductos=DB::Select('call spGetCostosMensuales_ProductosCosto0(?,?)', array($datos->input('ano'), $datos->input('mes')) );
            return $costosMensualesProductos;
        }
    }
    public function plantaspedidos(Request $dato){
        
        if($dato->ajax()){
            $costosEn0=DB::Select('call spGetPlantasPedido(?,?)', array($dato->input('nomPlanta'),$dato->input('unidad')));
            return $costosEn0;
        }
    }
}
