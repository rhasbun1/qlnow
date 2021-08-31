<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function listaUsuarios()
    {
        //
        $listaUsuarios=DB::Select('call spGetUsuarios()');
        $plantas=DB::table('plantas')->select('idPlanta', 'nombre')->get();
        return view('usuarios')->with('listaUsuarios', $listaUsuarios)->with('plantas', $plantas);
    }

    public function datosUsuario()
    {
      $datos=DB::Select('call spGetDatosUsuario(?)', array( Session::get('idUsuario') ) );
      return view('datosUsuario')->with('datosUsuario', $datos);
    }


    public function verificarusuario(Request $datos)
    {
        //
        if($datos->ajax()){
           $usuario=DB::Select('call validarUsuario(?, ?)', array($datos->input("email"), $datos->input("password")) );
           if($usuario[0]->usu_codigo!='-1'){

               $perfiles=DB::Select('call spGetUsuarioPerfiles(?)', array($usuario[0]->usu_codigo) );
               Session::put('idUsuario', $usuario[0]->usu_codigo);
               Session::put('nombreUsuario', $usuario[0]->nombreUsuario);
               Session::put('correoUsuario', $usuario[0]->usu_correo);
               Session::put('grupoUsuario', $usuario[0]->grupo);
               Session::put('empresaUsuario', $usuario[0]->emp_codigo);
               Session::put('empresaNombre', $usuario[0]->emp_nombre);
               Session::put('idPlanta', $usuario[0]->idPlanta);
               Session::put('idPerfil', $usuario[0]->per_codigo);
               return $perfiles;           

           }else{
               /*Session::put('idUsuario', $usuario[0]->usu_codigo);
   /*            Session::put('nombreUsuario', $usuario[0]->nombreUsuario);
               Session::put('correoUsuario', $usuario[0]->usu_correo);
               Session::put('idPerfil', $usuario[0]->per_codigo);           */
               return $usuario;   
           }

           
        }
    }

    public function cargarPerfil(Request $datos){
        if($datos->ajax()){
            Session::put('idPerfil', $datos->input("idPerfil") );
            Session::put('nombrePerfil', $datos->input("nombrePerfil") );
            Session::put('grupoUsuario', $datos->input("grupo") );
            return response()->json([
                "dato" => "1"
            ]);         
        }
    }


       
    public function validarUsuario(Request $datos)
    {
        //
        if($datos->ajax()){ 
           $usuario=DB::Select('call validarUsuario(?, ?)', array($datos->email, $datos->password) );
           return $usuario;
        }
    }


    public function terminarSesion(){
        Session::flush();
        return redirect('/');
    }

    public function grabarUsuario(Request $datos){
      if( $datos->ajax() ){
          $detalle=$datos->input('plantas');
          $detalle= json_decode($detalle);

          foreach ( $detalle as $item){
            DB::Select("call spUpdUsuarioPlanta(?,?,?)", array( $datos->input('idUsuario'), $item->idPlanta, $item->accion ) );
          }

          $plantas=DB::Select("call spGetUsuarioNombrePlantasAsignadas(?)", array( $datos->input('idUsuario') ));

          return response()->json([
              "idUsuario" => $datos->input('idUsuario'),
              "plantas" => $plantas[0]->plantas
          ]);
      }      
    }

    public function usuarioPlantas(Request $datos){
        if($datos->ajax()){ 
           $plantas=DB::Select('call spGetUsuarioPerfilPlantas(?, ?)', array($datos->idUsuario, -1) );
           return $plantas;
        }
    }

    public function usuarioAvisosCorreo( Request $datos){
        if($datos->ajax()){ 
           $usuario=DB::Select('call spUpdUsuarioAvisosCorreo(?,?,?)', array(Session::get('idUsuario'), $datos->despacho, $datos->novedades ) );
           return $usuario;
        }      
    }
    public function usuarioApagarNotificaciones(Request $datos){
        $usuario=DB::Select('call spUpdNotificaciones(?)', array($datos->codigo) );
        echo '<script>alert("Las notificaciones por correo fueran apagadas")</script>';
        return view('login');
    }

}
