<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Productolistaprecio;
use App\Unidad;
use App\Planta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use File;
use App\Costo;
use App\Imports\CostoImport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;

class ProductoController extends Controller
{
    //


    public function listaProductos()
    {
        // 
        $listaProductos=DB::Select('call spGetProductosPrecios()' );
        $unidades=Unidad::orderBy('u_nombre','asc')->get();
        $plantas=Planta::orderBy('nombre','asc')->get();
        $productos=Producto::orderBy('prod_nombre','asc')->get();
        $planta = DB::select('call spGetPlantas' );

        return view('productos')->with('listaProductos', $listaProductos)->with('unidades', $unidades)->with('plantas', $plantas)
            ->with('productos', $productos)->with('planta',$planta);
    }


    public function validarProductoCodigoSoftland(Request $datos){
        if($datos->ajax()){


            $client = new Client();
            $url="http://webservice.quimicalatinoamericana.cl:8082/qrysoftland/api/datosproducto";

            $params=[
                    "codigoProducto" => $datos->input('codigoProducto')       
            ];

            $headers = [
                'X-CSRF-TOKEN' => 'WiyfqvBuHrUnzT6zCvidq9lMVIQSB220Wtsx8EK5'
            ];

            $response= $client->request('POST', $url, [
                'json' => $params,
                'headers' => $headers,
                'verify' => false
            ]);

            $respuesta=json_decode( $response->getBody() );
            $cont=0;
            foreach($respuesta as $item){
                $cont++;
            }

            return response()->json([
                    "identificador" =>  $cont
                ]);                

        }
    }


    public function guardarDatosProducto(Request $datos){
        if($datos->ajax()){
            $producto=DB::Select('call spUpdProducto(?,?,?,?,?,?)', array(
                            $datos->input('prod_codigo'),
                            $datos->input('nombre'),
                            $datos->input('descripcion'),
                            $datos->input('precioReferencia'),
                            $datos->input('requiereDiseno'),
                            $datos->input('codigoSoftland')
                            ) 
                        );  

            return response()->json([
                "identificador" =>  $producto[0]->prod_codigo
            ]);
        }   
    }

    public function productosCodigosSoftland(){

            $productosCodigosSoftland=Productolistaprecio::All();
            return response()->json($productosCodigosSoftland);   

    }

    public function guardarDatosProductoListaPrecio(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spInsProductoListaPrecio(?,?,?,?,?,?,?,?,?,?)', array(
                            $datos->input('idProductoListaPrecios'),
                            $datos->input('nombreProducto'),
                            $datos->input('unidad'),
                            $datos->input('idPlanta'),
                            $datos->input('precioReferencia'), 
                            $datos->input('codigoSoftland'),
                            $datos->input('requiereDiseno'),
                            $datos->input('granel'),
                            $datos->input('solicitaCertificado'),
                            $datos->input('tiempoProduccion')
                            ) 
                        );
            return response()->json($respuesta);
        }         
    }

    public function eliminarProductoListaPrecio(Request $datos){
        if($datos->ajax()){
            $respuesta=DB::Select('call spDelProductoListaPrecio(?)', array($datos->input('idProductoListaPrecios') ) );
            return response()->json($respuesta);
        }
    }

    public function actualizarCostos(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            foreach ( $detalle as $item){
                DB::Select("call spUpdProductoCostoMensual(?,?,?,?,?)", array( 
                    $datos->input('ano'),
                    $datos->input('mes'), 
                    $item->idProductoListaPrecio, 
                    $item->costo, 
                    Session::get('idUsuario') 
                ) );
            } 
            return response()->json('OK');                       
        }
    }

    public function subirArchivoCostos(Request $data){
        $datos=DB::Select('call spInsRegistroUpload(?,?,?,?,?)', array( 1, Session::get('idUsuario'), $data->input("observaciones"), $data->input("ano"), $data->input("mes") ) );
        $archivo=$data->file("upload-demo");
        $nombreArchivo=public_path().'/costos/file_'.strval($datos[0]->idRegistroUpload).'.csv';
        file_put_contents($nombreArchivo, \File::get( $archivo) );
        $ruta= public_path('costos/'.$nombreArchivo);
        $linea = 0;
        //Abrimos nuestro archivo
        set_time_limit(0);
        $archivo = fopen($ruta, "r");
        //Lo recorremos
        $registros = array();
        while (($costo = fgetcsv($archivo,0, ";")) == true) 
        {
          if($linea>0){
            $this->cargarRegistroCosto($datos[0]->idRegistroUpload, $costo, $linea+1);
          }
          $linea+=1;

          //if($linea==1000){break;}

        }
        //Cerramos el archivo
        fclose($archivo); 
        DB::Select('call spUpdEtlCostos(?,?)', array($data->input("ano"), $data->input("mes") ) );    
    }

    function cargarRegistroCosto($idRegistroUpload, $row, $linea){
      if ($row[0]=='ERROR'){
        $costo=new Costo([
                   'idRegistroUpload' => $idRegistroUpload,
                   'prod_nombre'    => $linea,
                   'u_nombre'    => 'ERROR'
              ]);
      }else{
        $costo=new Costo([
                   'idRegistroUpload' => $idRegistroUpload,
                   'prod_nombre'    => $row[0],
                   'u_nombre'    => $row[1],
                   'nombrePlanta'    => $row[2],
                   'costo'    => $row[3]      
                ]);
        }
        $costo->save();
    }

    public function verificarProductoEnListadePrecios(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('productos');
            $detalle= json_decode($detalle);
            $arrProducto=array();
            foreach ( $detalle as $item){

                $producto=DB::Select("call spGetVerificarExistenciaProductoListaPrecio(?,?,?)", array( 
                            $item->prod_codigo,
                            $item->u_codigo,
                            $item->idPlanta
                        ));

                if($producto[0]->existe==0){
                    $arrProducto[]= [
                                        $producto[0]->nombreProducto,
                                        $producto[0]->unidad,
                                        $producto[0]->nombrePlanta
                                    ];
                }
            }
            return response()->json($arrProducto);    
        }
    }

}    
