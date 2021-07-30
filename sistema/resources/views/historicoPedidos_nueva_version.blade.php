@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div class="panel-heading">
            <b>Pedidos Despachados</b>
        </div>
        <div class="panel-body" id="panelBody">
            <div> 
                <div class="row">
                    <div class="col-md-4">
                        <div class="row" style="padding-top: 5px">
                            <div class="col-md-4">
                                Cliente
                            </div>
                            <div class="col-md-8">
                                @if ( Session::get('empresaUsuario')!='0' )
                                    <select id="idCliente" class="form-control input-sm" readonly>
                                        <option value="{{ $clientes[0]->emp_codigo }}">{{ $clientes[0]->emp_nombre }}</option>
                                @else
                                    <select id="idCliente" class="form-control input-sm">
                                        <option value="0">Todos los clientes</option>
                                        @foreach($clientes as $item)
                                            <option value="{{ $item->emp_codigo }}">{{ $item->emp_nombre }}</option>
                                        @endforeach
                                 @endif    
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px">
                            <div class="col-md-4">
                                Planta QLSA
                            </div>
                            <div class="col-md-8">
                                <select id="idPlanta" class="form-control input-sm">
                                    @if ( Session::get('idPlanta')=='0' )
                                        <option value="0">Todas las plantas</option>
                                        @foreach($plantas as $item)
                                            <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    @else    
                                        @foreach($plantas as $item)
                                            @if ( Session::get('idPlanta')==$item->idPlanta)
                                                <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                                            @endif    
                                        @endforeach
                                    @endif                                            
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4" style="padding-top: 5px">
                                Filtrar por Horario Salida
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaMin">
                                    <input type="text" class="form-control input-sm" id="min">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaMax">
                                    <input type="text" class="form-control input-sm" id="max">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(1);">Buscar</button>
                            </div>                                
                        </div>
                        <div class="row" style="padding-top: 5px">  
                            <div class="col-md-4" style="padding-top: 5px">
                                Filtrar por Horario Creación( Usar este filtro para ver todos los pedidos suspendidos )
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaCreacionMin">
                                    <input type="text" class="form-control input-sm" id="fechaCreacionMin">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaCreacionMax">
                                    <input type="text" class="form-control input-sm" id="fechaCreacionMax">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(4);">Buscar</button>
                            </div>                                
                        </div>                                                       
                        <div class="row" style="padding-top: 5px">                               
                            <div class="col-md-4">
                                Rango por Nº de Pedido
                            </div>
                            <div class="col-md-3">
                                <input id="txtPedidoDesde" class="form-control input-sm" id="min" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-3">
                                <input id="txtPedidoHasta" class="form-control input-sm" id="max" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(2);">Buscar</button>
                            </div>                                   
                        </div>
                        <div class="row" style="padding-top: 5px">
                            <div class="col-md-4">
                                Rango por Nº Guía Despacho
                            </div>
                            <div class="col-md-3">
                                <input id="txtGuiaDesde" class="form-control input-sm" id="min" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-3">
                                <input id="txtGuiaHasta" class="form-control input-sm" id="max" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(3);">Buscar</button>
                            </div>                                   
                        </div>
                    </div>
                </div>                                                                             
            </div>
        </div>
        <div id="Grid"></div>   
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
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

<div id="modNumeroAuxiliar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Ingrese número auxiliar</b></h5>
            </div>
            <div id="bodyProducto" class="modal-body">
                <input id="numeroAuxiliar" class="form-control input-sm" maxlength="10" data-fila="0">
            </div>
            <div class="col-md-offset-4" style="padding-top: 20px; padding-bottom: 20px">
               <button id="btnNumAuxiliar" type="button" class="btn btn-warning btn-sm" style="width: 100px" onclick="guardarNumeroAuxiliar();">Guardar</button>
               <button id="btnCerrarMumAuxiliar" type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalNumeroAuxliar()" style="width: 80px">Salir</button>
            </div>            
        </div>
    </div>
</div>
@include('guiaDespacho');

@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>

    <script src="{{ asset('/') }}js/syncfusion/jquery.globalize.js"></script>
    <script src="{{ asset('/') }}js/syncfusion/lang/globalize.culture.en-US.min.js"></script>
    <style>
        .e-grid .e-headercell {
            height:70px !important;
        }
        .e-grid .e-headercelldiv{ /* grid headercell font styles*/ 
            font-size: 10px; 
        }         
    </style>

    <script id="template" type="text/x-template">
        <div class="imagenes">
            ${colEstado}
        </div>
    </script>    

    <script id="template-pedido" type="text/x-template">
        <div class="numPedido" style="text-align=center">
            ${idPedido}
        </div>
    </script>

</script>

    <script>

        ej.base.L10n.load({
            'en-US': {
                'grid': {
                    'EmptyRecord': 'No hay información para mostrar',
                    'GroupDropArea': 'Arrastre columna aquí para agrupar',
                    'UnGroup': 'Ungroup',
                    'Item': 'Item',
                    'Items': 'Items',
                    'ClearFilter': 'Borrar filtro',
                    "Print": "Imprimir",
                    "Pdfexport": "Exportar a PDF",
                    "Excelexport": "Exportar a Excel",
                    "Csvexport": "Exportar a CSV",
                    "FilterButton": "Filtrar",
                    "ClearButton": "Quitar",                                    
                    "SelectAll": "Seleccionar todo",
                    "Search": "Buscar",
                    "Blanks": "Vacío",
                },
                'pager': {
                    'currentPageInfo': '{0} de {1}',
                    'totalItemsInfo': '({0} Items)',
                    'firstPageTooltip': 'Ir a primera página',
                    'lastPageTooltip': 'Ir a última página',
                    'nextPageTooltip': 'Siguiente página',
                    'previousPageTooltip': 'Página anterior',
                    'nextPagerTooltip': 'nextPagerTooltip',
                    'previousPagerTooltip': 'previousPagerTooltip'
                },
            }
        });



        function renderGrid(data){
            var columnas;
            columnas=[
                { field: 'idPedido', headerText: 'Pedido', width: 100, textAlign: 'center',  template: '#template-pedido' },            
                { field: 'colEstado', headerText: '', width: 80, template: '#template' },
                { field: 'emp_nombre', headerText: 'Cliente', width: 120, textAlign: 'Left' },
                { field: 'nombreObra', headerText: 'Obra/Planta', width: 120, textAlign: 'Left' },
                { field: 'prod_nombre', headerText: 'Producto', width: 120, textAlign: 'Left' },
                { field: 'cantidadReal', headerText: 'Cant.Real', width: 100, textAlign: 'Right', format: "N" },            
                { field: 'unidad', headerText: 'Unidad', width: 90, textAlign: 'left' },  
                { field: 'fechaEntrega', headerText: 'Fecha Entrega Solicitada', width: 100, textAlign: 'left' },
                { field: 'fechaHoraSalida', headerText: 'Horario de Salida', width: 100, textAlign: 'left' },   
                { field: 'formaEntrega', headerText: 'Forma de Entrega', width: 100, textAlign: 'left' }, 
                { field: 'nombrePlanta', headerText: 'Planta de Origen', width: 100, textAlign: 'left' }, 
                { field: 'estadoPedido', headerText: 'Estado', width: 100, textAlign: 'left' }, 
                { field: 'idNotaVenta', headerText: 'Nota de Venta', width: 100, textAlign: 'Right', format: "N"  }, 
                { field: 'numeroGuia', headerText: 'Nº de guía', width: 100, textAlign: 'Right', format: "N"  }, 
                { field: 'numeroAuxiliar', headerText: 'Nº Aux.', width: 100, textAlign: 'Right', format: "N"  }, 
            ];

            var grid = new ej.grids.Grid({
                dataSource: data,
                locale: 'en-US',
                allowPaging: true,
                allowSorting: true,
                allowGrouping: false,
                gridLines: 'Vertical',
                allowFiltering: true,
                allowTextWrap: true,
                filterSettings: { type: 'CheckBox' },
                allowExcelExport: true,
                allowPdfExport: true,
                allowCsvExport: true,
                allowScrolling : true,
                height: 500,
                toolbar: ['ExcelExport', 'Print'],
                columns: columnas,
                gridLines: 'Both',
                pageSettings: { pageCount: 5, pageSize: 10, pageSizes: ['10', '50', 'All'] },
              //  queryCellInfo: queryCellInfo   
            });
            document.getElementById('Grid').innerHTML='';
            grid.appendTo('#Grid');

            grid.toolbarClick = function (args) {
                    if (args.item.id === 'Grid_pdfexport') {
                        grid.pdfExport(getPdfExportProperties());
                    }
                    if (args.item.id === 'Grid_excelexport') {
                        grid.excelExport(getExcelExportProperties());
                    }
                    if (args.item.id === 'Grid_csvexport') {
                        grid.csvExport(getCsvExportProperties());
                    }
                };                  
        }   

        function queryCellInfo(args) {
            if (args.column.field === 'colEstado') {
                console.log("pasa por aqui");
                args.cell.querySelector('.imagenes').innerHTML="<button class='btn btn-sm btn-success'>Test</button>";        
            }
        }

        $('#datosGuia').on('submit', function(e) {
          // evito que propague el submit
          e.preventDefault();
          // agrego la data del form a formData

            document.getElementById('btnSubirGuia').disabled=true;

            if( $("#nuevoFolioDTE").val().trim()=='' ){
                swal(
                    {
                        title: '¡¡Debe ingresar el numero Folio DTE!!' ,
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
                            document.getElementById('btnSubirGuia').disabled=false;
                            return;                         
                        }
                    }
                );
                document.getElementById('btnSubirGuia').disabled=false;
                return;
            }

            if(document.getElementById('upload-demo').value.trim()==''){
                swal(
                    {
                        title: 'Debe seleccionar un archivo!!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonText: 'Cerrar',
                        cancelButtonText: '',
                    });
                document.getElementById('btnSubirGuia').disabled=false;
                return;           
            }


            var tabla=document.getElementById('tablaDetalleGuia');
            for (var i = 1; i < tabla.rows.length; i++){
                if(tabla.rows[i].cells[4].getElementsByTagName('input')[0]){
                  cantidad=tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "").replace(",",".");
                  if(cantidad=='' || parseFloat(cantidad)<=0){
                    swal(
                        {
                            title: '¡Debe completar las cantidades y actualizar antes de subir el archivo!' ,
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
                }
                /*if(tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "")>parseInt(tabla.rows[i].cells[3].innerHTML)){
                        swal(
                        {
                            title: 'La cantidad pedida no puede ser mayor a la solicitada',
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
                            return;
                        }
                        )
                        return; 
                }*/
            }

            // Se recorre el DataTable para modificar la funcion abrirGuia con el nuevo número ingresado por el usuario

            var numeroGuiaOrigen="abrirGuia(1, " + document.getElementById('folioDTE').dataset.numeroguia + ", this.parentNode.parentNode)";
            var nuevoNumeroGuia ="abrirGuia(1, " + $('#nuevoFolioDTE').val() + ", this.parentNode.parentNode)";
            var table = $('#tablaDetalle').DataTable();
            var cadena = "";
            var numGuia=document.getElementById('folioDTE').dataset.numeroguia;
            var filas=table.rows().count();

            for (var i = 0; i < filas; i++){
                if( table.cell(i,13).data()==numGuia ){
                    cadena=table.cell(i,1).data();
                    table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
                    table.cell(i,13).data( $('#nuevoFolioDTE').val() );
                }
            }

            // Aqui se actualizan los cantidades ingresadas en la guía de despacho   

            actualizarDatosGuiaDespacho(false);

            // a continuación se envia el formulario con el nuevo número de guía y el archivo pdf correspondiente a la guía

          var formData = new FormData( $("#datosGuia")[0]);
          $.ajax({
              url: urlApp + "subirGuiaDespachoPdf",
              headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
              type:'POST',
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){
                if(data=="0"){
                    swal(
                        {
                            title: 'El número de guía ya existe!!' ,
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
                                document.getElementById('btnSubirGuia').disabled=false;
                                return;                         
                            }
                        }
                    );
                    document.getElementById('btnSubirGuia').disabled=false;
                    return;
                }                
                document.getElementById('folioDTE').dataset.numeroguia=$("#nuevoFolioDTE").val();
                $("#folioDTE").val( $("#nuevoFolioDTE").val() );
            //    $("#numGuia").val( $("#nuevoFolioDTE").val() );
                document.getElementById('btnEmitirGuia').style.display='none';
                document.getElementById('btnBajar').style.display='inline';
                cerrarModalSubirGuiaPdf();                 
              },
              error: function(jqXHR, text, error){
                  alert('Error!, No se pudo Añadir los datos');
              }
          });
        });

        function obtenerHistorico(opcion){
            $("#mdProcesando").modal('show');    


            if(opcion==4){
                var fechaSalidaDesde=$("#fechaCreacionMin").val().trim();
                var fechaSalidaHasta=$("#fechaCreacionMax").val().trim();  
            }else{
                var fechaSalidaDesde=$("#min").val().trim();
                var fechaSalidaHasta=$("#max").val().trim();                
            }


            if(fechaSalidaDesde==''){
                fechaSalidaDesde='01/01/1900';
            }
            
            if(fechaSalidaHasta==''){   
                var hoy = new Date();
                var dd = hoy.getDate();
                var mm = hoy.getMonth()+1;
                var yyyy = hoy.getFullYear();

                if (dd < 10) {dd = '0' + dd; }
                if (mm < 10) {mm = '0' + mm; }
            
                fechaSalidaHasta=dd + '/' + mm + '/' + yyyy;
            }

            var pedDesde="0";
            if( $("#txtPedidoDesde").val()!=''){
                pedDesde=$("#txtPedidoDesde").val();
            }

            var pedHasta="0";
            if( $("#txtPedidoHasta").val()!=''){
                pedHasta=$("#txtPedidoHasta").val();
            }

            var guiaDesde="0";
            if( $("#txtGuiaDesde").val()!=''){
                guiaDesde=$("#txtGuiaDesde").val();
            }

            var guiaHasta="0";
            if( $("#txtGuiaHasta").val()!=''){
                guiaHasta=$("#txtGuiaHasta").val();
            }
           
            $.ajax({
                url: urlApp +'obtenerHistoricoPedidos',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        salidaDesde: fechaSalidaDesde,
                        salidaHasta: fechaSalidaHasta,
                        emp_codigo: $("#idCliente").val(),
                        idPlanta: $("#idPlanta").val(),
                        pedidoDesde: pedDesde,
                        pedidoHasta: pedHasta,
                        guiaDesde: guiaDesde,
                        guiaHasta: guiaHasta,
                        opcion: opcion 
                      },
                success:function(dato){
                    for(var x=0;x<dato.length;x++){
                        cadena="";
                        if(dato[x].modificado>0){
                            cadena+='<span class="badge badge-primary">' + dato[x].modificado + '</span>';
                        }    
                        if(dato[x].tipoTransporte==2){
                            cadena+='<span class="badge badge-danger">M</span>';
                        }
                        if( dato[x].formula!='' ){
                            cadena+='<span><img src="' + urlApp +'img/iconos/matraz.png" border="0" title="' + dato[x].formula +'" width="15px" height="15pxs"></span>';
                        }
                        if( dato[x].numeroGuia>0 ){
                            cadena+='<span onclick="abrirGuia(1, ' + dato[x].numeroGuia + ', this.parentNode.parentNode);" style="cursor:pointer; cursor: hand"><img src="'+ urlApp + 'img/iconos/guiaDespacho2.png" border="0"></span>';
                        }
                        if( dato[x].certificado!='' ){  
                            cadena+='<a target="_blank" href="'+ urlApp + 'bajarCertificado/'+ dato[x].certificado +'">';
                            cadena+='<img src="'+ urlApp + 'img/iconos/certificado.png" border="0"></a>';
                        }
                        if( dato[x].salida==1 ){
                            cadena+='<span><img src="' + urlApp + 'img/iconos/enTransporte.png" border="0" onclick="verUbicacionGmaps(' + dato[x].Patente + ');" style="cursor:pointer; cursor: hand"></span>';    
                        }

                        celdaPedido='<a target="_blank" href="'+urlApp+'verpedidoNuevaVentana/'+ dato[x].idPedido + '/1/" class="btn btn-xs btn-success" style="width:60px">' + dato[x].idPedido +'</a>';

                        dato[x].colEstado=cadena;
                        dato[x].idPedido=celdaPedido;
                    } 


                    renderGrid(dato);
                    $("#mdProcesando").modal('hide');
                },
                error: function(jqXHR, text, error){
                    $("#mdProcesando").modal('hide');
                    alert('Ha ocurrido un error!, vuelva a presionar el botón Buscar Selección');
                }                
            })
        }

        function ingresarNumeroAuxiliar(row){
            var tabla=$("#tablaDetalle").DataTable();
            var fila=tabla.row(row).index();
            var celda=tabla.cell(fila,14).node();
            $("#numeroAuxiliar").val(celda.getElementsByTagName('a')[0].innerHTML);            
            document.getElementById("numeroAuxiliar").dataset.fila=tabla.row( row ).index();;
            $("#modNumeroAuxiliar").modal('show');
        } 

        function cerrarModalNumeroAuxliar(){
            $("#modNumeroAuxiliar").modal('hide');
        }
        
        function guardarNumeroAuxiliar(){
            var fila=document.getElementById('numeroAuxiliar').dataset.fila;
            var tabla=$("#tablaDetalle").DataTable();
            var celda=tabla.row(document.getElementById("numeroAuxiliar").dataset.fila,0).node();
            var pedido=celda.getElementsByTagName('a')[0].innerHTML;

            for(var x=0;x<tabla.rows().count();x++){
                celda=tabla.cell(x,0).node();
                if(celda.getElementsByTagName('a')[0].innerHTML==pedido){
                    celda=tabla.cell(x,14).node();
                    celda.getElementsByTagName('a')[0].innerHTML=$("#numeroAuxiliar").val();
                    tabla.cell(x,14).draw();
                }
            }

            //guardar Numero auxiliar en el pedido
            $.ajax({
                url: urlApp + "actualizarNumeroAuxiliar",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        idPedido: pedido,
                        numeroAuxiliar: $("#numeroAuxiliar").val()
                      },
                success:function(dato){
                }
            });
            cerrarModalNumeroAuxliar();      
        }        

        $(document).ready(function() {


            var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();

            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }
            $("#max").val(dd + '/' + mm + '/' + yyyy);


            hoy.setDate(hoy.getDate() - 60);
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();
            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }
            $("#min").val(dd + '/' + mm + '/' + yyyy);

           var tituloArchivo='Pedidos Despachados'

            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true
            }) 

            $('#wrapper').toggleClass('sidebar-hide');
            $('.main-menu').find('.openable').removeClass('open');
            $('.main-menu').find('.submenu').removeAttr('style');

            obtenerHistorico(1);

        } );

    </script>
    
@endsection
