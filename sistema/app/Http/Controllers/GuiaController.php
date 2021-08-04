<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use File;
use \Mailjet\Resources;
use GuzzleHttp\Client;

class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearGuiaDespachoElectronica(Request $datos)
    {
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);
            $guia=DB::Select('call spGetNuevoNumeroGuia(?,?)', array( $datos->input('idPedido'), Session::get('idUsuario') ) );

            foreach ( $detalle as $item){
                DB::Select("call spInsGuiaDetalle(?,?,?,?,?,?)", array(   1,
                                                                        $guia[0]->nuevaGuia, 
                                                                        $datos->input('idPedido'), 
                                                                        $item->prod_codigo,
                                                                        $item->unidad, 
                                                                        $item->cantidad ) );
            }
            return response()->json([
                "nuevaGuia" => $guia[0]->nuevaGuia
            ]);
        }          
    }
    
    public function validarNumeroGuia(Request $datos){
        if($datos->ajax()){


            $client = new Client();
            $url="http://webservice.quimicalatinoamericana.cl:8082/qrysoftland/api/datosdte";

            $params=[
                    "tipoDocumento" => 52,
                    "folio" => $datos->input('numeroGuia')                
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


    public function registroSalida(){
        $guias=DB::Select('call spGetGuiasParaDespacho(?,?)', array( Session::get('idUsuario'), Session::get('idPerfil')  ) );
        return view('registroSalida')->with('guias', $guias);
    }


    public function guiasEnProceso(){
        $guias=DB::Select('call spGetGuiasEnProceso(?,?)', array( Session::get('idUsuario'), Session::get('idPerfil') ) );
        $titulo='Certificados pendientes';
        return view('guiasEnProceso')->with('guias', $guias)->with('titulo', $titulo);        
    }

    public function listaGuiasDespacho(){
        $guias=DB::Select('call spGetGuiasEnProceso(?,?)', array(Session::get('idUsuario'), Session::get('idPerfil')));
        return view('listaGuiasDespacho')->with('guias', $guias);        
    }    

    public function registrarSalidaDespacho(Request $datos){
        if($datos->ajax()){
            $guia=DB::Select('call spGetRegistrarSalidaDespacho(?,?,?)', array( $datos->input('tipoGuia'), $datos->input('numeroGuia'), Session::get('idUsuario') ) );


            //$this->avisoRegistrodeSalida($datos->input('numeroGuia'));

            return response()->json([
                "numroGuia" => $guia[0]->numeroGuia
            ]);            
        }
    }

    public function actualizarDatosGuiaDespacho(Request $datos){
        if($datos->ajax()){
            $detalle=$datos->input('detalle');
            $detalle= json_decode($detalle);            
            $guia=DB::Select('call spGetCompletarDatosGuiaDespacho(?,?,?,?,?,?,?,?,?,?,?)', 
                array( $datos->input('tipoGuia'), $datos->input('numeroGuia'), $datos->input('nombreConductor'), $datos->input('patente'),
                $datos->input('sellos'), $datos->input('temperaturaCarga'), $datos->input('observaciones'), Session::get('idUsuario'), 
                $datos->input('numeroGuiaOrigen'), $datos->input('nombreEmpresaTransportes'), $datos->input('numeroRampla') ) );

            foreach ( $detalle as $item){
                DB::Select("call spUpdGuiaDespachoDetalle(?,?,?,?,?)", array($item->numeroGuia, 
                                                                        $item->prod_codigo,
                                                                        $item->unidad, 
                                                                        $item->cantidad,
                                                                        Session::get('idUsuario')
                                                                         ) );
            }

            return response()->json([
                "numroGuia" => $guia[0]->numeroGuia
            ]);            
        }
    }

    public function emitirGuiaDespacho(Request $datos){
        if($datos->ajax()){ 
            $guia=DB::Select('call spUpdEmitirGuia(?,?)', array( $datos->input('numeroGuia'), Session::get('idUsuario') ) );            
            $this->crearguiatxt($datos->input('numeroGuia'));
            return response()->json([
                "numeroGuia" => $guia[0]->numeroGuia
            ]);             
        }
    }    


    public function crearguiatxt(Request $datos){
        if($datos->ajax()){

            $nombreArchivo=public_path().'/guias/txt/G'.$datos->input('numeroGuia').'.txt';
            $guiaElectronica=DB::Select('call spGetFormatoGuiaElectronica(?)', array($datos->input('numeroGuia')) );          
            $fh = fopen($nombreArchivo, 'w');
            $idPedido=0;
            foreach ( $guiaElectronica as $item){

                $idPedido=$item->numeroOrdenProduccion;

                $cadena='';
                $cadena=$cadena.''.$item->bodega.''.';';
                $cadena=$cadena.$item->numeroGuia.';';
                $cadena=$cadena.''.$item->tipoDocumento.''.';';
                $cadena=$cadena.''.$item->subTipoDocumento.''.';';
                $cadena=$cadena.''.$item->fechaGeneracion.''.';';
                $cadena=$cadena.''.$item->ConceptoSalida.''.';';
                $cadena=$cadena.''.$item->observacion.''.';';
                $cadena=$cadena.''.$item->codigoClienteSoftland.''.';';
                $cadena=$cadena.''.strtoupper($item->nombreCliente).''.';';
                $cadena=$cadena.''.strtoupper($item->rutCliente).''.';';
                $cadena=$cadena.''.$item->giroCliente.''.';';
                $cadena=$cadena.''.$item->direccionCliente.''.';';
                $cadena=$cadena.''.$item->comunaCliente.''.';';
                $cadena=$cadena.''.$item->ciudadCliente.''.';';
                $cadena=$cadena.''.$item->centroCosto.''.';';
                $cadena=$cadena.''.$item->codigoBodegaDestino.''.';';
                $cadena=$cadena.''.$item->codigoListaPrecio.''.';';
                $cadena=$cadena.$item->numeroOrdenTrabajo.';';
                $cadena=$cadena.$item->numeroOrdenProduccion.';';
                $cadena=$cadena.$item->OrdenCompra.';';
                $cadena=$cadena.$item->facturaAsociada.';';
                $cadena=$cadena.''.$item->subTipoFacturaAsociada.''.';';
                $cadena=$cadena.$item->notaCreditoAsociada.';';
                $cadena=$cadena.''.$item->codigoCentroCostoparaContabilizar.''.';';
                $cadena=$cadena.''.$item->codigoVendedor.''.';';
                $cadena=$cadena.''.$item->codigoCondiciondePago.''.';';
                $cadena=$cadena.''.$item->codigoLugarDespacho.''.';';
                $cadena=$cadena.''.$item->direccionDespacho.''.';';
                $cadena=$cadena.''.$item->comunaDespacho.''.';';
                $cadena=$cadena.''.$item->ciudadDespacho.''.';';
                $cadena=$cadena.''.$item->paisDespacho.''.';';
                $cadena=$cadena.''.$item->atencion.''.';';
                $cadena=$cadena.''.$item->provincia.''.';';
                $cadena=$cadena.''.$item->region.''.';';
                $cadena=$cadena.$item->codigoPostalDespacho.';';
                $cadena=$cadena.''.$item->codigoGLN.''.';';
                /*$cadena=$cadena.''.$item->.''.';';*/
                $cadena=$cadena.$item->codigoVendedorWalmart.';';
                $cadena=$cadena.''.$item->retiradoPor.''.';';
                $cadena=$cadena.''.$item->patenteCamionDespacho.''.';';
                $cadena=$cadena.''.$item->solicitadoPor.''.';';  
                $cadena=$cadena.''.$item->rutTransportista.''.';';
                $cadena=$cadena.''.$item->despachadoPor.''.';';
                $cadena=$cadena.''.$item->rutSolicitante.''.';';
                $cadena=$cadena.$item->tipoDespacho.';';
                $cadena=$cadena.$item->numeroNotaVenta.';';
                $cadena=$cadena.$item->pordentajeDescuento1.';';
                $cadena=$cadena.$item->valorDescuento1.';';
                $cadena=$cadena.$item->pordentajeDescuento2.';';
                $cadena=$cadena.$item->valorDescuento2.';';
                $cadena=$cadena.$item->pordentajeDescuento3.';';
                $cadena=$cadena.$item->valorDescuento3.';';
                $cadena=$cadena.$item->pordentajeDescuento4.';';
                $cadena=$cadena.$item->valorDescuento4.';';
                $cadena=$cadena.$item->pordentajeDescuento5.';';
                $cadena=$cadena.$item->valorDescuento5.';';
                $cadena=$cadena.$item->flete.';';
                $cadena=$cadena.$item->embalaje.';';
                $cadena=$cadena.$item->totalFinal.';';
                $cadena=$cadena.''.$item->codigoProductoSF.''.';';
                $cadena=$cadena.''.$item->descripcionProducto.''.';';
                $cadena=$cadena.''.$item->codigoUnidadMedida.''.';';
                $cadena=$cadena.''.$item->descripcionProducto1.''.';';
                $cadena=$cadena.$item->cantidadDespachada.';';
                $cadena=$cadena.$item->precioReferencia.';';
                $cadena=$cadena.$item->porcentajeDescuentoLinea1.';';
                $cadena=$cadena.$item->valorDescuentoLinea1.';';
                $cadena=$cadena.$item->porcentajeDescuentoLinea2.';';
                $cadena=$cadena.$item->valorDescuentoLinea2.';';
                $cadena=$cadena.$item->porcentajeDescuentoLinea3.';';
                $cadena=$cadena.$item->valorDescuentoLinea3.';';
                $cadena=$cadena.$item->porcentajeDescuentoLinea4.';';
                $cadena=$cadena.$item->valorDescuentoLinea4.';';
                $cadena=$cadena.$item->porcentajeDescuentoLinea5.';';
                $cadena=$cadena.$item->valorDescuentoLinea5.';';
                $cadena=$cadena.$item->valorTotalDescuentosdeLinea.';';
                $cadena=$cadena.''.$item->partida.''.';';
                $cadena=$cadena.''.$item->pieza.''.';';
                $cadena=$cadena.''.$item->fechaVencimiento.''.';';
                $cadena=$cadena.''.$item->serie.''.';';
                $cadena=$cadena.''.$item->cuentadeConsumodelMovimento.''.';';
                $cadena=$cadena.''.$item->conservaFolioAsignadoalDTE.''.';';
                $cadena=$cadena.''.$item->referencia1_TipoDocumento.''.';';
                $cadena=$cadena.''.$item->referencia1_Descripcion.''.';';
                $cadena=$cadena.$item->referencia1_NumeroDocumento.';';
                $cadena=$cadena.''.$item->referencia1_FechaDocumento.''.';';
                $cadena=$cadena.''.$item->referencia2_TipoDocumento.''.';';
                $cadena=$cadena.''.$item->referencia2_Descripcion.''.';';
                $cadena=$cadena.$item->referencia2_NumeroDocumento.';';
                $cadena=$cadena.''.$item->referencia2_FechaDocumento.''.';';
                $cadena=$cadena.''.$item->referencia3_TipoDocumento.''.';';
                $cadena=$cadena.''.$item->referencia3_Descripcion.''.';';
                $cadena=$cadena.$item->referencia3_NumeroDocumento.';';
                $cadena=$cadena.''.$item->referencia3_FechaDocumento.''.';';
                $cadena=$cadena.''.$item->referencia4_TipoDocumento.''.';';
                $cadena=$cadena.''.$item->referencia4_Descripcion.''.';';
                $cadena=$cadena.$item->referencia4_NumeroDocumento.';';
                $cadena=$cadena.''.$item->referencia4_FechaDocumento.''.';';
                $cadena=$cadena.''.$item->referencia5_TipoDocumento.''.';';
                $cadena=$cadena.''.$item->referencia5_Descripcion.''.';';
                $cadena=$cadena.$item->referencia5_NumeroDocumento.';';
                $cadena=$cadena.''.$item->referencia5_FechaDocumento.''.';';
                
                $cadena = str_replace(
                array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ', 'º'),
                array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n', ''),
                $cadena
                );

                fwrite($fh, $cadena.chr(13).chr(10));
            }
            
            fclose($fh);
           

            $cadena = file_get_contents($nombreArchivo);

            $base64File = base64_encode($cadena); 

            $opts = array(
                'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false)
            );

            $ctx_opts = array(
                'http' => array(
                    'header' => 'Content-Type: application/soap+xml'
                )
            );

            $params = array ('soap_version' => SOAP_1_2 );
            $url = "http://webservice.quimicalatinoamericana.cl/CapturaDteExterno/CapturaDteExterno.svc?wsdl";

            $ruta="\\\QLSA-2012\Softland-ERP\SOFTLAND\DATOS\QLSA";
            try{
                $client = new SoapClient($url);
                $respuesta = $client->CaptudaGuiaSalida( [ 
                                'base64File'=> $base64File, 
                                'extensionArchivo'=> 'TXT', 
                                'areaDeDatos'=> $ruta, 
                                'usuario'=> 'hans', 
                                'nombreCertificadoDigital'=>'PATRICIO CLEMENTE SEGUEL  BUNSTER'
                            ])->CaptudaGuiaSalidaResult;
                $result = (array) $respuesta;

                if($result["FolioDte"]){
                    // spUpdGuiaEmitidaActualizaCliente, esto marca al cliente la primera vez que se emite una guía de forma correcta, para que en
                    // las proximas guías solo envía el codigo de despacho y NO incluya direccion, comuna y ciudad.
                    //DB::Select('call spUpdGuiaEmitidaActualizaCliente(?)', array($datos->input('numeroGuia') ));
                     
                    DB::Select('call spGetUpdFolioDTE(?,?,?)', array( $datos->input('numeroGuia'), $result["FolioDte"], Session::get('idUsuario') ) );
                }

                $despacho=DB::Select('call spGetVerificaDespachoCompleto(?)', array($idPedido) );
                
                if($result["PdfenBase64"]){
                    // a route is created, (it must already be created in its repository(pdf)).
                    $nombrePdf=public_path().'/guias/pdf/GD'.$result["FolioDte"].'.pdf';

                    // decode base64
                    $pdf_b64 = base64_decode($result["PdfenBase64"]);

                    // you record the file in existing folder
                    file_put_contents($nombrePdf, $pdf_b64 );                    
                }

                $descripcionError="";
                if( $result["Error"] ){
                    $err= (array) $result["Error"];
                    $descripcionError=$err["Descripcion"];
                }

                return response()->json([
                    "FolioDte" => $result["FolioDte"],
                    "Error" => $descripcionError,
                    "despachoCompleto" => $despacho[0]->despachoCompleto
                ]);                
            }
            catch(SoapFault $fault) {
                echo '<br>'.$fault;
            }
        }

    }


    public function testGuiaTxt($numeroGuia){
        $nombreArchivo=public_path().'/guias/txt/G'.$numeroGuia.'.txt';
        $guiaElectronica=DB::Select('call spGetFormatoGuiaElectronica(?)', array($numeroGuia) );          
        $fh = fopen($nombreArchivo, 'w');
        $idPedido=0;
        foreach ( $guiaElectronica as $item){

            $idPedido=$item->numeroOrdenProduccion;

            $cadena='';
            $cadena=$cadena.''.$item->bodega.''.';';
            $cadena=$cadena.$item->numeroGuia.';';
            $cadena=$cadena.''.$item->tipoDocumento.''.';';
            $cadena=$cadena.''.$item->subTipoDocumento.''.';';
            $cadena=$cadena.''.$item->fechaGeneracion.''.';';
            $cadena=$cadena.''.$item->ConceptoSalida.''.';';
            $cadena=$cadena.''.$item->observacion.''.';';
            $cadena=$cadena.''.$item->codigoClienteSoftland.''.';';
            $cadena=$cadena.''.strtoupper($item->nombreCliente).''.';';
            $cadena=$cadena.''.strtoupper($item->rutCliente).''.';';
            $cadena=$cadena.''.$item->giroCliente.''.';';
            $cadena=$cadena.''.$item->direccionCliente.''.';';
            $cadena=$cadena.''.$item->comunaCliente.''.';';
            $cadena=$cadena.''.$item->ciudadCliente.''.';';
            $cadena=$cadena.''.$item->centroCosto.''.';';
            $cadena=$cadena.''.$item->codigoBodegaDestino.''.';';
            $cadena=$cadena.''.$item->codigoListaPrecio.''.';';
            $cadena=$cadena.$item->numeroOrdenTrabajo.';';
            $cadena=$cadena.$item->numeroOrdenProduccion.';';
            $cadena=$cadena.$item->OrdenCompra.';';
            $cadena=$cadena.$item->facturaAsociada.';';
            $cadena=$cadena.''.$item->subTipoFacturaAsociada.''.';';
            $cadena=$cadena.$item->notaCreditoAsociada.';';
            $cadena=$cadena.''.$item->codigoCentroCostoparaContabilizar.''.';';
            $cadena=$cadena.''.$item->codigoVendedor.''.';';
            $cadena=$cadena.''.$item->codigoCondiciondePago.''.';';
            $cadena=$cadena.''.$item->codigoLugarDespacho.''.';';
            $cadena=$cadena.''.$item->direccionDespacho.''.';';
            $cadena=$cadena.''.$item->comunaDespacho.''.';';
            $cadena=$cadena.''.$item->ciudadDespacho.''.';';
            $cadena=$cadena.''.$item->paisDespacho.''.';';
            $cadena=$cadena.''.$item->atencion.''.';';
            $cadena=$cadena.''.$item->provincia.''.';';
            $cadena=$cadena.''.$item->region.''.';';
            $cadena=$cadena.$item->codigoPostalDespacho.';';
            $cadena=$cadena.''.$item->codigoGLN.''.';';
            /*$cadena=$cadena.''.$item->.''.';';*/
            $cadena=$cadena.$item->codigoVendedorWalmart.';';
            $cadena=$cadena.''.$item->retiradoPor.''.';';
            $cadena=$cadena.''.$item->patenteCamionDespacho.''.';';
            $cadena=$cadena.''.$item->solicitadoPor.''.';';  
            $cadena=$cadena.''.$item->rutTransportista.''.';';
            $cadena=$cadena.''.$item->despachadoPor.''.';';
            $cadena=$cadena.''.$item->rutSolicitante.''.';';
            $cadena=$cadena.$item->tipoDespacho.';';
            $cadena=$cadena.$item->numeroNotaVenta.';';
            $cadena=$cadena.$item->pordentajeDescuento1.';';
            $cadena=$cadena.$item->valorDescuento1.';';
            $cadena=$cadena.$item->pordentajeDescuento2.';';
            $cadena=$cadena.$item->valorDescuento2.';';
            $cadena=$cadena.$item->pordentajeDescuento3.';';
            $cadena=$cadena.$item->valorDescuento3.';';
            $cadena=$cadena.$item->pordentajeDescuento4.';';
            $cadena=$cadena.$item->valorDescuento4.';';
            $cadena=$cadena.$item->pordentajeDescuento5.';';
            $cadena=$cadena.$item->valorDescuento5.';';
            $cadena=$cadena.$item->flete.';';
            $cadena=$cadena.$item->embalaje.';';
            $cadena=$cadena.$item->totalFinal.';';
            $cadena=$cadena.''.$item->codigoProductoSF.''.';';
            $cadena=$cadena.''.$item->descripcionProducto.''.';';
            $cadena=$cadena.''.$item->codigoUnidadMedida.''.';';
            $cadena=$cadena.''.$item->descripcionProducto1.''.';';
            $cadena=$cadena.$item->cantidadDespachada.';';
            $cadena=$cadena.$item->precioReferencia.';';
            $cadena=$cadena.$item->porcentajeDescuentoLinea1.';';
            $cadena=$cadena.$item->valorDescuentoLinea1.';';
            $cadena=$cadena.$item->porcentajeDescuentoLinea2.';';
            $cadena=$cadena.$item->valorDescuentoLinea2.';';
            $cadena=$cadena.$item->porcentajeDescuentoLinea3.';';
            $cadena=$cadena.$item->valorDescuentoLinea3.';';
            $cadena=$cadena.$item->porcentajeDescuentoLinea4.';';
            $cadena=$cadena.$item->valorDescuentoLinea4.';';
            $cadena=$cadena.$item->porcentajeDescuentoLinea5.';';
            $cadena=$cadena.$item->valorDescuentoLinea5.';';
            $cadena=$cadena.$item->valorTotalDescuentosdeLinea.';';
            $cadena=$cadena.''.$item->partida.''.';';
            $cadena=$cadena.''.$item->pieza.''.';';
            $cadena=$cadena.''.$item->fechaVencimiento.''.';';
            $cadena=$cadena.''.$item->serie.''.';';
            $cadena=$cadena.''.$item->cuentadeConsumodelMovimento.''.';';
            $cadena=$cadena.''.$item->conservaFolioAsignadoalDTE.''.';';
            $cadena=$cadena.''.$item->referencia1_TipoDocumento.''.';';
            $cadena=$cadena.''.$item->referencia1_Descripcion.''.';';
            $cadena=$cadena.$item->referencia1_NumeroDocumento.';';
            $cadena=$cadena.''.$item->referencia1_FechaDocumento.''.';';
            $cadena=$cadena.''.$item->referencia2_TipoDocumento.''.';';
            $cadena=$cadena.''.$item->referencia2_Descripcion.''.';';
            $cadena=$cadena.$item->referencia2_NumeroDocumento.';';
            $cadena=$cadena.''.$item->referencia2_FechaDocumento.''.';';
            $cadena=$cadena.''.$item->referencia3_TipoDocumento.''.';';
            $cadena=$cadena.''.$item->referencia3_Descripcion.''.';';
            $cadena=$cadena.$item->referencia3_NumeroDocumento.';';
            $cadena=$cadena.''.$item->referencia3_FechaDocumento.''.';';
            $cadena=$cadena.''.$item->referencia4_TipoDocumento.''.';';
            $cadena=$cadena.''.$item->referencia4_Descripcion.''.';';
            $cadena=$cadena.$item->referencia4_NumeroDocumento.';';
            $cadena=$cadena.''.$item->referencia4_FechaDocumento.''.';';
            $cadena=$cadena.''.$item->referencia5_TipoDocumento.''.';';
            $cadena=$cadena.''.$item->referencia5_Descripcion.''.';';
            $cadena=$cadena.$item->referencia5_NumeroDocumento.';';
            $cadena=$cadena.''.$item->referencia5_FechaDocumento.''.';';

            $cadena = str_replace(
            array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ', 'º'),
            array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n', ''),
            $cadena
            );

            fwrite($fh, $cadena.chr(13).chr(10));
        }
        
        fclose($fh);
    }

    public function datosGuiaDespacho(Request $datos){
        if($datos->ajax()){
            $guia=DB::Select('call spGetGuiaDespacho(?,?)', array( $datos->input('tipoGuia'), $datos->input('numeroGuia') ) );
            return $guia;      
        }        
    }

    public function datosGuiaDespachoDetalle(Request $datos){
        if($datos->ajax()){
            $detalle=DB::Select('call spGetGuiaDespachoDetalle(?)', array( $datos->input('numeroGuia') ) );
            return $detalle;
        }
    }

    public function subirCertificado(Request $data){
        $archivo=$data->file("miArchivo");
        $nombreArchivo= $data->input("codigoTipoGuia")."_".$data->input("numeroGuiaCertificado").
        "_".$data->input("codigoProducto")."_RND".strval(random_int(1,100000)).".pdf";


        $nombreArchivo_upload=public_path().'/certificados/'.$nombreArchivo;
        file_put_contents($nombreArchivo_upload, \File::get( $archivo) );

        DB::Select('call spUpdArchivoCertificado(?,?,?,?,?,?)', array( $data->input('codigoTipoGuia'), $data->input('numeroGuiaCertificado'), $data->input("codigoProducto"), $nombreArchivo, Session::get('idUsuario'), '' ) );

 /*       dd(
            $data->input('codigoTipoGuia'), 
            $data->input('numeroGuiaCertificado'), 
            $data->input("codigoProducto"),
            $nombreArchivo
        );
*/

        return $nombreArchivo; 
    }

    public function productoSinCertificado(Request $data){
        $nombreArchivo= "S/C";
        DB::Select('call spUpdArchivoCertificado(?,?,?,?,?,?)', array( $data->input('codigoTipoGuia'), $data->input('numeroGuiaCertificado'), $data->input("codigoProducto"), $nombreArchivo, Session::get('idUsuario'), $data->input('motivo') ) );
        return response()->json([
            "numeroGuia" => $data->input('numeroGuiaCertificado')
        ]);    
    }

    public function subirGuiaDespachoPdf(Request $data){
        $guia=DB::Select('call spGetGuiaDespacho(?,?)', array( 1, $data->input('nuevoFolioDTE') ) );

        if(count($guia)){
            return response()->json([
                "folioExiste" => "1"
            ]);
        }


        $archivo=$data->file("upload-demo");
        $nombreArchivo=public_path().'/guias/pdf/GD'.$data->input("nuevoFolioDTE").'.pdf';
        $nombreSinruta=$data->input("nuevoFolioDTE").'.pdf';
        file_put_contents($nombreArchivo, \File::get( $archivo) );

        $guia=DB::Select('call spUpdArchivoGuiaPDF(?,?,?,?)', array( $data->input('numGuia'), $data->input('nuevoFolioDTE'), $nombreSinruta, Session::get('idUsuario') ) );
        $despacho=DB::Select('call spGetVerificaDespachoCompleto(?)', array($guia[0]->idPedido) );
        return response()->json([
            "nombreArchivo" => $nombreArchivo,
            "despachoCompleto" => $despacho[0]->despachoCompleto
        ]);         
        return ; 
    }

    public function bajarCertificado($file){
      $pathtoFile = public_path().'/certificados/'.$file;

      $nombreArchivo = asset(env('CARPETA_PUBLIC_ASSET').'certificados/'.$file);
      //$nombreArchivo = asset('certificados/'.$file);
      $result = File::exists($pathtoFile); 
      if($result){
            return view('verpdf')->with('nombreArchivo', $nombreArchivo);
      }      
    }


    public function bajarGuiaDespacho($numeroGuia){
      $pathtoFile = public_path().'/guias/pdf/GD'.$numeroGuia.'.pdf';
      $nombreArchivo = asset(env('CARPETA_PUBLIC_ASSET').'guias/pdf/GD'.$numeroGuia.'.pdf');
      $result = File::exists($pathtoFile);
      if($result){
            return view('verpdf')->with('nombreArchivo', $nombreArchivo);
      }else{
            return;
      }
    }

    public function eliminarCertificado(Request $datos){
        if($datos->ajax()){
            $guia=DB::Select('call spUpdCertificado(?,?,?,?,?)', array( 
                $datos->input('numeroGuia'),
                $datos->input('prodcodigo'), 
                $datos->input('ucodigo'), 
                $datos->input('idPlanta'), 
                $datos->input('nombreCertificado')  ) );
            
            return $guia;      
        }        
    }

    public function modificarCertificado(){

        
        $titulo='Gestión de certificados';

        return view('gestionCertificados')->with('titulo', $titulo);        
    }

    public function obtenerCertificados(Request $datos){
        if($datos->ajax()){
            $guias=DB::Select('call spGetModificarCertificado(?,?,?,?,?,?,?)', array( 
                Session::get('idUsuario'), 
                Session::get('idPerfil'),  
                $datos->input('pedidoDesde'),
                $datos->input('pedidoHasta'),
                $datos->input('guiaDesde'),
                $datos->input('guiaHasta'),
                $datos->input('opcion')
            ) );
            return $guias;
        }
    }

    public function eliminacionGuiaDespacho(){
        $parametros=DB::table('parametros')->select('iva', 'maximo_toneladas_por_pedido', 'maximo_productos_por_pedido', 'notaPedido1', 'notaPedido2','consideracionesPedidosGranel','version')->get();        
        return view('eliminacionGuiaDespacho')->with('parametros', $parametros);
    }

    public function eliminarGuiaDespacho(Request $datos){
        if($datos->ajax()){
            $guia=DB::Select('call spDelGuiaDespacho(?,?,?)', array( $datos->input('numeroGuia'), $datos->input('motivo'), Session::get('idUsuario')  ) );
            return $guia;      
        }            
    } 


    public function datosEtiqueta(Request $datos){
        $guia=DB::Select('call spGetDatosEtiqueta(?)', array( $datos->input('numeroGuia') ) );
        return $guia;
    }

    public function avisoRegistrodeSalida($numeroGuia){

        $guia=DB::Select('call spGetGuiaDespacho(?, ?)', array(1, $numeroGuia));

        $mj = new \Mailjet\Client('a72036914344c6d8d14466fdd90a9515','a058d7e4f920dfc4a0f34995c92034f8',true,['version' => 'v3.1']);

        $correoDestinatario=$guia[0]->usu_correo;

        $mensaje="<h3>AVISO DE SALIDA DE DESPACHO</h3><br><br>";
        $mensaje=$mensaje."Estimado Usuario,<br><br>";
        $mensaje=$mensaje."Se ha registrado la salida del Pedido Nº ".strval($guia[0]->idPedido).", con la guía de despacho Nº ".strval($numeroGuia). 
        " perteneciente al cliente ".$guia[0]->nombreCliente.".";

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
                    'Email' => 'nbastias@spsgroup.cl',
                    'Name' => 'Natalia Bastias'
                  ],                  
/*                   [
                   'Email' => $correoDestinatario,
                    'Name' => $correoDestinatario
                  ]*/
                ],
                'Subject' => "AVISO DE SALIDA DE DESPACHO",
                'TextPart' => "",
                'HTMLPart' => $mensaje,
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();       
        $response->getData();
        return;
    }

    public function guiasPorFacturar(){
        $clientes=DB::Select('call spGetEmpresas');
        return view('guiasPorFacturar')->with('clientes', $clientes);
    }

    public function consultaDtesEmitidos(){
        $clientes=DB::Select('call spGetEmpresas');
        return view('consultaDtesEmitidos')->with('clientes', $clientes);        
    }

    public function liberarDte(){
        return view('liberarDte');
    }    

}
