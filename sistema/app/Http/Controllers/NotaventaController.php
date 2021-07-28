<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use File;
use App\Planta;
use PDF;
use App\CondicionPago;
use App\Notaventa;
use PhpParser\Node\Stmt\Return_;

class NotaventaController extends Controller
{
    //

    public function index()
    {
        //
        $plantas=DB::table('plantas')->select('nombre', 'idPlanta')->get();
        $formaEntrega=DB::table('formasdeentrega')->select('idFormaEntrega', 'nombre')->get();
        $usuarios=DB::Select('call spGetUsuarioPorGrupo(?)', array( 'C' )); 
        $condicionesPago=CondicionPago::All();
        return view('nuevanventa')->with('Plantas', $plantas)
                                    ->with('formaEntrega', $formaEntrega)
                                    ->with('condicionesPago', $condicionesPago)
                                    ->with('usuarios', $usuarios);
    }

    public function grabarNuevaNotaVenta(Request $datos){
    	if($datos->ajax()){

            $extension="";
            $archivo=$datos->file("upload-demo");
            if(isset($archivo)){
                $extension = $archivo->getClientOriginalExtension();               
            }

            $detalle = json_decode($datos->input('detalle'));
            $totalNV=0;
            foreach ( $detalle as $item){
                $totalNV += ($item->precio);
            }

            $idnotaventa=DB::Select('call spInsNotaVenta(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
                            $datos->input('cot_numero'),
                            $datos->input('cot_ano'),
                            $datos->input('idObra'),
                            $datos->input('observaciones'),
                            $datos->input('contacto'),
                            $datos->input('correo'),
                            $datos->input('telefono'),
                            $datos->input('ordenCompraCliente'),
                            $datos->input('idUsuarioEncargado'),
                            $datos->input('codigoClienteSoftland'),
                            Session::get('idUsuario'),
                            $datos->input('idCondicionPago'),
                            $extension,
                            $totalNV
                            ) 
                        );  

           
            foreach ( $detalle as $item){
                DB::Select("call spInsNotaVentaDetalle(?,?,?,?,?,?,?,?)", array( $idnotaventa[0]->idNotaVenta, $item->prod_codigo, $item->formula, $item->cantidad, $item->u_codigo, $item->precio, $item->idPlanta, $item->idFormaEntrega ) );
            }

            if($idnotaventa[0]->nombreArchivo!=""){
               // $nombreArchivo= "OC".$idnotaventa[0]->idNotaVenta.".".$extension;
                //Storage::disk('ocnventa')->put($idnotaventa[0]->nombreArchivo, \File::get( $archivo) );
                $nombreArchivo_upload=public_path().'/ocompra/nventa/'.$idnotaventa[0]->nombreArchivo;
                file_put_contents($nombreArchivo_upload, \File::get( $archivo) );
            }


            return response()->json([
                "identificador" => $idnotaventa[0]->idNotaVenta
            ]);
        }
    }

    function subirOCnotaventa(Request $datos){
        if($datos->ajax()){
              
            $archivo=$datos->file("upload-demo");
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo= "OC".$datos->input('idNotaVenta').".".$extension;
            if( File::exists(public_path('ocompra/nventa/'."OC".$datos->input('idNotaVenta').'.*'))){
                File::delete(public_path('ocompra/nventa/'."OC".$datos->input('idNotaVenta').'.*'));
            }            
            //Storage::disk('ocnventa')->put($nombreArchivo, \File::get( $archivo) );
            $nombreArchivo_upload=public_path().'/ocompra/nventa/'.$nombreArchivo;
            file_put_contents($nombreArchivo_upload, \File::get( $archivo) );
            $nv = Notaventa::find($datos->input('idNotaVenta'));
            $nv->nombreArchivoOC = $nombreArchivo;
            $nv->save();            
            return response()->json([
                "nombreArchivo" => $nombreArchivo
            ]);            
        }
    }

    public function actualizarValoresNotaVenta(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            foreach ( $detalle as $item){
                DB::Select("call spUpdValoresNotaVenta(?,?,?)", array( $item->idNotaVenta, $item->prod_codigo, $item->formula ) );
            }

            return response()->json([
                "identificador" => 'OK'
            ]);
        }
    }

    public function listarNotasdeVenta(){
        DB::Select('call spPasarNVvencidaHistorico()');
		$listaNotasdeVenta=DB::Select('call spGetNotasdeVentas(?)', array(0) );       	
    	return view('listanotaventa')->with('listaNotasdeVenta', $listaNotasdeVenta);
    }

    public function clienteNotasdeVenta(){
        $listaNotasdeVenta=DB::Select('call spGetNotasdeVentasCliente(?)', array( Session::get('idUsuario') ) );           
        return view('cliente_notasdeventa')->with('listaNotasdeVenta', $listaNotasdeVenta);
    }    

    public function historicoNotasdeVenta(){
        $fecha_termino = date('Y-m-d'); 
        $fecha_inicio = date("Y-m-d",strtotime($fecha_termino));
        if(Session::get('empresaUsuario')=='0'){
            $clientes=DB::table('empresas')->select('emp_codigo', 'emp_nombre')->orderBy(DB::raw("TRIM(emp_nombre)"))->get();
        }else{
            $clientes=DB::table('empresas')->select('emp_codigo', 'emp_nombre')->where('emp_codigo',"=",Session::get('empresaUsuario') )->orderBy(DB::raw("TRIM(emp_nombre)"))->get();
        }
        $parametros=DB::table('parametros')->select('version')->get();

        if(Session::get('idPerfil') == 14 or Session::get('idPerfil') ==15){
            $listaNotasdeVenta=DB::Select('call spGetHistoricoNotasdeVentasCliente(?,?,?,?,?,?,?,?)', 
                array( 
                    $fecha_inicio, 
                    $fecha_termino,
                    0,
                    0,
                    0,
                    Session::get('idUsuario'),
                    Session::get('idPerfil') ,
                    1
                ) 
            );

        }else{
            $listaNotasdeVenta=DB::Select('call spGetHistoricoNotasdeVentas(?,?,?,?,?,?,?,?)', 
                array( 
                    $fecha_inicio, 
                    $fecha_termino,
                    0,
                    0,
                    0,
                    Session::get('idUsuario'),
                    Session::get('idPerfil') ,
                    1
                ) 
            );            
        }       

        return view('historicoNotasdeVenta')->with('listaNotasdeVenta', $listaNotasdeVenta)
                                    ->with('parametros', $parametros)
                                    ->with('clientes', $clientes)
                                    ->with('fecInicio', $fecha_inicio)
                                    ->with('fecTermino', $fecha_termino);        
    }

    public function obtenerHistoricoNotaVentas(Request $datos){
        $fechaInicio=strtotime( str_replace('/', '-', $datos->input("fechaCreacionDesde") ) );
        $fechaTermino=strtotime( str_replace('/', '-', $datos->input("fechaCreacionHasta") ) );
        $fechaInicio = date('Y-m-d', $fechaInicio);
        $fechaTermino = date('Y-m-d', $fechaTermino);


       
        if(Session::get('idPerfil') == 14 or Session::get('idPerfil') ==15){

            $listaNotasdeVenta=DB::Select('call spGetHistoricoNotasdeVentasCliente(?,?,?,?,?,?,?,?)', 
                array( 
                    $fechaInicio, 
                    $fechaTermino,
                    $datos->input('emp_codigo'),
                    $datos->input('nvDesde'),
                    $datos->input('nvHasta'),
                    Session::get('idUsuario'),
                    Session::get('idPerfil') ,
                    $datos->input('opcion')
                ) 
            );
        }else{
            $listaNotasdeVenta=DB::Select('call spGetHistoricoNotasdeVentas(?,?,?,?,?,?,?,?)', 
                array( 
                    $fechaInicio, 
                    $fechaTermino,
                    $datos->input('emp_codigo'),
                    $datos->input('nvDesde'),
                    $datos->input('nvHasta'),
                    Session::get('idUsuario'),
                    Session::get('idPerfil') ,
                    $datos->input('opcion')
                ) 
            );            
        }    

        return $listaNotasdeVenta;
    }    


    public function aprobarnota($idNotaVenta){
        $notaventa=DB::Select('call spUpdAprobarNotaVenta(?,?)', array( $idNotaVenta, Session::get('idUsuario') ) );      
        $listaNotasdeVenta=DB::Select('call spGetNotasdeVentas(?)', array(0) );   
        return redirect('listarNotasdeVenta');
    }

    public function Desaprobarnota($idNotaVenta){
        $notaventa=DB::Select('call spUpdDesaprobarNotaVenta(?,?)', array( $idNotaVenta, Session::get('idUsuario')  ) );      
        return redirect('listarNotasdeVenta');
    }

    public function AprobarNotasdeVenta(){
        $listaNotasdeVenta=DB::Select('call spGetAprobarNotasdeVentas');           
        return view('aprobarnotaventa')->with('listaNotasdeVenta', $listaNotasdeVenta);
    }


    public function datosNotaVenta($id){
     //   return DB::table('cotizaciones')->join('empresas','empresas.emp_codigo', '=', 'cotizaciones.emp_codigo')->
     //   select('cotizaciones.cot_fecha_creacion', 'cotizaciones.cot_obra', 'empresas.emp_codigo',  'empresas.emp_razon_social', 'cotizaciones.cot_ano')->where('cotizaciones.cot_numero', //$id)->get();        

     return DB::Select('call spGetNotaVenta(?)', array($id));   
    } 

    public function vernotaventa($idNotaPedido, $accion){
        //MATIAS
        $id = explode('-', $idNotaPedido)[0];
        $numPedido = explode('-',$idNotaPedido)[1];
        $notaventa=DB::Select('call spGetNotaVenta(?)', array($id));
        $notaventadetalle=DB::Select('call spGetNotaVentaDetalle(?)', array($id) );

        $pedidos=DB::Select('call spGetNotaVentaPedidos(?, ?, ?)', array($id, Session::get('idUsuario'), Session::get('idPerfil') ) );
        $log = DB::Select('call spGetNotaVentaLog(?)', array($id) );
        $condicionesPago=CondicionPago::All();
        $usuarios=DB::Select('call spGetUsuarioPorGrupo(?)', array( 'C' )); 
        return view('vernotaventa')->with('notaventa', $notaventa)
                                   ->with('notaventadetalle', $notaventadetalle)
                                   ->with('accion', $accion)
                                   ->with('pedidos', $pedidos)
                                   ->with('condicionesPago', $condicionesPago)
                                   ->with('log', $log)
                                   ->with('usuarios', $usuarios)
                                   ->with('accion', $accion)
                                   ->with('numPedido', $numPedido);
    }

    public function cerrarNotaVenta($idNotaVenta, $motivo){
        DB::Select('call spUpdCerrarNotaVenta(?,?,?)', array($idNotaVenta, Session::get('idUsuario'), $motivo ) );
        return redirect('listarNotasdeVenta');
    }

    public function existeArchivo(Request $datos){
        if($datos->ajax()){
            if ($datos->input('nombreArchivo') == ''){
                return response()->json([
                    "existe" => false
                ]);
            }
            $pathtoFile = public_path().'/ocompra/pedido/'.$datos->input('nombreArchivo');
            $exists = File::exists($pathtoFile);
            //$exists = Storage::disk($datos->input('carpeta'))->exists($datos->input('nombreArchivo'));
            return response()->json([
                "existe" => $exists
            ]);                
        }
    }

    public function bajarOCnventa($nombreArchivo){
      $pathtoFile = public_path().'/ocompra/nventa/'.$nombreArchivo;
      $nombreArchivo = asset('ocompra/nventa/'.$nombreArchivo);
      $result = File::exists($pathtoFile); 
      if($result){
            return view('verpdf')->with('nombreArchivo', $nombreArchivo);
      }

    }

    public function actualizarDatosNV(Request $datos){
        if($datos->ajax()){
            $nv = Notaventa::find($datos->input('idNotaVenta'));
            $nv->contacto = $datos->input('contacto');
            $nv->correo = $datos->input('correo');
            $nv->telefono = $datos->input('telefono');
            $nv->observaciones = $datos->input('observaciones');
            $nv->ordenCompraCliente = $datos->input('ordenCompraCliente');
            $nv->idCondiciondePago = $datos->input('idCondiciondePago');
            $nv->codigoClienteSoftland = $datos->input('codigoSoftland');
            $nv->idUsuarioEncargado = $datos->input('idUsuarioEncargado');
            $nv->save(); 
            $num = 0;
            $idNotaventa = $datos->input('idNotaVenta');
            $idUsuario = Session::get('idUsuario');

            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            foreach ( $detalle as $item){
           
            DB::Select("call spUpdValoresNotaVenta(?,?,?,?,?)", array( $item->idNotaVentaDetalle, $item->formula,$idNotaventa,$idUsuario,$num) );
                $num = 2; 
            }
 
            return response()->json([
                "idNotaVenta" => $datos->input('idNotaVenta')
            ]);                          
        }
    }

    public function imprimirNotaVenta($id)
    {
        $notaventa=DB::Select('call spGetNotaVenta(?)', array($id));
        $notaventadetalle=DB::Select('call spGetNotaVentaDetalle(?)', array($id) );
        $fecha = new \Datetime();

        //var_dump($proyecto); exit();
 
   
            $data = [
                'fecha'=>$fecha->format('d-m-Y H:i:s'),
                'notaventa' => $notaventa,
                'detalle'=>$notaventadetalle
            ];
            $pdf = PDF::loadView('plantillasPDF/notaventa', $data, [], [
              'format' => 'Letter'
            ]);
            return $pdf->stream('notadeventa_'.$id.'.pdf');        
     
    }


    public function notaVentaVigenteCargos(Request $datos){
        
        $cargos=DB::Select('call spGetPlantas()');

        
        return view('notadeventaCargos')->with('subtitulo', '(Notas de Venta Vigentes)')->with('cargos', $cargos);
    }  
    public function notaVentaVigenteCargos1(Request $datos){
        if($datos->ajax()){  
        $cargos=DB::Select('call spGetNotaVentaCostos(?,?,?)', array(0,$datos->input('fechaInicio'),$datos->input('fechaTermino')));
        return $cargos;
        }
    }

    public function notaVentaCerradaCargos(Request $datos){
        return view('notadeventaCargos')->with('subtitulo', '(Notas de Venta Cerradas)');
    } 
    public function notaVentaCerradaCargos1(Request $datos){
        if($datos->ajax()){ 
        $cargos=DB::Select('call spGetNotaVentaCostos(?,?,?)', array(1,$datos->input('fechaInicio'),$datos->input('fechaTermino')));
        return  $cargos;
    } 
}

    public function notaVentaCargosUrgente(){
       
        return view('notadeventaCargos')->with('subtitulo', '(Asignaciones Pendientes)');        
    } 
    public function notaVentaCargosUrgente1(Request $datos){
        if($datos->ajax()){ 
        $cargos=DB::Select('call spGetNotaVentasCostosUrgentes()');
        return $cargos;        
    }   
    }
    public function actualizarNotaVentaCargos(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            foreach ( $detalle as $item){
                DB::Select("call spUpdNotaVentaCargos(?,?,?,?,?,?,?)", array( 
                    $item->idNotaVenta,
                    $item->idPlanta,
                    $item->u_codigo,
                    $item->flete, 
                    $item->distancia, 
                    $item->tiempoTraslado, 
                    Session::get('idUsuario') 
                ) );
            } 
            return response()->json('OK');                       
        }        
    }

    public function clienteNotaVentas(){
      $listaUsuarios=DB::Select('call spGetUsuariosCliente()');
      $listaEmpresas=DB::Select('call spGetEmpresas()');
      return view('clienteNotaVenta')->with('listaUsuarios', $listaUsuarios)->with('listaEmpresas', $listaEmpresas);
    }

    public function agregarUsuarioNotaVenta(Request $datos){
        if($datos->ajax()){
            $usuario=DB::Select('call spInsUsuarioNotaVenta(?,?,?)', 
                array($datos->input('usu_codigo'), $datos->input('emp_codigo'), $datos->input('idNotaVenta') ) );

            return $usuario;
        }
    }

    public function usuarioNotasdeVenta(Request $datos){
        if($datos->ajax()){
            $notas=DB::Select('call spGetUsuarioNotasdeVenta(?)', 
                array($datos->input('usu_codigo') ) );

            return $notas;
        }
    }

    public function eliminarUsuarioNotaVenta(Request $datos){
        if($datos->ajax()){
            $notas=DB::Select('call spDelUsuarioNotaVenta(?,?)', 
                array($datos->input('usu_codigo'), $datos->input('idNotaVenta') ) );

            return $notas;
        }
    }

    public function listarNotasdeVentaModal(){
        DB::Select('call spPasarNVvencidaHistorico()');
		$listaNotasdeVenta=DB::Select('call spGetNotasdeVentas(?)', array(0) );       	
        return $listaNotasdeVenta;
    }
   

}
