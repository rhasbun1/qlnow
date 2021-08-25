@extends('plantilla')      

@section('contenedorprincipal')


<div style="padding: 20px">
    <div class="panel panel-default table-responsive">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="text" id="txtPerfil" value="{{Session::get('idPerfil')}}" hidden>
        <div class="panel-heading">
            <b>Productos</b>
        </div>
        <div style="padding-bottom: 15px">
            <div class="row">
                <div class="col-md-3">
                </div>
               <!-- <div class="col-md-9 row"  style="display: inline">
                    <div class="col-md-2">
                        Filtrar por Planta
                    </div>
                    <div class="col-md-4">
                         <select id="licitacion" class="form-control input-sm bloquear">
                        <option value="0">Seleccione...</option>
                        @foreach($planta as $item)
                            <option value="{{ $item->idPlanta}}">{{ $item->idPlanta}} {{ $item->nombre }}</option>
                        @endforeach 
                        <option value="6">Todas</option> 
                    </select> 
                    </div>
                    <div class="col-md-4">
                       <input type="button" value="Buscar" class="btn btn-success btn-sm" style="display: inline;" onclick="resumenGeneral()">
   
                    </div>
				</div> -->
            </div>                    
            
        </div>

        <div id="divTabla" style="display:none;" class="padding-md clearfix">           
            <table id="tabla" class="table table-hover table-condensed table-responsive"  style="width: 100%">
                <thead>
                    <th style="display: none">ID</th>
                    <th style="width:150px">Producto</th>
                    <th style="width:70px">Unidad</th>
                    <th style="width:70px">Planta</th>
                    <th style="width:70px">Precio Ref. ($)</th>
                    <th style="text-align: right; display:none;">Precio Ref. ($)</th>
                    <th>Cód.Softland</th>
                    <th style="width:70px">Requiere Diseño</th>
                    <th style="width:70px">Granel</th>
                    <th style="width:70px">Certificado</th>
                    <th>Tiempo Producción (Horas)</th>
                    @if ( Session::get('idPerfil')=='1' or
                                    Session::get('idPerfil')=='2' or
                                    Session::get('idPerfil')=='4' or
                                    Session::get('idPerfil')=='18' or
                                    Session::get('idPerfil')=='5' ) 
                        <th style="width:40px"></th>
                    @endif
                    <th style="display: none;">Precio Ref. ($)</th>

                </thead>
                <tbody>
                    @foreach($listaProductos as $item)
                        <tr>
                            <td style="display: none">{{ $item->idProductoListaPrecios }}</td>
                            <td style="width:150px">{{ $item->prod_nombre }}</td>
                            <td style="width:70px">{{ $item->u_nombre }}</td>
                            <td style="width:70px">{{ $item->nombrePlanta }}</td>
                            <td style="width:70px">{{ number_format( $item->precioReferencia, 0, ',', '.')  }}</td>
                            <td style="text-align: right; display:none;">{{ number_format( $item->precioReferencia, 0, '.', ',')  }}</td>
                            <td>{{ $item->codigoSoftland }}</td>
                            <td style="width:70px">
                                @if($item->requiereDiseno==1) SI @else NO @endif
                            </td>
                            <td style="width:70px">
                                @if($item->granel==1) SI @else NO @endif
                            </td>    
                            <td style="width:70px">@if($item->solicitaCertificado==1) SI @else NO @endif</td>
                            <td>{{ $item->tiempoProduccion }}</td>
                            @if ( Session::get('idPerfil')=='1' or
                                    Session::get('idPerfil')=='2' or
                                    Session::get('idPerfil')=='4' or
                                    Session::get('idPerfil')=='18' or
                                    Session::get('idPerfil')=='5' ) 
                                <td style="width:40px">
                                                                
                                    <button class="btn btn-xs btn btn-warning btnEditar" title="Editar"><i class="fa fa-edit fa-lg"></i></button>
                                    <button class="btn btn-xs btn btn-danger btnEliminar" title="Eliminar"><i class="fa fa-trash-o fa-lg"></i></button>
                                
                                </td> 
                            @endif    
                            <td style="display: none;">{{ number_format( $item->precioReferencia, 0, ',', ',')  }}</td>
                       
                        </tr>
                    @endforeach
                </tbody>              
            </table>      
        </div>
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
    </div>    
</div>

<div id="modProducto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header" id="tituloFormProducto">
            <h5><b>Datos Producto</b></h5>
        </div>
        <div id="bodyProducto" class="modal-body">
            <input class="hidden" id="fila">
            <div class="row" style="padding-top: 5px">
                <div class="col-md-3">
                    Nombre (*)
                </div>
                <div class="col-md-7">
                    <select class="form-control input-sm" id="selProductos">
                        @foreach($productos as $item)
                            <option value="{{ $item->prod_codigo }}">{{ $item->prod_nombre }}</option>
                        @endforeach 
                    </select>  
                </div>
            </div>
            <div class="row" style="padding-top: 5px">
                <div class="col-md-3">
                    Unidad (*)
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" id="selUnidades">
                        @foreach($unidades as $item)
                            <option value="{{ $item->u_codigo }}">{{ $item->u_nombre }}</option>
                        @endforeach 
                    </select>  
                </div>
                <div class="col-md-3">
                    Código Softland
                </div>
                <div class="col-md-3">
                    <input class="form-control input-sm" id="codigoSoftland" maxlength="10">
                </div>            
            </div>                     
            <div class="row" style="padding-top: 5px">
                <div class="col-md-3">
                    Planta (*)
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" id="selPlantas">
                        @foreach($plantas as $item)
                            <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                        @endforeach 
                    </select>  
                </div>
                <div class="col-md-3">
                    Precio Referencia (*)
                </div>
                <div class="col-md-3">
                    <input class="form-control input-sm" id="precioReferencia" onkeypress='return isNumberKey(event)'>
                </div>                
            </div>
            <div class="row" style="padding-top: 20px">
                <div class="col-md-3">
                    Requiere Diseño
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" id="requiereDiseno">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                    </select>
                </div>
                <div class="col-md-3">
                    Granel
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" id="granel">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                    </select>
                </div>
            </div>
            <div class="row" style="padding-top: 20px">
                <div class="col-md-3">
                    Solicita Certificado
                </div>
                <div class="col-md-3">
                    <select class="form-control input-sm" id="solicitaCertificado">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                    </select>
                </div>
                <div class="col-md-3">
                    Tiempo de Producción (Horas)
                </div>
                <div class="col-md-3">
                    @if(Session::get('idPerfil')=='3' or
                        Session::get('idPerfil')=='5' or
                        Session::get('idPerfil')=='4' or
                        Session::get('idPerfil')=='2' or
                        Session::get('idPerfil')=='5' or
                        Session::get('idPerfil')=='18' or
                        Session::get('idPerfil')=='1')
                        <input class="form-control input-sm" maxlength="10" id="tiempoProduccion" onkeypress='return isNumberKey(event)'>
                    @else
                        <input class="form-control input-sm" maxlength="10" id="tiempoProduccion" onkeypress='return isNumberKey(event)' readonly="true">
                    @endif
                </div>
            </div>                            
        </div>
        <div class="col-md-offset-8" style="padding-top: 20px; padding-bottom: 20px">
           <button type="button" class="btn btn-success btn-sm" onclick="guardarDatosProducto();" style="width: 80px">Guardar</button>
           <button type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModProducto()" style="width: 80px">Salir</button>
        </div>        
    </div>
</div>


@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>  

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  
    <script src="{{ asset('/') }}js/app/funciones.js"></script>
    <script src="js/syncfusion/ej.web.all.min.js"> </script>
    <script src="{{ asset('/') }}js/syncfusion/lang/ej.culture.de-DE.min.js"></script>

    <script>
        var productoListaPrecioID;
        $("#precioCosto").ejNumericTextbox({
            decimalPlaces: 0,
           // watermarkText: "Ingrese valor",
            minValue: 0,
            locale: "de-DE",
            showSpinButton: false,
            width: 120
        });

        $("#precioReferencia").ejNumericTextbox({
            decimalPlaces: 0,
           // watermarkText: "Ingrese valor",
            minValue: 0,
            locale: "de-DE",
            showSpinButton: false,
            width: 120
        });

        function nuevoProducto(){
 
            $("#fila").val('-1');
            document.getElementById('selProductos').selectedIndex=0;
            $("#tituloFormProducto").html('<h5><b>Nuevo Producto</b></h5>');
            var tabla=document.getElementById('tabla');
            $("#fechaCosto").val('');
            $("#precioCosto").ejNumericTextbox({
                value: 0
            });

            $("#precioReferencia").ejNumericTextbox({
                value: 0
            });

            $("#codigoSoftland").val('' );
            document.getElementById('selPlantas').selectedIndex=0;
            document.getElementById('selUnidades').selectedIndex=0;            
            document.getElementById('requiereDiseno').selectedIndex=0;
            document.getElementById('granel').selectedIndex=0;
            document.getElementById('solicitaCertificado').selectedIndex=0;
            $("#modProducto").modal("show");
         

        }

        function cerrarModProducto(){
           $("#modProducto").modal("hide"); 
        }

        function guardarDatosProducto(){
            if($("#codigoSoftland").val()==""){
                swal(
                        {
                            title: 'Código softland no puede estar vacío',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'NO',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        });
            }else if($("#tiempoProduccion").val()==""){
                swal(
                        {
                            title: 'Tiempo de producción no puedo estar vacío',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'NO',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        });
            }else{

                  var existe=true;  

                  $.ajax({
                        async: false, 
                        url: urlApp + "validarProductoCodigoSoftland",
                        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                            codigoProducto: $("#codigoSoftland").val()
                        },
                      success:function(data){
                        if(data.identificador==0){

                            existe=false;
                            swal(
                                {
                                    title: '¡El codigo softland ingresado no existe en contabilidad!' ,
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
                        }
                      },
                      error: function(jqXHR, text, error){
                          alert('Error!, No se pudo Añadir los datos');
                      }
                  });
                  if(!existe){
                    return;
                  }



                var fila=$("#fila").val();
                var idPrecio='0';
                var table=$('#tabla').DataTable();
                var buscar=$("#selProductos option:selected").html().trim()+$("#selUnidades option:selected").html().trim()+$("#selPlantas option:selected").html().trim();

                for (var i = 0; i < table.rows().count(); i++){
                    if( (buscar==table.cell(i,1).data().trim()+table.cell(i,2).data().trim()+table.cell(i,3).data().trim()) && fila!=i ){
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
                    if( $("#precioReferencia").val() == "" ){
                        swal(
                            {
                                title: 'El precio no puede estar vacío!',
                                text: '',
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
                    url: urlApp + "guardarDatosProductoListaPrecio",
                    headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                             idProductoListaPrecios: productoListaPrecioID,    
                             nombreProducto: $("#selProductos option:selected").text(),
                             unidad: $("#selUnidades option:selected").html(),
                             idPlanta: $("#selPlantas").val(),
                             precioReferencia: $("#precioReferencia").val(),
                             requiereDiseno: $("#requiereDiseno").val(),
                             granel: $("#granel").val(),
                             solicitaCertificado: $("#solicitaCertificado").val(),
                             codigoSoftland: $("#codigoSoftland").val(),
                             tiempoProduccion: $("#tiempoProduccion").val()
                          },
                    success:function(dato){
                        var table = $('#tabla').DataTable();
                        var chain = '<td style="width:40px"><button class="btn btn-xs btn btn-warning btnEditar" title="Editar"><i class="fa fa-edit fa-lg"></i></button>' + 
                                    '<button class="btn btn-xs btn btn-danger btnEliminar" title="Eliminar"><i class="fa fa-trash-o fa-lg"></i></button></td>';
                        if(fila.toString()=='-1'){
                           //ff=table.row.add( [ 
                           table.row.add( [
                                    dato[0].idProductoListaPrecios,
                                    $("#selProductos option:selected").html(),
                                    $("#selUnidades option:selected").html(),
                                    $("#selPlantas option:selected").html() ,
                                    $("#precioReferencia").val(),
                                    $("#precioReferencia").val(),

                                    $("#codigoSoftland").val(),
                                    $("#requiereDiseno option:selected").html(),
                                    $("#granel option:selected").html(),
                                    $("#solicitaCertificado option:selected").html(),
                                    $("#tiempoProduccion").val(),
                                    '<td style="width:40px"><button class="btn btn-xs btn btn-warning btnEditar" title="Editar"><i class="fa fa-edit fa-lg"></i></button>' + 
                                    '<button class="btn btn-xs btn btn-danger btnEliminar" title="Eliminar"><i class="fa fa-trash-o fa-lg"></i></button></td>',
                                    $("#precioReferencia").val()

                                    ] ).draw();
                                                                              
                        }else{

                            table.cell(fila,1).data( $("#selProductos option:selected").html() );
                            table.cell(fila,2).data( $("#selUnidades option:selected").html() );                 
                            table.cell(fila,3).data( $("#selPlantas option:selected").html() );
                            table.cell(fila,4).data( number_format($("#precioReferencia").data("ejNumericTextbox").model.value) );
                            table.cell(fila,5).data( number_format($("#precioReferencia").data("ejNumericTextbox").model.value) );

                            table.cell(fila,6).data( $("#codigoSoftland").val() );
                            table.cell(fila,7).data( $("#requiereDiseno option:selected").html() );
                            table.cell(fila,8).data( $("#granel option:selected").html() );
                            table.cell(fila,9).data( $("#solicitaCertificado option:selected").html() ); 
                            table.cell(fila,10).data( $("#tiempoProduccion").val() );
                            table.cell(fila,11).data(chain);

                            table.cell(fila,10).draw();                
                        }
                        table.columns([5,12]).visible(false, false);

                        cerrarModProducto();
                    }

                })
            }
        }

        function eliminarProductoPrecio(fila){
            var table= $("#tabla").DataTable();
            var data=table.row( fila ).data() ;
            swal(
                {
                    title: '¿Elimina el producto seleccionado de la lista de precios?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'SI',
                    cancelButtonText: 'NO',
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if(isConfirm){
                        $.ajax({
                            url: urlApp + "eliminarProductoListaPrecio",
                            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                            type: 'POST',
                            dataType: 'json',
                            data: { 
                                    idProductoListaPrecios: data[0]
                                  },
                            success:function(dato){
                                if( dato[0].idProductoListaPrecios==-1){
                                    swal(
                                        {
                                            title: '¡El producto no puede ser eliminado, ya tiene información relacionada!',
                                            text: '',
                                            type: 'warning',
                                            showCancelButton: false,
                                            confirmButtonText: 'OK',
                                            cancelButtonText: 'NO',
                                            closeOnConfirm: true,
                                            closeOnCancel: false
                                        });
                                }else{
                                    table
                                        .row( fila )
                                        .remove()
                                        .draw();
                                }
                            }

                        })
                        
                    }
                }
            )              
        }

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tabla thead tr').clone(true).appendTo( '#tabla thead' );
            $('#tabla thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();

                if(title.trim()!='' ){
                    $(this).html( '<input type="text" class="form-control input-sm" placeholder="Buscar..." />' );
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    } );
                }
             
            } );


            $('#tabla tbody').on( 'click', '.btnEliminar', function () {
                eliminarProductoPrecio(table.row( $(this).parents('tr') ).index() );
            } );

            $('#tabla tbody').on( 'click', '.btnEditar', function () { 
                var data=table.row( $(this).parents('tr') ).data();
                $("#fila").val( table.row( $(this).parents('tr') ).index() );
                $("#tituloFormProducto").html('<h5><b>Editar Datos del Producto</b></h5>');
                productoListaPrecioID = data[0];
                $("#precioReferencia").ejNumericTextbox({
                    value: data[4].trim().replace('.','')
                });                

                $("#codigoSoftland").val( data[6].trim() );
                if( data[7].trim()=='NO' ){
                    document.getElementById('requiereDiseno').selectedIndex=1;
                }else{
                    document.getElementById('requiereDiseno').selectedIndex=0;
                }
                if( data[8].trim()=='NO' ){
                    document.getElementById('granel').selectedIndex=1;
                }else{
                    document.getElementById('granel').selectedIndex=0;
                }

                if( data[9].trim()=='NO' ){
                    document.getElementById('solicitaCertificado').selectedIndex=1;
                }else{
                    document.getElementById('solicitaCertificado').selectedIndex=0;
                }

                $("#tiempoProduccion").val( data[10].trim() );

                var lista=document.getElementById('selProductos');
                for (var i = 0; i < lista.length; i++){
                    var opt=lista.options[i];
                    if( opt.text==data[1].trim() ){
                        lista.selectedIndex=i;
                        break;
                    }
                }

                var lista=document.getElementById('selPlantas');
                for (var i = 0; i < lista.length; i++){
                    var opt=lista.options[i];
                    if( opt.text==data[3].trim() ){
                        lista.selectedIndex=i;
                        break;
                    }
                }

                var lista=document.getElementById('selUnidades');
                for (var i = 0; i < lista.length; i++){
                    var opt=lista.options[i];
                    if( opt.text==data[2].trim() ){
                        lista.selectedIndex=i;
                        break;
                    }
                }


                $("#modProducto").modal("show");

            } );            

            // DataTable
           if($("#txtPerfil").val() == 1 || $("#txtPerfil").val() == 2 || $("#txtPerfil").val() == 4 ||
           $("#txtPerfil").val() == 5 ||$("#txtPerfil").val() == 18 ){          
            var table=$('#tabla').DataTable({
                orderCellsTop: true,        
                fixedHeader: true,  
                dom: 'Bfrtip',
                "language": {
                  "thousands": ".",
                  "decimal": ","
                 },              
                "order": [[ 1, "asc" ]],
                "columnDefs": [ {
                    "targets": 0,
                    "createdCell": function (td, cellData, rowData, row, col) {

                        $(td).css('display', 'none')

                    }
                } ],               
                buttons: [                   
                    {
                        text: 'Nuevo Producto',
                        className: 'orange',
                        action: function ( e, dt, node, config ) {
                            nuevoProducto();
                        }
                    },                
                    {
                        extend: 'excelHtml5',
                        title: 'Listado de Productos',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [ 1, 2, 3, 12, 6, 7, 8, 9,10 ],
                            /*format: {
                                body: function(data, row, column, node) {
                                    data = $('<p>' + data + '</p>').text();
                                    
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                                }
                           }  */


                         }
                        
                    }
                ],                  
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function( settings, json){
                    var divTabla = document.getElementById('divTabla');
                     divTabla.style.display="block";
                     var api = this.api();
                     api.draw();
                 }
            });


           }else{         
            var table=$('#tabla').DataTable({
                orderCellsTop: true,        
                fixedHeader: true,  
                dom: 'Bfrtip',
                "language": {
                  "thousands": ".",
                  "decimal": ","
                 },
                "order": [[ 1, "asc" ]],
                "columnDefs": [ {
                    "targets": 0,
                    "createdCell": function (td, cellData, rowData, row, col) {

                        $(td).css('display', 'none')

                    }
                } ],               
                buttons: [                                 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado de Productos',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [ 1, 2, 3, 11, 6, 7, 8, 9,10 ],
                            /*format: {
                                body: function(data, row, column, node) {
                                    data = $('<p>' + data + '</p>').text();
                                    
                                    return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                                }
                           }  */


                         }
                        
                    }
                ],                  
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function( settings, json){
                     divTabla.style.display="block";
                     var api = this.api();
                     api.draw();
                 }
            });
        }
             

        } );

       
   
    </script>
    
@endsection
