@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 20px">
    <div class="panel panel-default table-responsive">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="periodoActual" value='{{ $periodoActual }}'
        <div class="panel-heading">
            <b>Costos  Mensuales</b>
        </div>

        <div class="padding-md clearfix" id="cuadro1">
          @if (Session::get('idPerfil')==2 or Session::get('idPerfil')==5 or Session::get('idPerfil')==18)
  	        <div class="row" style="padding-bottom: 15px">
              
  	        	<div class="col-md-1">
  	        		Año
  	        	</div>
  	        	<div class="col-md-1">
  	        		<input id="ano" class="form-control input-sm" maxlength="4" onkeypress="return isNumberKey(event)">
  	        	</div>
  	        	<div class="col-md-1">
  	        		Mes
  	        	</div>
  	        	<div class="col-md-2">
  	        		<select id="mes" class="form-control input-sm">
  	        			<option value="1">Enero</option>
  	        			<option value="2">Febrero</option>
  	        			<option value="3">Marzo</option>
  	        			<option value="4">Abril</option>
  	        			<option value="5">Mayo</option>
  	        			<option value="6">Junio</option>
  	        			<option value="7">Julio</option>
  	        			<option value="8">Agosto</option>
  	        			<option value="9">Septiembre</option>
  	        			<option value="10">Octubre</option>
  	        			<option value="11">Noviembre</option>
  	        			<option value="12">Diciembre</option>
  	        		</select>
  	        	</div>
  	        	<div class="col-md-1">
  	        		<button class="btn btn-success btn-sm" onclick="agregarMes()">Agregar</button>
  	        	</div>        	
  	        </div>
          @endif
	        <div style="width: 50%">        
	            <table id="tabla" class="table table-hover table-condensed table-responsive" style="width: 100%">
	                <thead>
	                    <th style="width:80px">Año</th>
	                    <th style="width:100px">Mes</th>
	                    <th style="width:40px"></th>
	                </thead>
	                <tbody>
	                    @foreach($costosMensuales as $item)
	                        <tr data-nummes="{{ $item->mes }}">
	                            <td>{{ $item->ano }}</td>
	                            <td>{{ $item->nombreMes}}</td>
	                            <td style="width:40px">
	                            	<button class="btn btn-xs btn btn-warning btnEditar" title="Ver Costos" onclick="listarCostosProductos(this.parentNode.parentNode);"><i class="fa fa-search fa-lg"></i></button>
                                @if (Session::get('idPerfil')==2 or Session::get('idPerfil')==5 or Session::get('idPerfil')==18)
                                  <button class="btn btn-xs btn btn-warning btnEditar" title="Subir Archivo de Costos" onclick="subirArchivoCostos(this.parentNode.parentNode);"><i class="fa fa-file-archive-o"></i></button>                                    
                                @endif
                                <button type="button"  class="btn-xs btn btn-info" onclick="verificarmes(this.parentNode.parentNode);">Revisión Costos en 0</button>
                                
	                            </td>
	                        </tr>
	                    @endforeach
                      
	                </tbody>
	            </table>
	        </div>
		    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
		        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
		    </div>        
        </div>
		<div class="padding-md clearfix" id="cuadro2" style="display: none">

    </div>
 
</div>


<div id="mdCostos" class="modal fade" role="dialog" style="z-index: 1400;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-1">
            Año
          </div>
          <div class="col-md-2">
            <input id="anoSel" class="form-control input-sm" readonly>
          </div>
          <div class="col-md-1">
            Mes
          </div>
          <div class="col-md-2">
            <input id="mesSel" class="form-control input-sm" readonly data-numeromes="0">
          </div>        
        </div>
        <br>
            <div style="width: 80%">        
                <table id="tablaProductos" class="table table-hover table-condensed table-responsive" style="width: 100%">
                    <thead>
                      <tr>
                        <th style="width:120px">Producto</th>
                        <th style="width:60px">Unidad</th>
                        <th style="width:80px">Planta</th>
                        <th style="width:80px">Costo ($)</th>
                        <th style="width:0px">Costo</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
          <div style="text-align: right;">
            @if (Session::get('idPerfil')==2 or Session::get('idPerfil')==5 or Session::get('idPerfil')==18)
                <button id="btnGuardarCambios" class="btn btn-sm btn-success" style="width:120px" onclick="abrirCuadroEspera();">Guardar Cambios</button>
                <button id="btnAgregarCosto" class="btn btn-sm btn-primary" style="width:120px" onclick="abrirAgregarCosto();">Agregar otro Costo</button>
            @endif
            <button class="btn btn-sm btn-warning" style="width:100px" onclick="cerrarListaProductos();">Cerrar</button>
          </div>  
        
      </div>      
    </div>
  </div>
</div>

<div id="mdAgregarCosto" class="modal fade" role="dialog" style="z-index: 1600;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <div class="row" style="padding-top: 20px">
            <div class="col-md-2">
              Producto
            </div>
            <div class="col-md-4">
              <select class="form-control input-sm" id="selProductos">
                  @foreach($productos as $item)
                      <option value="{{ $item->prod_codigo }}">{{ $item->prod_nombre }}</option>
                  @endforeach 
              </select>  
            </div>
            <div class="col-md-2">
              Unidad
            </div>
            <div class="col-md-4">
              <select class="form-control input-sm" id="selUnidades">
                  @foreach($unidades as $item)
                      <option value="{{ $item->u_codigo }}">{{ $item->u_nombre }}</option>
                  @endforeach 
              </select>
            </div> 
          </div><br>      
          <div class="row">
              <div class="col-md-2">
                Planta
              </div>
              <div class="col-md-4">
                <select class="form-control input-sm" id="selPlantas">
                  @foreach($plantas as $item)
                      <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                  @endforeach 
              </select>
              </div>        
              <div class="col-md-2">
                Costo
              </div>
              <div class="col-md-4">
                <input id="txtCosto" class="form-control input-sm" onkeypress="return isIntegerKey(event);">
              </div>        
          </div>
          <br>
          <div style="text-align: right;">
            @if (Session::get('idPerfil')==2 or Session::get('idPerfil')==5 or Session::get('idPerfil')==18)
                <button id="btnGuardarAgregarCosto" class="btn btn-sm btn-success" style="width:120px" onclick="guardarAgregarCostos();">Agregar</button>
            @endif
            <button class="btn btn-sm btn-warning" style="width:100px" onclick="cerrarAgregarCosto();">Cerrar</button>
          </div> 
          <div class="row" style="padding: 10px">
            <p>Debe seleccionar un producto NO EXISTENTE en el listado de costos del mes seleccionado. Si no encuentra aquí el producto deseado, debe solicitar primero que se agregue a la base de datos de productos.</p>
          </div>
      </div>      
    </div>
  </div>
</div>


<div id="mdProcesando" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" style="text-align: center">
          <img src="{{ asset('/') }}img/procesando.gif" alt="User Avatar">
      </div>
    </div>
  </div>
</div>

<div id="mdProcesandoArchivoCostos" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" style="text-align: center">
          <img src="{{ asset('/') }}img/procesando.gif" alt="User Avatar">
      </div>
    </div>
  </div>
</div>

<div id="modSubirArchivo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="filaTabla" name="filaTabla">
                <h5><b>Subir archivo de Costos</b></h5>
            </div>
            <div id="bodyProducto" class="modal-body">
                <form id="datos" name="datos" enctype="multipart/form-data">
                    <div class="row" style="padding-top: 15px;padding-left: 15px">
                      <b>El archivo a subir debe ser CSV, y los datos que debe contener son: Producto, Unidad, Planta y Costo.</b>
                    </div>
                    <div class="row" style="padding-top: 10px;padding-left: 15px">
                        <div class="upload-file">
                            <input type="file" id="upload-demo" name="upload-demo" class="upload-demo" style="width: 300px" accept=".csv" >
                            <label data-title="Buscar" for="upload-demo" id="labelUpload" style="width: 400px">
                                <span id="mensajeUpload" data-title="No ha seleccionado un archivo..." style="width: 300px"></span>
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-offset-8" style="padding-top: 20px; padding-bottom: 20px">
                       <button type="submit" class="btn btn-success btn-sm" style="width: 80px">Subir</button>
                       <button type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalSubirArchivo()" style="width: 80px">Salir</button>
                    </div>                   
                </form>     
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')
    <!-- Datepicker 
     <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>  -->

    <!-- Timepicker 
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  -->
    <script src="{{ asset('/') }}js/app/funciones.js"></script>
    <script src="{{ asset('/') }}js/syncfusion/ej.web.all.min.js"> </script>
    <script src="{{ asset('/') }}js/syncfusion/lang/ej.culture.de-DE.min.js"></script>

	<script>

        var anoSel=0;
        var mesSel=0;
        var excelMes;
        var excelAno;
        var titulo="Esta es una prueba";
        var objCajaNumero;
     

        var btnExcel={
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        title: 'Costos'+excelAno,
                        titleAttr: 'Excel',
                        filename: function(){
                            var a = excelAno;
                            var m = excelMes;
                            return 'Costos ' + m +'.'+a ;
                        },                           
                        exportOptions: {
                            columns: [ 0, 1, 2, 4 ]
                        }
                    };

        $('#datos').on('submit', function(e) {
          // evito que propague el submit
          e.preventDefault();
          // agrego la data del form a formData
          if( $("#upload-demo").val().trim()=='' ){
            swal(
                {
                    title: 'No ha seleccionado un archivo para subir!!' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(isConfirm){
                        return;                         
                    }
                }
            );
            return;
          }

          var nombreArchivo=$("#upload-demo").val().trim();
          var extension = nombreArchivo.slice(-3).toUpperCase();
          if( extension!='CSV'){
            swal(            
                {
                    title: 'Solo se permite subir achivos CSV!' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(isConfirm){
                        return;                         
                    }
                }
            );
            return;
          }

          var formData = new FormData( $("#datos")[0]);
          formData.append("observaciones", "" );
          formData.append("ano", anoSel);
          formData.append("mes", mesSel );
          
          $("#modSubirArchivo").modal('hide');
          $("#mdProcesandoArchivoCostos").modal('show');       
          $.ajax({
              url: urlApp + "subirArchivoCostos",
              headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
              type:'POST',
              timeout: 0,
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){
                $("#mdProcesandoArchivoCostos").modal('hide');
                if(data.length>0){
                  var cadena="";
                  for(var x=0;x<data.length;x++){
                      cadena+= data[x] + "<br>";
                  }
                  document.getElementById('bodyErrores').innerHTML=cadena;
                  $("#mdErrores").modal("show");
                }else{
                  swal(
                      {
                          title: 'El archivo ha sido Procesado!!' ,
                          text: '',
                          type: 'warning',
                          showCancelButton: false,
                          confirmButtonText: 'OK',
                          cancelButtonText: '',
                          closeOnConfirm: true,
                          closeOnCancel: false
                      },
                      function(isConfirm)
                      {
                          if(isConfirm){
                            $("#modSubirArchivo").modal('hide');
                            return;                         
                          }
                      }
                  );
                }     
              },
              error: function(jqXHR, text, error){
                $("#mdProcesandoArchivoCostos").modal('hide');
                  alert('Error!, No se pudo Añadir los datos');
              }
          });
        });

        $('#mdProcesando').on('shown.bs.modal', function (e) {
          guardarCostos();
        })  

        function abrirCuadroEspera(){
            $("#mdProcesando").modal('show');
            $("#mdCostos").modal('hide');
        }


        function subirArchivoCostos(row){
            var tabla=$("#tabla").DataTable();
            var datos=tabla.row( row ).data();
            var node=tabla.row(row).node();

            anoSel=datos[0];
            mesSel=node.dataset.nummes;

            document.getElementById('mensajeUpload').dataset.title="No ha seleccionado un archivo...";
            $("#modSubirArchivo").modal('show');
        }

        function cerrarModalSubirArchivo(){
            $("#modSubirArchivo").modal('hide');
        }  

		$(document).ready(function() {


            // Setup - add a text input to each footer cell
            $('#tablaProductos thead tr').clone(true).appendTo( '#tablaProductos thead' );
            $('#tablaProductos thead tr:eq(1) th').each( function (i) {
                if(i==0 ){
                    $(this).html( '<select id="selProducto" class="form-control input-sm"></select>' );
                }else if(i==1 ){
                    $(this).html( '<select id="selUnidad" class="form-control input-sm"></select>' );
                }else if(i==2 ){
                    $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );
                }else if(i==3 ){
                    $(this).html( '' );                                   
                }
            } );
                     

            // DataTable
            var table=$('#tabla').DataTable({
              orderCellsTop: true,
                 fixedHeader: true,         
                "lengthMenu": [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                dom: 'Bfrtip',
                "scrollX": true,
                buttons: [
                                 
                    'pageLength'
                ],
                columnDefs: [ {
                                "targets": [2],
                                "orderable": false
                                } ],                                        
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                "order": [[ 0, "desc" ]]              
            });

            var productos=$('#tablaProductos').DataTable({
                orderCellsTop: true,
                fixedHeader: true,  
                  
                columnDefs: [ {
                                "targets": [3],
                                "orderable": false
                                },
                              {
                                  "targets": [ 4 ],
                                  "visible": false,
                                  "searchable": false
                              } 
                            ],
                dom: 'Bfrtip',
                buttons: [
                    btnExcel,
                                  
                ],                                                
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function () {
                    actualizarFiltros(this.api());
                }                
            });             

        } );

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
            } ).search( '', true, false ).draw();

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
            } ).search( '', true, false ).draw();

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
            } ).search( '', true, false ).draw();
               
        }


        function listarCostosProductos(row){
        	var table=$('#tabla').DataTable();
        	var fila=table.row(row).index();
        	var ano=table.cell(fila,0).data();
        	var mes=table.row(row).node().dataset.nummes;

        	$("#anoSel").val(ano);
        	$("#mesSel").val(table.cell(fila,1).data());
          excelAno=ano;
          excelMes=table.cell(fila,1).data();
          console.log(excelAno);

          if(parseInt(mes)<10){ 
               mm='0'+mes.toString();
          }else{
              mm=mes.toString();
          }

          periodoSeleccionado=parseInt(ano.toString()+mm.toString());
        	document.getElementById('mesSel').dataset.numeromes=mes;

          titulo="Lista de Costos " + table.cell(fila,1).data() + " " + ano;
        	var productos=$("#tablaProductos").DataTable();

        	productos.rows().remove().draw();

            $.ajax({
                url: urlApp +'costosMensualesProductos',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        ano: ano,
                        mes: mes
                      },
                success:function(dato){
                  $( "#btnAgregarCosto" ).show();

                    for(var x=0;x<dato.length;x++){
                        if( 
                            ( document.getElementById('idPerfilSession').value=='2' ||
                              document.getElementById('idPerfilSession').value=='5' || 
                              document.getElementById('idPerfilSession').value=='18')    
                            ){
                            elemCosto="<input class='cajaNumero' value='" + new Intl.NumberFormat("de-DE").format(dato[x].costo) +"' style='width: 100px' onkeypress='return isIntegerKey(event)' onblur='formatoNumero(this);'>";
                        }else{
                            elemCosto="<input class='cajaNumero' input-sm' value='" + new Intl.NumberFormat("de-DE").format(dato[x].costo) +"' style='width: 100px' readonly>";
                        }

                        var fila=productos.row.add( [
                                dato[x].prod_nombre,
                                dato[x].u_nombre,
                                dato[x].nombrePlanta,
                                elemCosto, 
                                dato[x].costo
                            ] ).index();

                        productos.cell(fila,0).node().dataset.idproductolistaprecio=dato[x].idProductoListaPrecio;
                        productos.cell(fila,0).node().style.width="0px";

                    }

                    productos.draw();
                    
           /*         $(".cajaNumero").ejNumericTextbox({
                        decimalPlaces: 0,
                       // watermarkText: "Ingrese valor",
                        minValue: 0,
                        locale: "de-DE",
                        showSpinButton: false,
                        width: 120
                    });*/

                    actualizarFiltros(productos);

                    $("#mdCostos").modal('show');
            
                }
            })

        }

        function cerrarListaProductos(){
        	$("#mdCostos").modal('hide');
        }

        function formatoNumero(texto){

          texto.value=new Intl.NumberFormat("de-DE").format( texto.value.replace('.','').replace('.','') );
        }


		function agregarMes(){

			var table=$('#tabla').DataTable();
            var ano=$("#ano").val();
            var mes = $("#mes").val();
          

            if(ano.toString().trim().length <4 ){
                swal(
                    {
                        title: '¡El año ingresado es incorrecto!',
                        text: 'Debe ingresar 4 dígitos',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'NO',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    });
                return;
            }
           
            var f = new Date();
           
      if((f.getMonth() +2 >= $("#mes").val() && f.getFullYear() >= $("#ano").val()) || (f.getMonth() +1 == 12 && f.getFullYear() +1 == $("#ano").val() && $("#mes").val() == 1) || (f.getMonth() +1 == 12 && f.getFullYear() +1 >= $("#ano").val())){

        
            for (var i = 0; i < table.rows().count(); i++){

                var m = table.row(i).node().dataset.nummes;
                if(table.cell(i,0).data()==$("#ano").val() && m==$("#mes").val() ){
                            swal(
                                {
                                    title: '¡Mes ya existe!',
                                    text: 'El año y mes ingresados ya existen en la lista',
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    cancelButtonText: 'NO',
                                    closeOnConfirm: true,
                                    closeOnCancel: false
                                });
                            return;
                  } 
            }


            $.ajax({
              url: urlApp + "crearCostosMensuales",
              headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
              type: 'POST',
              dataType: 'json',
              data: {                      
                      ano: $("#ano").val(),
                      mes: $("#mes").val()
                    },
              success:function(dato){
                var nodo=table.row.add( [
                        $("#ano").val(),
                        $("#mes option:selected").html(),
                        '<button class="btn btn-xs btn btn-warning btnEditar" title="Ver Costos" onclick="listarCostosProductos(this.parentNode.parentNode);"><i class="fa fa-search fa-lg"></i></button>&nbsp' + 
                        '<button class="btn btn-xs btn btn-warning btnEditar" title="Subir Archivo de Costos" onclick="subirArchivoCostos(this.parentNode.parentNode);"><i class="fa fa-file-archive-o"></i></button>&nbsp' +
                        '<button type="button"  class="btn-xs btn btn-info" onclick="verificarmes(this.parentNode.parentNode);">Revisión Costos en 0</button>'           
                        ] ).draw().node();

                nodo.dataset.nummes=$("#mes").val();
                swal(
                    {
                        title: 'Costo Agregado Correctamente',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'NO',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    });
              }
            })			

      }else{
        swal(
            {
                title: '¡Puede asignar costos con máximo un mes de adelanto respecto al mes actual!',
                text: '',
                type: 'warning',
                showCancelButton: false,
                confirmButtonText: 'OK',
                cancelButtonText: 'NO',
                closeOnConfirm: true,
                closeOnCancel: false
            });
      }        

                      
		}


		function guardarCostos(){
      document.getElementById('btnGuardarCambios').disabled=true;
			var tabla= $("#tablaProductos").DataTable();
		    var cadena='[';
		    var costo="0";
		    for (var i = 0; i < tabla.rows().count(); i++){
		    	if(tabla.cell(i,3).node().getElementsByTagName('input')[0].value==''){
		    		costo="0";
		    	}else{
		    		costo=tabla.cell(i,3).node().getElementsByTagName('input')[0].value.replace('.','').replace('.','');
		    	}

          if( isNaN(costo) ){
            $("#mdProcesando").modal('hide');
            document.getElementById('btnGuardarCambios').disabled=false;
            swal(
                {
                    title: '¡Los valores ingresados deben ser solo números!' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }
            )
            return;                   
          }

          cadena+='{';
          cadena+='"idProductoListaPrecio":"'+ tabla.cell(i,0).node().dataset.idproductolistaprecio  + '", ';
          cadena+='"costo":"' + costo + '"';
          cadena+='}, ';

		    }
		    cadena=cadena.slice(0,-2);
		    cadena+=']';
            $.ajax({
                url: urlApp + "actualizarCostos",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {                      
                        ano: $("#anoSel").val(),
                        mes: document.getElementById('mesSel').dataset.numeromes,
                        detalle: cadena
                      },
                success:function(dato){
                    $("#mdProcesando").modal('hide');
                    document.getElementById('btnGuardarCambios').disabled=false;
                    swal(
                        {
                            title: '¡Los cambios han sido guardados!' ,
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: '',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        }
                    ) 
                	cerrarListaProductos();
                }

            })	

		}

    function abrirAgregarCosto(){
      $("#mdAgregarCosto").modal('show');
    }

    function cerrarAgregarCosto(){
      $("#selProductos").val(1);
      $("#selUnidades").val(1);
      $("#selPlantas").val(1);
      $("#txtCosto").val("");
      $("#mdAgregarCosto").modal('hide'); 
    }
    function verificarmes(row){
        	var table=$('#tabla').DataTable();
        	var fila=table.row(row).index();
        	var ano=table.cell(fila,0).data();
        	var mes=table.row(row).node().dataset.nummes;

        	$("#anoSel").val(ano);
        	$("#mesSel").val(table.cell(fila,1).data());

          if(parseInt(mes)<10){ 
               mm='0'+mes.toString();
          }else{
              mm=mes.toString();
          }

          periodoSeleccionado=parseInt(ano.toString()+mm.toString());
        	document.getElementById('mesSel').dataset.numeromes=mes;

          titulo="Lista de Costos " + table.cell(fila,1).data() + " " + ano;
        	var productos=$("#tablaProductos").DataTable();

        	productos.rows().remove().draw();

            $.ajax({
                url: urlApp +'verificarMesEn0',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        ano: ano,
                        mes: mes
                      },
                success:function(dato){
                  if(dato[0].numero != 0){
                    $.ajax({
                        url: urlApp +'costosMensualesProductosen0',
                        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                                ano: ano,
                                mes: mes
                              },
                            success:function(dato){
                              $( "#btnAgregarCosto" ).hide();
                                for(var x=0;x<dato.length;x++){

                                    if( 
                                        ( document.getElementById('idPerfilSession').value=='2' ||
                                          document.getElementById('idPerfilSession').value=='5' || 
                                          document.getElementById('idPerfilSession').value=='18')    
                                        ){
                                        elemCosto="<input class='cajaNumero' value='" + new Intl.NumberFormat("de-DE").format(dato[x].costo) +"' style='width: 100px' onkeypress='return isIntegerKey(event)' onblur='formatoNumero(this);'>";
                                    }else{
                                        elemCosto="<input class='cajaNumero' input-sm' value='" + new Intl.NumberFormat("de-DE").format(dato[x].costo) +"' style='width: 100px' readonly>";
                                    }

                                    var fila=productos.row.add( [
                                            dato[x].prod_nombre,
                                            dato[x].u_nombre,
                                            dato[x].nombrePlanta,
                                            elemCosto, 
                                            dato[x].costo
                                        ] ).index();

                                    productos.cell(fila,0).node().dataset.idproductolistaprecio=dato[x].idProductoListaPrecio;
                                    productos.cell(fila,0).node().style.width="0px";

                                }

                                productos.draw();
                                
                                /*$(".cajaNumero").ejNumericTextbox({
                                    decimalPlaces: 0,
                                  // watermarkText: "Ingrese valor",
                                    minValue: 0,
                                    locale: "de-DE",
                                    showSpinButton: false,
                                    width: 120
                                });*/

                                actualizarFiltros(productos);

                                $("#mdCostos").modal('show');
                        
                            }
                        })

                  }else{
                    swal(
                {
                    title: 'El mes no contiene costos en 0' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }
            ) 
                  }

                }
              })
    }

    function guardarAgregarCostos(){
      var table=$('#tablaProductos').DataTable();
      var buscar=$("#selProductos option:selected").html().trim()+$("#selUnidades option:selected").html().trim()+$("#selPlantas option:selected").html().trim();

      for (var i = 0; i < table.rows().count(); i++){
          if( (buscar==table.cell(i,0).data().trim()+table.cell(i,1).data().trim()+table.cell(i,2).data()) ){
              swal(
                  {
                      title: '¡Producto existente!',
                      text: 'El producto ya se encuentra en la lista',
                      type: 'warning',
                      showCancelButton: false,
                      confirmButtonText: 'OK',
                      cancelButtonText: 'NO',
                      closeOnConfirm: true,
                      closeOnCancel: false
                  });
              return;
          }
      }
      $.ajax({
        url: urlApp + "obtenerIdProductoListaPrecio",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'post',
        dataType: 'json',
        data: {                      
                codigo_prod: $("#selProductos").val(),
                nombre_unidad: $("#selUnidades option:selected").text(),
                codigo_planta: $("#selPlantas").val()
              },
        success:function(dato){
          if (dato.length == 0){
            swal(
                {
                    title: 'Primero debe crear el producto en el maestro de productos' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }
            ) 
          }
          else{
            var costo = 0;
            if ($("#txtCosto").val() != ""){
              costo = $("#txtCosto").val();
            }
            $.ajax({
              url: urlApp + "guardarProductoListaPrecio",
              headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
              type: 'post',
              dataType: 'json',
              data: {     
                      ano: $("#anoSel").val(),
                      mes: document.getElementById('mesSel').dataset.numeromes,
                      idproductolistaprecio: dato[0].idProductoListaPrecios,
                      costo: costo,
                    },
              success:function(dato){
                swal(
                {
                    title: 'Costo agregado correctamente' ,
                    text: '',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }) 
              } 
            });

            elemCosto="<input class='form-control input-sm' value='" + $("#txtCosto").val() +"' style='width: 100px' onkeypress='return isIntegerKey(event)'>";

            var fila=table.row.add( [
                    $("#selProductos option:selected").html(),
                    $("#selUnidades option:selected").html(),
                    $("#selPlantas option:selected").html() ,
                    elemCosto,
                    $("#txtCosto").val()
                ] ).index();

            table.cell(fila,0).node().dataset.idproductolistaprecio=dato[0].idProductoListaPrecios;
            table.cell(fila,0).node().style.width="0px";
            table.draw();
            actualizarFiltros(table);
            cerrarAgregarCosto();
          }
        } 
      });
    }
	</script>

@endsection        	
