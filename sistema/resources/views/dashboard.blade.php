@extends('plantilla')      

@section('contenedorprincipal')
<div class="padding-md">
	<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">

  
@if (Session::get('grupoUsuario')!='CL' and (Session::get('idPerfil') == '2' || Session::get('idPerfil') == '4' || Session::get('idPerfil') == '18'))

	
<div class="col-md-6">
		
		<a href="#modalToneladasDespachadasMensual"  data-toggle="modal">
			<div class="panel-stat3 bg-warning btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelDespachadoEsteMes }} ton</h2>
        <font size=4><strong>Toneladas (granel) despachadas este Mes</strong></font>
        <br>
			</div>
      
		</a>
		</span>
	</div>

	<div class="col-md-6">
		<a href="#modalToneladasDespachadasAnual"  data-toggle="modal">
			<div class="panel-stat3 bg-warning btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelAcumuladoAnual }} ton</h2>
        <font size=4><strong>Toneladas (granel) despachadas este Año</strong></font>
        <br>
			</div>
		</a>
	</div>


	
	<div class="col-md-3">
		<a href="#modalPendienteAprobacion" data-target="" id="notasVen"  data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%" >
				<h2 class="m-top-none">{{ $datos[0]->cantidadNventasPendientesAprobacion }}</h2>
        <h4>Notas de Venta </h4>
        <h4>pendientes de aprobación</h4>
        
			</div>
		</a>
	</div>
	
  <div class="col-md-3">
    <a href="#modalJefePedidosAtrasados" data-toggle="modal">
        <div class="panel-stat3 bg-info btn" style="width:100%">
          <h2 class="m-top-none">{{ $datos3[0]->cantidadPedidosAtrasados }}</h2>
          <h3>Pedidos Atrasados</h3>
          <br>
          

        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="#modalPedidosSinAprobar" data-toggle="modal">
          <div class="panel-stat3 bg-info btn" style="width:100%">
            <h2 class="m-top-none">{{ $datos[0]->cantidadPedidosSinAprobar }}</h2>
            <p> <h4>Pedidos pendientes de</h4>
            <h4>Aprobación de Crédito</h4></p>
          </div>
        </a>
      </div>
	

	
	
@endif
@if (Session::get('grupoUsuario')!='CL' and (Session::get('idPerfil') == '3' || Session::get('idPerfil') == '19'))
	
	<div class="col-md-6">
		
		<a href="#modalToneladasDespachadasMensual"  data-toggle="modal">
			<div class="panel-stat3 bg-warning btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelDespachadoEsteMes }} ton</h2>
        <font size=5><strong>Toneladas (granel) despachadas este Mes</strong></font>
        <br>
			</div>
		</a>
		</span>
	</div>

	<div class="col-md-6">
		<a href="#modalToneladasDespachadasAnual"  data-toggle="modal">
			<div class="panel-stat3 bg-warning btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelAcumuladoAnual }} ton</h2>
        <font size=5><strong>Toneladas (granel) despachadas este Año</strong></font>
        <br>
			</div>
		</a>
	</div>


	<div class="col-md-3">
		<a href="{{ asset('/') }}#modalPedidoClientePendientes"  data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadPedidosIngresadosClienteSinAprobar }}</h2>
        <h5>Pedidos Pendientes de </h5>
        
        <p><font size=5><strong>Preaprobación</strong></font></p>

        <font size=1>(ingresados por clientes)</font>
        
			</div>
		</a>

	</div>

  <div class="col-md-3">
    <a href="#modalJefePedidosAtrasados" data-toggle="modal">
        <div class="panel-stat3 bg-info btn" style="width:100%">
          <h2 class="m-top-none">{{ $datos3[0]->cantidadPedidosAtrasados }}</h2>
          <br>
          <h4>Pedidos Atrasados </h4>
          <br>
        </div>
      </a>
    </div>


    <div class="col-md-3">
      <a href="#modalPedidosSinAprobar" data-toggle="modal">
          <div class="panel-stat3 bg-info btn" style="width:100%">
            <h2 class="m-top-none">{{ $datos[0]->cantidadPedidosSinAprobar }}</h2>
            <p> <h4>Pedidos pendientes de</h4>
              <h4>Aprobación de Crédito</h4></p>
              <br>    
          </div>
        </a>
      </div>
	
      

	
@endif
@if (Session::get('grupoUsuario')!='CL' and (Session::get('idPerfil') == '18' || Session::get('idPerfil') == '2' || Session::get('idPerfil') == '4'))
	
@endif
@if ((Session::get('idPerfil') == '14' || Session::get('idPerfil') == '15'))
	<div class="col-md-3">
	<a href="#modalPedidoClienteEnProceso"   data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $CantidadPedidosEnProcesoCliente[0]->cantidadPedidos }}</h2>
        <h4>Pedidos en Proceso</h4>
        <br>
			</div>
		</a>
	</div>
	<div class="col-md-3">
	<a href="{{ asset('/') }}historicoPedidos"   data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadPedidosDespachadosCliente }}</h2>
        <h4>Pedidos Despachados</h4>
        <br>
			</div>
		</a>
  </div>
  
  <div class="col-md-3">

    <a href="#modalPedidosSinAprobar1" data-toggle="modal">
        <div class="panel-stat3 bg-info btn" style="width:100%">
          <h2 class="m-top-none">{{ $CantidadPedidosPendienteAprobacionVB[0]->cantidadVB }}</h2>
          <p> <h4>Pedidos pendientes de</h4>
            <h4>Aprobación de Crédito</h4></p>
        </div>
      </a>
    </div>


	

@endif
@if ((Session::get('idPerfil') == '6' || Session::get('idPerfil') == '7' || Session::get('idPerfil') == '8' || Session::get('idPerfil') == '5'))
	<div class="col-md-3">
			<a href="#modalJefePedidosEnProceso" data-toggle="modal">
				<div class="panel-stat3 bg-info btn" style="width:100%">
					<h2 class="m-top-none">{{ $datos1[0]->cantidadPedidosEnProceso }} </h2>
          <h4>Pedidos En Proceso</h4>
          <br>
				</div>
			</a>
		</div>

		<div class="col-md-3">
		<a href="#modalPedidosAGranelSinAsignacionHorario"  data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos2[0]->cantidadPedidosGranelSinAsignacion }}</h2>
        <h4>Pedidos a granel sin Horario de Carga</h4>
        <br>
			</div>
		</a>
	</div>
	
	<div class="col-md-3">
	<a href="#modalJefePedidosAtrasados" data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos3[0]->cantidadPedidosAtrasados }}</h2>

				<h4>Pedidos Atrasados </h4>
        <br>
			</div>
		</a>
	</div>



@endif

@if ((Session::get('idPerfil') == '9'))

<div class="col-md-3">
		<a href="#modalJefeLabCertificados"  data-toggle="modal">	
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $cantidadPedidosSinCertificado[0]->cantidad }}</h2>
        <h4>Certificados pendientes</h4>
        <br>
			</div>
		</a>
	</div>
	
	<div class="col-md-3 text-center">
	<a href="#modalJefeLabModificadoHaceUnaHora"  data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadPedidosModificadosUltimaHora }}</h2>
        <h5>Pedidos Modificados</h5>
        <h5> durante las últimas 24 horas</h5>
			</div>
		</a>
	</div>


@endif

@if ((Session::get('idPerfil') == '11'))

<div class="col-md-6">
  <a href="#modalPedidosSinAprobar" data-toggle="modal">
      <div class="panel-stat3 bg-warning btn" style="width:102%">
        <h2 class="m-top-none">{{ $datos[0]->cantidadPedidosSinAprobar}}</h2>
        <h3><strong>Pedidos pendientes de Aprobación de Crédito</strong></h3>
      </div>
    </a>
  </div>


<div class="col-md-3">
		<a href="#modalToneladasDespachadasMensual" data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelDespachadoEsteMes }} ton</h2>
        <h4>Toneladas (granel) </h4>
        <h4>despachadas este Mes</h4>
			</div>
		</a>
	</div>
	
	<div class="col-md-3">
		<a href="#modalToneladasDespachadasAnual" data-toggle="modal">
			<div class="panel-stat3 bg-info btn" style="width:100%">
				<h2 class="m-top-none">{{ $datos[0]->cantidadGranelAcumuladoAnual }} ton</h2>
      <h4>Toneladas (granel) </h4>
      <h4>despachadas este Año</h4>
			</div>
		</a>
  </div>
  

  


@endif

@if ((Session::get('idPerfil') == '10'))
<div class="row">
    <div class="col-md-6">
        <a href="#NotaVentaSinFlete"  data-toggle="modal">
          <div class="panel-stat3 bg-warning btn" style="width:100%">
            <h2 class="m-top-none">{{ $datos[0]->cantidadNotaVentaConFleteSinAsignar }}</h2>
            <font size=5><strong>Asignaciones de Flete pendientes</strong></font>
            <br>
            <br>
          </div>
        </a>
    </div>


    <div class="col-md-3">
        <a href="#modalPedidoSinTransporteAsignado" data-toggle="modal">
          <div class="panel-stat3 bg-info btn" style="width:100%">
            <h2 class="m-top-none">{{ $datos[0]->cantidadPedidosSinTransporteAsignado }}</h2>
            <h4>Pedidos sin Transporte </h4>
            <h4>asignado</h4>
          </div>
        </a>
    </div>

    <div class="col-md-3">

        <a href="#modalAtrasadoTransporte" data-toggle="modal">
          <div class="panel-stat3 bg-info btn" style="width:100%">
            <h2 class="m-top-none">{{ $datos[0]->cantidadPedidosAtrasadosTransporte }}</h2>
              
            <h4>Pedidos Atrasados</h4>
            <h4>(despacho pendiente)</h4>
            
          </div>
        </a>
    </div>
</div>

{{-- <div class="row">
  <div class="col-md-3">

  <a href="#" data-toggle="modal">
    <div class="panel-stat3 bg-info btn" style="width:100%">
    <h2 class="m-top-none">0</h2>
      <h4>Devoluciones pendientes de</h4>
      <h4>VB° de Jefe de Transporte</h4>
    </div>
  </a>
</div>
</div> --}}






@endif

</div>


<!-- Modals -->



<div class="modal fade" id="modalAtrasadoTransporte"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Pedidos Atrasados (despacho pendiente)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalAtrasadoTransporte" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Estado</th>
                <th >Fecha Creación</th>
                <th >Planta de Origen </th>
                <th >Cliente</th>
                <th >Obra/Planta</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th>Unidad</th>
                <th >Fecha Entrega</th>
                
              
               
                    
                </thead>
                
                <tbody>
                @foreach($listaPedidoAtrasadoTransporte as $item)
                               <tr>
                                
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->estado }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 120px">{{ $item->nombreProducto }}</td>
                                    <td style="width: 120px">{{ $item->cantidad }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    
                                  
                                    
                                </tr>
                @endforeach           
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>





  <div class="modal fade" id="modalPedidoSinTransporteAsignado"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:90%" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Pedidos sin Transporte asignado</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalPedidoSinTransporteAsignado" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Id Pedido</th> 
                <th >Cliente</th> 
                <th >Planta de Origen</th> 
                <th>Obra/Planta </th>
                <th >Producto</th>
                <th>Unidad</th>
                <th >Fecha de Carga</th>
              
               
               
                    
                </thead>
                
                
                
                <tbody>
                @foreach($listaPedidoSinFechaDeCarga as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->nombreEmpresa }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                    <td style="width: 120px">{{ date('d/m/Y', strtotime($item->fechaCarga)) }}</td>
                                    
                                   
                                    
                                </tr>
                @endforeach            
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="NotaVentaSinFlete"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Asignaciones de Flete pendientes</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaNotaVentaSinFlete" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>

                <th >Nota de Venta</th>  
                <th >Cliente</th>  
                <th >Planta de Origen</th>  
                <th>Obra/Planta</th>       
               

                </thead>
                
                
               
                <tbody>
                @foreach($listaNotaVentaSinFlete as $item)
                            
                                <tr>
                                    <td style="width: 120px">{{ $item->idNotaVenta }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td> 
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                   
                                   
                                    
                                </tr>
                @endforeach          
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalJefeLabModificadoHaceUnaHora"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos Modificados durante las últimas 24 horas</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalJefeLabModificadoHaceUnaHora" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th>Pedido</th>
                <th >Tipo</th>             
                <th >Motivo</th>
                <th >Fecha Hora</th>
                <th >Nombre Usuario</th>
                <th>Planta de Origen</th>
                <th>Cliente</th>
                <th>Obra Planta </th>
               
               
                    
                </thead>
                
                
                
                <tbody>
                @foreach($listaAccionesHaceUnaHora as $item)
    
                            
                                <tr>
                                  <td style="width:50px">{{ $item->idPedido }}</td>
                                  <td style="width:120px ">{{ $item->tipo }}</td>
                                  <td style="width: 120px">{{ $item->motivo }}</td>
                                  <td style="width:120px ">{{ date('d/m/Y h:m', strtotime($item->fechaHora))  }}</td>
                                  <td style="width: 120px">{{ $item->nombreUsuario }}</td>
                                  <td style="width: 120px">{{ $item->nombre }}</td>
                                  <td style="width:120px ">{{ $item->nombreEmpresa }}</td>
                                  <td style="width: 120px">{{ $item->nombreObra }}</td>
                                   
                                    
                                </tr>
               @endforeach            
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalJefeLabCertificados"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Certificados Pendientes</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalJefeLabCertificados" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Planta de Origen</th>
                <th >Cliente</th>
                <th >Obra/Planta</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th>Unidad</th>               
                <th >Fecha de carga</th>

                
               
                    
                </thead>
                
                
              
                <tbody>
                @foreach($listaJefeLabCertificadosPorSubir as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->plantaOrigen }}</td>
                                    <td style="width: 120px">{{ $item->emp_nombre }}</td>
                                    <td style="width: 120px">{{ $item->obra }}</td>
                                    <td style="width:120px ">{{ $item->prod_nombre }}</td>           
                                    <td style="width: 120px;text-align: right;">{{number_format( $item->cantidadDespachada, 0, ',', '.' ) }}</td>
                                    <td style="width: 120px">{{ $item->unidad }}</td>
                                    <td style="width: 120px">{{ $item->fechaHoraCarga }}</td>

                                </tr>
                @endforeach            
                </tbody>
               
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalJefePedidosAtrasados"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos Atrasados (despacho pendiente)</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalJefePedidosAtrasados" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Estado</th>
                <th >Fecha Creación</th>
                <th>Planta de Origen</th>
                <th >Cliente</th>
                <th>Obra/Planta</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th>Unidad</th>
                <th>Fecha Entrega</th>
                
                
                </thead>
               
                <tbody>
                @foreach($listaJefePedidosAtrasados as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->estadoPedido }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    <td style="width: 30px;text-align: right;">{{number_format( $item->cantidad, 0, ',', '.' ) }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                   
                                   
                                </tr>
                @endforeach          
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="modalPedidosAGranelSinAsignacionHorario"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos a granel sin Horario de Carga</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalPedidosAGranelSinAsignacionHorario" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Estado</th>
                <th>Planta de Origen</th>
                <th >Cliente</th>
                <th>Obra/Planta</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th>Unidad</th>
               
                    
                </thead>
                
                <tbody>
                @foreach($listaJefeGranelSinAsignacionDeHorario as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->estadoPedido }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    
                                    <td style="width: 30px;text-align: right;">{{number_format( $item->cantidad, 0, ',', '.' ) }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                </tr>
                @endforeach         
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalJefePedidosEnProceso"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Pedidos en Proceso</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalJefePedidosEnProceso" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>
                <th >Planta de Origen</th>             
                <th >Estado</th>
                <th >Fecha Creación</th>
                <th >Cliente</th>
                <th> Planta/Obra</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th>Unidad</th>
                <th>Fecha de Entrega</th>
                <th style="display: none;">Cantidad</th>
               
                    
                </thead>
                
                  
               
                <tbody>
                @foreach($listaJefePedidoEnProceso as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 120px">{{ $item->estadoPedido }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td> 
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                   
                                    <td style="width: 30px;text-align: right;">{{number_format( $item->cantidad, 0, ',', '.' ) }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    <td style="display: none;">{{ $item->cantidad }}</td>

                                </tr>
                @endforeach         
                </tbody>
               
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalPedidoClienteEnDespacho"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Pedidos Atrasados </h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalPedidoClienteEnDespacho" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Estado</th>
              
                <th >Cliente</th>
                <th >Fecha Creación</th>
                <th >Producto</th>
                
              
               
                    
                </thead>
                
                <tbody>
                @foreach($listaPedidoClienteEnDespacho as $item)
                            
                                <tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->estadoPedido }}</td>
                                    
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                
                                </tr>
                @endforeach         
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalPedidosSinAprobar1"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos Pendientes de Aprobación de Crédito</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalPedidosSinAprobar" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th> 
                <th >Fecha Creación</th>            
                <th >Estado</th>
                <th >Cliente</th>
                <th>Obra/Planta</th>
                <th >Producto</th>
                <th>Fecha de Entrega</th>
                <th>Total ($)</th>


               
                    
                </thead>
                
                <tbody>
                @foreach($listaPedidoSinAprobarClientes as $item)
                                <tr>
                                
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->estado }}</td>
                                    <td style="width: 120px">{{ $item->emp_nombre }}</td>
                                    <td style="width: 120px">{{ $item->Obra }}</td>
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    <td style="width: 120px">{{ number_format($item->total, 0, ",", ".") }}</td>



                                    
                                  
                                    
                                </tr>

                                @endforeach         
                </tbody>
               
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalPedidosSinAprobar"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos Pendientes de Aprobación de Crédito</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalPedidosSinAprobar1" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th> 
                <th >Fecha Creación</th>            
                <th >Estado</th>
                <th >Cliente</th>
                <th>Obra/Planta</th>
                <th >Producto</th>
                <th>Fecha de Entrega</th>
                <th>Total Neto($)</th>


               
                    
                </thead>
                
                <tbody>
                @foreach($listaPedidoSinAprobar as $item)
                                <tr>
                                
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->estado }}</td>
                                    <td style="width: 120px">{{ $item->emp_nombre }}</td>
                                    <td style="width: 120px">{{ $item->Obra }}</td>
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    <td style="width: 120px">{{ number_format($item->totalNeto,0,",",".") }}</td>



                                    
                                  
                                    
                                </tr>

                                @endforeach         
                </tbody>
               
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>







<div class="modal fade" id="modalPedidoClienteEnProceso"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Pedidos en Proceso</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalClienteEnProceso" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>  
                <th >Fecha Creación</th>           
                <th >Estado</th>
                
                <th >Obra</th>
                <th >Producto</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Fecha de Entrega</th>
                
              
               
                    
                </thead>
                
                <tbody>
                @foreach($listaPedidoClienteEnProceso as $item)
                                <tr>
                                
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->estadoPedido }}</td>
                                    
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 120px">{{ $item->prod_nombre }}</td>
                                    <td style="width: 120px">{{ $item->cantidad }}</td>
                                    <td style="width: 120px">{{ $item->u_nombre }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    
                                    
                                  

                                </tr>

                                @endforeach         
                </tbody>
               
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>






<div class="modal fade" id="modalToneladasDespachadasAnual"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Toneladas (granel) despachadas este Año</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalToneladasDespachadasAnual" class="table table-hover table-condensed table-responsive" style="width: 100%">
	  <thead>
					<th style="width:80px;text-align: right;">Nº de Pedido</th>       
                    <th>Planta Origen</th>
                    <th style="width: 200px">Cliente</th>
                    <th>Obra/Planta</th>
                    <th>Producto</th>
    				        <th style="width: 60px">Cantidad</th>
					          <th>Transporte</th>
                    <th>Conductor</th>
                    <th style="width: 80px">Fecha Salida</th>
    	</thead>
                
                <tbody>
				
                @foreach($listatoneladasAnuales as $item)
                
                <?php
                    $fecha = explode(" ", $item->fechaHoraSalida);
                
                ?>
				<tr>
                            <td>{{ $item->idPedido }}</td>
                            <td>{{ $item->plantaQLSA }}</td>
                            <td>{{ $item->nombreCliente }}</td>
                            <td>{{ $item->nombreObra }}</td>
                            <td>{{ $item->prod_nombre }}</td>
                            <td>{{ $item->cantidadDespachada }}</td>
                            <td>{{ $item->nombreTransporte }}</td>
                            <td>{{ $item->nombreConductor }}</td>
                            <td>{{ date('d/m/Y', strtotime($fecha[0]))  }} {{$fecha[1]}}</td>
				</tr>
                @endforeach        
				
                </tbody>
               
               
			</table> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="modalToneladasDespachadasMensual"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Toneladas (granel) despachadas este Mes</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalToneladasDespachadasMensual" class="table table-hover table-condensed table-responsive" style="width: 100%">
				<thead>
					<th style="width:80px;text-align: center;">Nº de Pedido</th>
                    
                    <th>Planta Origen</th>
                    <th style="width: 200px">Cliente</th>
                    <th>Obra/Planta</th>
                    <th>Producto</th>
    				        <th style="width: 60px">Cantidad</th>
					          <th>Transporte</th>
                    <th>Conductor</th>
                    <th style="width: 80px">Fecha Salida</th>
					
				</thead>
                
                <tbody>
                @foreach($listaToneladasaMensuales as $item)
                <?php
                    $fecha = explode(" ", $item->fechaHoraSalida);
                
                ?>
               
				<tr>  
                            <td>{{ $item->idPedido }}</td>
                            <td>{{ $item->plantaQLSA }}</td>
                            <td>{{ $item->nombreCliente }}</td>
                            <td>{{ $item->nombreObra }}</td>
                            <td>{{ $item->prod_nombre }}</td>
                            <td>{{ $item->cantidadDespachada }}</td>
                            <td>{{ $item->nombreTransporte }}</td>
                            <td>{{ $item->nombreConductor }}</td>
                            <td>{{  date('d/m/Y', strtotime($fecha[0]))  }} {{$fecha[1] }}</td>
							</tr>
                @endforeach        
                </tbody>
               
               
			</table> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalPedidosEnProceso"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:90%" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h2 class="modal-title" id="exampleModalLabel">Pedidos en Proceso</h2>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaPedidosEnProceso" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <th >Pedido</th>             
                <th >Estado</th>
                <th >Cliente</th>
                <th >Obra/Planta</th>
                <th >Producto</th>
                <th >Cantidad</th>
                <th >Unidad</th>
                <th>Fecha Entrega</th>
                <th >Forma Entrega</th>
                <th >Planta Origen</th>
                <th >Fecha Carga<br>Programada</th>
                <th >Transporte</th>
                <th >Fecha Creación</th>
                <th >Nº Aux.</th>
                    
                </thead>
                
                <tbody>
				@foreach($listaPedidosEnProceso as $item)
                            
				<tr>
                                    <td style="width: 50px">{{ $item->idPedido }}</td>
                                                                        
                                    <td style="width: 50px">{{ $item->estadoPedido }}</td>
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 70px">
                                        {{ $item->prod_nombre }}                                   
                                    </td>
                                    <td style="width: 30px;text-align: right;">{{number_format( $item->cantidad, 0, ',', '.' ) }}</td>
                                    <td style="width: 30px;text-align: center">{{ $item->u_nombre }}</td>
                                    <td style="width: 100px">{{ $item->fechaEntrega }} {{ $item->horarioEntrega }}</td>
                                    <td style="width: 70px">{{ $item->formaEntrega }}</td>
                                    <td style="width: 70px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 100px">{{ $item->fechaCarga }} {{ $item->horaCarga }} </td>
                                    <td style="width: 150px">{{ $item->apellidoConductor }} / {{ $item->empresaTransporte }}</td>
                                    <td style="width: 50px;text-align: right;">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 80px;text-align: center;">{{ $item->numeroAuxiliar }}</td>
                                </tr>
				@endforeach  
                </tbody>
                
         </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalPendienteAprobacion"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Notas de Venta pendientes de Aprobación</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <table id="tablaNotaVentaPendiente" class="table table-hover table-condensed table-responsive" style="width:100%">
                <thead>

                <th>Fecha Creación</th>
                    <th>Cliente</th>
                    <th>Obra/Planta</th>
                    <th>Ejecutivo QL</th>
                    <th>Estado</th>
                    

                </thead>
                
                <tbody id="notaVentas1">
              
                    @foreach($listaNotasdeVenta as $item1)
                      <tr>
                                        
                          <td>{{ $item1->fechahora_creacion }}</td>        
                          <td>{{ $item1->emp_nombre }}</td>
                          <td>{{ $item1->Obra }}</td>
                          <td>{{ $item1->nombreUsuarioEncargado }}</td>
                          @if($item1->aprobada==1)
                                  <td>Aprobado</td>
                          @else
                                  <td>Pendiente de Aprobación</td>
                          @endif  

                      </tr>
                    @endforeach
			          
              </tbody>
            </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalPedidoClientePendientes"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="ModalPedidoCliente">Pedidos Pendientes de Preaprobación (ingresados por clientes)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id="tablaModalClientePendiente" class="table table-hover table-condensed table-responsive" style="width:100%">
                <thead>

                    <th>Pedido Nº</th>
                    
                    <th>Fecha Creación</th>
                    <th>Cliente</th>
                    <th>Obra/Planta</th>
                    @if( Session::get('idPerfil')=='11' )
                        <th><b>Total c/IVA</b></th>
                    @else
                        <th style="display: none"><b>Total c/IVA</b></th>
                    @endif    
                    <th>Fecha Entrega</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>

                </thead>
                
                <tbody>
				@foreach($listaPedidosIngresadosporClientesSinAprobar as $item)
                 <tr>
                            
                            <td>{{ $item->idPedido }}</td>                   
                            <td>{{ $item->fechahora_creacion }}</td>
                            <td>{{ $item->emp_nombre }}</td>
                            <td>{{ $item->Obra }}</td>
                            @if( Session::get('idPerfil')=='11' )
                                <td align="right"><b>$ {{ number_format( $item->totalNeto + $item->montoIva, 0, ',', '.' ) }}</b></td>
                            @else
                                <td align="right" style="display: none"><b>$ {{ number_format( $item->totalNeto + $item->montoIva, 0, ',', '.' ) }}</b></td>
                            @endif    
                            <td>{{ $item->fechaEntrega }}</td>
                            <td>{{ $item->prod_nombre }}</td>
                            <td>{{ number_format( $item->cantidad, 0, ',', '.' ) }}</td> 
                            <td>{{ $item->u_nombre }}</td>
                        </tr>
                @endforeach
				</tbody>
            </table>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>





@endsection




@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script> 
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  



   <script src="{{ asset('/') }}js/app/funciones.js"></script>

    

    <script>
        function actualizarFiltros(tabla){
            tabla.columns(0).every( function () {
                var column = this;
                var select = $("#selProducto" ).empty().append( '<option value=""></option>' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
            tabla.columns(1).every( function () {
                var column = this;
                var select = $("#selUnidad" ).empty().append( '<option value=""></option>' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
            tabla.columns(2).every( function () {
                var column = this;
                var select = $("#selPlanta" ).empty().append( '<option value=""></option>' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }


        $(document).ready(function() {
            // DataTable
            var table=$('#tablaModalClientePendiente').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Pendientes de Preaprobación (ingresados por clientes)',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,6,7 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,"initComplete": function () {
                    actualizarFiltros(this.api());
                }        
			});

      // $(document).on('click','#notasVen', e=>
      // {
      //   $.ajax({
      //       url: urlApp + "notaVentas",
      //       type: "POST",
      //       data:{},
      //       dataType: "JSON",
      //       success: function(data) {

      //         var html = '';
      //         var i;
      //         for (i = 0; i < data.length; i++) {
      //           html += '<tr>' +
      //           '<td>'+data[i].fechahora_creacion+'</td>' +
      //           '<td>'+data[i].emp_nombre+'</td>' +
      //           '<td>'+data[i].Obra+'</td>' +
      //           '<td>'+data[i].nombreUsuarioEncargado+'</td>' 
      //           if(data[i].aprobada==1){
      //            + '<td>'+"Aprobado"+'</td>' 
      //           }else{
      //            + '<td>'+"Pendiente de Aprobación"+'</td>'
      //           } 
      //           + '<tr>';
      //         }
      //         $('#notaVentas1').html(html);
      //       }
      //   })
      // });
        
      var table=$('#tablaModalJefeLabModificadoHaceUnaHora').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Modificados durante las últimas 24 horas',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			});
			 // DataTable
			 var table=$('#tablaNotaVentaPendiente').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Notas de Venta pendientes de Aprobación',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData
			
			 // DataTable
			 var table=$('#tablaPedidosEnProceso').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos en Proceso',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,6,7,8,9,10,11,12,13 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData
			
			 // DataTable
			 var table=$('#tablaModalToneladasDespachadasMensual').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Toneladas (granel) despachadas este Mes',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,6,7,8 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData
			
			 // DataTable
			 var table=$('#tablaModalToneladasDespachadasAnual').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Toneladas (granel) despachadas este Año',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7,8]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData
			

			 // DataTable
			 var table=$('#tablaModalClienteEnProceso').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos en Proceso',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			 // DataTable
			 var table=$('#tablaModalPedidoClienteEnDespacho').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Atrasados',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			// DataTable
			var table=$('#tablaModalJefePedidosEnProceso').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos en Proceso',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,10,8,9]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			// DataTable
			var table=$('#tablaModalPedidosAGranelSinAsignacionHorario').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos a granel sin Horario de Carga',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			// DataTable
			var table=$('#tablaModalJefePedidosAtrasados').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Atrasados (despacho pendiente)',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7,8,9]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

				// DataTable
				var table=$('#tablaModalJefeLabCertificados').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Certificados Pendientes',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			
				// DataTable
				var table=$('#tablaModalPedidosSinAprobar1').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
                dom: 'Bfrtip',
                buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Pendientes de Aprobación de Crédito',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7]
                        }
                    }               
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData
     
				// DataTable
				var table=$('#tablaNotaVentaSinFlete').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Asignaciones de Flete pendientes',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			// DataTable
			var table=$('#tablaModalPedidoSinTransporteAsignado').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos sin Transporte asignado',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,6 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

			// DataTable
			var table=$('#tablaModalAtrasadoTransporte').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos Atrasados (aún sin despacharse)',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7,8,9]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

      	// DataTable
			var table=$('#tablaModalClienteSinAprobar').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos sin aprobar',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

      
      	// DataTable
			var table=$("#tablaModalPedidosSinAprobar").DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                lengthMenu: [[6, 12, 20, 40], ["6", "12", "20", "40"]],
				dom: 'Bfrtip',
				buttons: [
                                  
                                            
                    'pageLength',                
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos pendientes de Aprobación de Crédito',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ,5,6,7]
                        }
                    }
                ],                
                                
                "order": [[ 0, "desc" ]],
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"}
                ,initComplete: function () {
                    actualizarFiltros(this.api());
                }  
                              
			}); //finData

        } );
        function addCommas(nStr) { 
            nStr += ''; 
            var x = nStr.split('.'); 
            var x1 = x[0]; 
            var x2 = x.length > 1 ? '.' + x[1] : ''; 
            var rgx = /(\d+)(\d{3})/; 
            while (rgx.test(x1)) { 
              x1 = x1.replace(rgx, '$1' + ',' + '$2'); 
            } 
            return x1 + x2; 
        }




    </script>
    
@endsection




