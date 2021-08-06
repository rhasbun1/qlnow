<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});	
Route::post('verificarusuario', 'UsuarioController@verificarusuario');
Route::post('validarUsuario', 'UsuarioController@validarUsuario');
Route::get('autorizarPedidoUrgente/{token}/', 'PedidoController@autorizarPedidoUrgente');
Route::get('obtenerIdPlanta', 'PlantaController@obtenerIdPlanta');

Route::get('guiasPorFacturar', 'GuiaController@guiasPorFacturar');
Route::get('consultaDtesEmitidos', 'GuiaController@consultaDtesEmitidos');
Route::get('liberarDte', 'GuiaController@liberarDte');
Route::get('devolucionesEnProceso/{tipo}/', 'DevolucionController@devolucionesEnProceso');
Route::get('verDevolucion', 'DevolucionController@verDevolucion');
/* Cambiar Version */
Route::get('nuevaVersion', 'Controller@nuevaVersion');
Route::get('notaVentas', 'Controller@notaVentas');


Route::group(['middleware' => 'checksession'], function () {
	Route::get('informacion/{idPlanta}/', 'Controller@informacion');
	Route::get('parametros', 'Controller@parametros');
	Route::get('datosUsuario', 'UsuarioController@datosUsuario');
	Route::post('obtenerParametros', 'Controller@obtenerParametros');
	Route::post('grabarParametros', 'Controller@grabarParametros');
	Route::post('validarCodigoSoftlandEmpresa','EmpresaController@validarCodigoSoftlandEmpresa');
	Route::post('cargarPerfil', 'UsuarioController@cargarPerfil');
	Route::post('usuarioPlantas', 'UsuarioController@usuarioPlantas');
	Route::post('usuarioAvisosCorreo', 'UsuarioController@usuarioAvisosCorreo');

	Route::post('agregarObra', 'ObraController@agregarObra');
	Route::post('listarObras', 'ObraController@listarObras');
	Route::post('eliminarObra', 'ObraController@eliminarObra');
	Route::post('datosObra', 'ObraController@datosObra');
	Route::post('datosNotaVenta', 'NotaventaController@datosNotaVenta');
	Route::post('grabarNuevaNotaVenta', 'NotaventaController@grabarNuevaNotaVenta');
	Route::post('existeArchivo', 'NotaventaController@existeArchivo');
	Route::post('existeArchivoNotaDeVenta', 'NotaventaController@existeArchivoNotaDeVenta');
	Route::post('actualizarDatosNV', 'NotaventaController@actualizarDatosNV');
	Route::get('notaVentaVigenteCargos', 'NotaventaController@notaVentaVigenteCargos');
	Route::post('notaVentaVigenteCargos1', 'NotaventaController@notaVentaVigenteCargos1');
	Route::get('notaVentaCerradaCargos', 'NotaventaController@notaVentaCerradaCargos');
	Route::post('notaVentaCerradaCargos1', 'NotaventaController@notaVentaCerradaCargos1');
	Route::get('notaVentaCargosUrgente', 'NotaventaController@notaVentaCargosUrgente');
	Route::post('notaVentaCargosUrgente1', 'NotaventaController@notaVentaCargosUrgente1');
	Route::post('actualizarNotaVentaCargos', 'NotaventaController@actualizarNotaVentaCargos');
	Route::post('obtenerHistoricoNotaVentas', 'NotaventaController@obtenerHistoricoNotaVentas');

	Route::get('bajarOCnventa/{nombreArchivo}/', 'NotaventaController@bajarOCnventa');
	Route::get('bajarOCpedido/{nombreArchivo}/', 'PedidoController@bajarOCpedido');
	Route::post('subirOCnotaventa', 'NotaventaController@subirOCnotaventa');
	Route::post('subirOCpedido', 'PedidoController@subirOCpedido');
	Route::post('grabarPlanta', 'PlantaController@grabarPlanta');
	Route::post('eliminarPlanta', 'PlantaController@eliminarPlanta');
	Route::post('grabarUsuario', 'UsuarioController@grabarUsuario');
	Route::post('eliminarProductoListaPrecio', 'ProductoController@eliminarProductoListaPrecio');
	Route::post('verificarProductoEnListadePrecios', 'ProductoController@verificarProductoEnListadePrecios');

	Route::post('grabarNuevoPedido',  'PedidoController@grabarNuevoPedido');
	Route::post('aprobarPedido',  'PedidoController@aprobarPedido');
	Route::post('aprobarPedidoCliente',  'PedidoController@aprobarPedidoCliente');
	Route::post('guardarDatosProgramacion',  'PedidoController@guardarDatosProgramacion');
	Route::post('actualizarNumeroAuxiliar', 'PedidoController@actualizarNumeroAuxiliar');
	Route::post('agregarEmpresaTransporte',  'EmpresaTransporteController@agregarEmpresaTransporte');
	Route::post('apiPlantas', 'PlantaController@apiPlantas');
	Route::post('selectPlantas', 'PlantaController@selectPlantas');
	Route::post('verificarTiempoProduccion', 'PlantaController@verificarTiempoProduccion');
	Route::post('apiFormadeEntrega', 'FormadeentregaController@apiFormadeEntrega');
	Route::post('datosCotizacion', 'CotizacionController@datosCotizacion');
	Route::post('EmpresaConductores',  'EmpresaTransporteController@EmpresaConductores');
	Route::post('EmpresaCamiones',  'EmpresaTransporteController@EmpresaCamiones');
	Route::post('listaCamiones',  'CamionController@listaCamiones');
	Route::post('listaConductores',  'ConductorController@listaConductores');
	Route::post('cotizacionProductos', 'CotizacionController@cotizacionProductos');
	Route::post('detalleNotaVenta', 'PedidoController@detalleNotaVenta');
	Route::post('traerPlantaNotaVenta', 'PedidoController@traerPlantaNotaVenta');

	Route::post('actualizarPedido', 'PedidoController@actualizarPedido');
	Route::post('productosconPedidoPendientePorFechaEntrega', 'PedidoController@productosconPedidoPendientePorFechaEntrega');
	Route::post('productosconPedidoPendientePorFechaCarga', 'PedidoController@productosconPedidoPendientePorFechaCarga');
	Route::post('guardarDatosCliente', 'EmpresaController@guardarDatosCliente');
	Route::post('guardarDatosProducto', 'ProductoController@guardarDatosProducto');
	Route::post('agregarNota', 'PedidoController@agregarNota');
	Route::post('eliminarNota', 'PedidoController@eliminarNota');
	Route::post('buscarTiempoProduccion', 'PedidoController@buscarTiempoProduccion');
	Route::post('buscarTiempoTraslado', 'PedidoController@buscarTiempoTraslado');
	Route::post('guardarAcciones', 'PedidoController@guardarAcciones');
	Route::post('buscarFeriados', 'PedidoController@buscarFeriados');
	Route::post('verificarFlete', 'PedidoController@verificarFlete');
	Route::post('crearGuiaDespachoElectronica', 'GuiaController@crearGuiaDespachoElectronica');
	Route::post('registrarSalidaDespacho', 'GuiaController@registrarSalidaDespacho');
	Route::post('actualizarDatosGuiaDespacho', 'GuiaController@actualizarDatosGuiaDespacho');
	Route::post('datosGuiaDespacho', 'GuiaController@datosGuiaDespacho');
	Route::post('emitirGuiaDespacho', 'GuiaController@emitirGuiaDespacho');
	Route::post('eliminarCertificado', 'GuiaController@eliminarCertificado');
	Route::get('eliminacionGuiaDespacho', 'GuiaController@eliminacionGuiaDespacho');
	Route::post('eliminarGuiaDespacho', 'GuiaController@eliminarGuiaDespacho');
	Route::post('obtenerCertificados', 'GuiaController@obtenerCertificados');
	Route::post('productoSinCertificado', 'GuiaController@productoSinCertificado');
	Route::post('validarNumeroGuia', 'GuiaController@validarNumeroGuia');
	Route::post('verificarFeriado', 'PedidoController@verificarFeriado');

	Route::post('grabarCamion', 'CamionController@grabarCamion');
	Route::post('eliminarCamion', 'CamionController@eliminarCamion');
	Route::post('grabarConductor', 'ConductorController@grabarConductor');
	Route::post('eliminarConductor', 'ConductorController@eliminarConductor');

	Route::post('subirCertificado', 'GuiaController@subirCertificado');
	Route::post('subirGuiaDespachoPdf', 'GuiaController@subirGuiaDespachoPdf');

	Route::post('solicitarSessionIDSitrack', 'SitrackController@solicitarSessionIDSitrack');
	
	Route::post('productosCodigosSoftland', 'ProductoController@productosCodigosSoftland');
	Route::post('guardarDatosProductoListaPrecio', 'ProductoController@guardarDatosProductoListaPrecio');
	Route::post('actualizarCostos', 'ProductoController@actualizarCostos');
	
	Route::post('actualizarValoresNotaVenta', 'NotaventaController@actualizarValoresNotaVenta');

	Route::get('aprobarnota/{idNotaVenta}/', 'NotaventaController@aprobarnota');
	Route::get('Desaprobarnota/{idNotaVenta}/', 'NotaventaController@Desaprobarnota');
	Route::get('cerrarNotaVenta/{idNotaVenta}/{motivo}/', 'NotaventaController@cerrarNotaVenta');
	Route::get('clienteNotaVentas', 'NotaventaController@clienteNotaVentas');
	Route::post('agregarUsuarioNotaVenta', 'NotaventaController@agregarUsuarioNotaVenta');
	Route::post('usuarioNotasdeVenta', 'NotaventaController@usuarioNotasdeVenta');
	Route::post('eliminarUsuarioNotaVenta', 'NotaventaController@eliminarUsuarioNotaVenta');

	Route::get('desaprobarPedido/{idPedido}/', 'PedidoController@desaprobarPedido');
	Route::post('suspenderPedido', 'PedidoController@suspenderPedido');
	Route::post('cerrarPedido', 'PedidoController@cerrarPedido');
	Route::get('clienteNotasdeVenta', 'NotaventaController@clienteNotasdeVenta');
	Route::get('guiasEnProceso', 'GuiaController@guiasEnProceso');
	Route::get('bajarCertificado/{file}/', 'GuiaController@bajarCertificado');
	Route::get('bajarGuiaDespacho/{numeroGuia}/', 'GuiaController@bajarGuiaDespacho');
	Route::post('crearguiatxt', 'GuiaController@crearguiatxt');
	Route::get('crearguiaGet/{numeroGuia}/', 'GuiaController@crearguiaGet');
	Route::get('conectaWebservice', 'GuiaController@conectaWebservice');
	Route::post('datosGuiaDespachoDetalle', 'GuiaController@datosGuiaDespachoDetalle');
	Route::get('testRuta', 'GuiaController@testRuta');
	Route::get('listaGuiasDespacho', 'GuiaController@listaGuiasDespacho');
	Route::get('modificarCertificado', 'GuiaController@modificarCertificado');
	Route::get('verPedidosDespachados', 'PedidoController@verPedidosDespachados');
	Route::post('obtenerPedidosDespachados','PedidoController@obtenerPedidosDespachados');
	Route::post('crearCostosMensuales', 'PedidoController@crearCostosMensuales');
	Route::post('costosMensualesProductos', 'PedidoController@costosMensualesProductos');
	Route::post('verificarMesEn0', 'PedidoController@verificarMesEn0');
	Route::post('costosMensualesProductosen0', 'PedidoController@costosMensualesProductosen0');


	Route::post('obtenerIdProductoListaPrecio', 'PedidoController@obtenerIdProductoListaPrecio');
	Route::post('guardarProductoListaPrecio', 'PedidoController@guardarProductoListaPrecio');
	Route::get('aprobarnotaventa', 'NotaventaController@AprobarNotasdeVenta');
	Route::get('aprobarpedidos', 'PedidoController@AprobarPedidos');
	Route::get('listarNotasdeVenta', 'NotaventaController@listarNotasdeVenta');
	Route::get('historicoNotasdeVenta', 'NotaventaController@historicoNotasdeVenta');
	Route::get('vernotaventa/{id}/{accion}', 'NotaventaController@vernotaventa');
	Route::get('listarPedidos', 'PedidoController@listaPedidos');
	Route::get('clientePedidos', 'PedidoController@clientePedidos');
	Route::get('historicoPedidos', 'PedidoController@historicoPedidos');
	Route::get('editarPedido/{idPedido}/', 'PedidoController@editarPedido');
	Route::get('verResumenGranel', 'PedidoController@verResumenGranel');
	Route::post('resumenGranel', 'PedidoController@resumenGranel');
	Route::post('validarProductoCodigoSoftland', 'ProductoController@validarProductoCodigoSoftland');



	Route::get('listarNotasVentaModal','NotaventaController@listarNotasdeVentaModal');
	
	Route::get('correoAutorizacionPedidoUrgente/{idPedido}/{idUsuario}/', 'PedidoController@correoAutorizacionPedidoUrgente');

	Route::get('listaIngresosClienteporAprobar', 'PedidoController@listaIngresosClienteporAprobar');

	Route::get('programacion', 'PedidoController@programacion');
	Route::get('dashboard', 'Controller@dashboard');
	Route::get('volver', 'Controller@volver');
	Route::get('terminarSesion', 'UsuarioController@terminarSesion');
	Route::get('listaEmpresas', 'EmpresaController@listaEmpresas');
	Route::get('listaCondicionesdePago', 'CondiciondePagoController@listaCondicionesdePago');
	Route::post('guardarDatosCondicionPago', 'CondiciondePagoController@guardarDatosCondicionPago');
	Route::post('eliminarCondiciondePago', 'CondiciondePagoController@eliminarCondiciondePago');

	Route::get('listaPlantas', 'PlantaController@listaPlantas');
	Route::get('listaProductos', 'ProductoController@listaProductos');
	Route::get('listaFeriados', 'FeriadosController@listaFeriados');
	Route::post('guardarDatosFeriado', 'FeriadosController@guardarDatosFeriado');
	Route::post('eliminarFeriado', 'FeriadosController@eliminarFeriado');
	Route::post('filtrarFeriados', 'FeriadosController@filtrarFeriados');
	Route::get('listaUsuarios', 'UsuarioController@listaUsuarios');
	Route::get('listadeObras', 'ObraController@listadeObras');
	Route::get('listaEmpresasTransporte', 'EmpresaTransporteController@listaEmpresas');
	Route::post('deshabilitaEmpresaTransporte', 'EmpresaTransporteController@deshabilitaEmpresaTransporte');
	Route::post('habilitaEmpresaTransporte', 'EmpresaTransporteController@habilitaEmpresaTransporte');
	Route::get('verpedido/{idPedido}/{accion}/', 'PedidoController@verpedido');
	Route::get('verpedidoNuevaVentana/{idPedido}/{accion}/', 'PedidoController@verpedidoNuevaVentana');
	Route::get('clienteVerPedido/{idPedido}/{accion}/', 'PedidoController@clienteVerPedido');
	Route::get('programarpedido/{idPedido}/{accion}/', 'PedidoController@programarpedido');
	Route::get('costosMensuales', 'PedidoController@costosMensuales');
	Route::get('datosEmpresaTransporte/{id}/', 'EmpresaTransporteController@datosEmpresaTransporte');
	Route::get('registroSalida', 'GuiaController@registroSalida');

	Route::get('listaRamplas', 'RamplasController@listaRamplas');
	Route::post('guardarRampla', 'RamplasController@guardarRampla');
	Route::post('eliminarRampla', 'RamplasController@eliminarRampla');
	Route::post('editarRampla', 'RamplasController@editarRampla');

	Route::get('despachosPorMes', 'ReportesController@despachosPorMes');
	Route::get('despachosPorAno', 'ReportesController@despachosPorAno');
	Route::get('notasdeVentaMargenes', 'ReportesController@notasdeVentaMargenes');
	
	Route::get('imprimirNotaVenta/{id}/', 'NotaventaController@imprimirNotaVenta');

	Route::get('testGuiaTxt/{numeroGuia}/', 'GuiaController@testGuiaTxt');

	Route::get('test', 'GuiaController@test');
	Route::get('sendemail', function () {
		$data = array('name'=> 'Curso Laravel');

		Mail::send('email', $data, function ( $message) {
			$message->from('daviddiaz1402@gmail.com', 'Curso Laravel');
			$message->to('daviddiaz1402@gmail.com')->subject('test email Curso Laravel');
		});

		return 'Tu email ha sido enviado correctamente';
	});

	
	Route::post('obtenerHistoricoPedidos', 'PedidoController@obtenerHistoricoPedidos');
	Route::get('clienteGestionarPedido/{idNotaVenta}/','PedidoController@clienteGestionarPedido');
	Route::get('registroAcciones', 'AccionesController@registroAcciones');
	Route::post('consultarProductoAcciones', 'AccionesController@consultarProductoAcciones');
	Route::post('consultarRegistroAcciones', 'AccionesController@consultarRegistroAcciones');
	Route::post('subirArchivoCostos', 'ProductoController@subirArchivoCostos');
	Route::post('plantaspedidos', 'PedidoController@plantaspedidos');

	Route::resource('nuevanotaventa', 'NotaventaController', ['except' => 'show']);
	Route::resource('gestionarpedido/{idNotaVenta}', 'PedidoController', ['except' => 'show']);


	

});

