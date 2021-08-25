@extends('plantilla')      

@section('contenedorprincipal')


<div style="padding: 20px">
    <div class="panel panel-default table-responsive">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="text" id="perfil"  value="{{Session::get('idPerfil')}}" hidden>

      
        
        <div class="panel-heading">
            <b id="b"> Costo Flete y Tiempo de Traslado {{ $subtitulo }}</b>
        </div>
        <br><br>
        <div style="padding-bottom: 15px">
            <div class="row">
                <div class="col-md-2">
                </div>
               <div class="col-md-9" id="filtro" style="display: inline">
                Filtro&nbsppor&nbspFecha&nbspCreación
					<input id="fechaInicio" class="form-control input-sm date" style="display: inline; width: 140px" data-date-end-date="0d">&nbsp&nbsp
					<input id="fechaTermino" class="form-control input-sm date" style="display: inline; width: 140px" data-date-end-date="0d">
                    <input type="button" class="btn btn-success btn-sm" style="display: inline;" onclick="resumenGeneral()" value="Buscar">
				</div>
            </div>                    
            
        </div>

        

        <div class="padding-md clearfix" id="cuadro1">
	        <div style="width: 80%">        
	            <table id="tablaNotas" class="table table-hover table-condensed table-responsive" style="width: 100%">
	                <thead>
	                    <th style="width:80px">Nº Nota Venta</th>
	                    <th style="width:300px">Cliente</th>
                        <th style="width:250px">Obra/Planta</th>
                        <th style="width:120px">Planta QLSA</th>
                        <th style="width:120px">Unidad</th>
                        <th style="width:120px">Fecha Creación</th>           
                        <th style="width:80px">Costo Flete ($/Unidad)</th>
                        <th style="width:80px">Distancia (km)</th>
                        <th style="width:80px">Tiempo Traslado (horas)</th>
                        <th style="display: none;">id usuario</th>
                        <th style="display: none;">id planta</th>
                        <th style="display: none;">Costo Flete ($/Unidad)</th>
                        <th style="display: none;">Distancia (km)</th>
                        <th style="display: none;">Tiempo Traslado (horas)</th>

	                </thead>
	                <tbody id="tablan">
                    
	                   
	                </tbody>
	            </table>
	        </div>
		    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
                @if(Session::get('idPerfil')=='5' or Session::get('idPerfil')=='10' or Session::get('idPerfil')=='18')
                    <button id="btnGuardarCambios" class="btn btn-sm btn-success" style="width:120px" onclick="abrirCuadroEspera();">Guardar Cambios</button>
                @endif
		        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
		    </div>        
        </div>
		<div class="padding-md clearfix" id="cuadro2" style="display: none">

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


@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>  
<!-- Bootstrap -->
<script src="{{ asset('/') }}bootstrap/js/bootstrap.js"></script>

<script src="{{ asset('/') }}js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/') }}js/buttons.html5.min.js"></script>


    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script> 
    <!-- <script src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>  -->
    <script src="{{ asset('/') }}js/app/funciones.js"></script>
    <script src="js/syncfusion/ej.web.all.min.js"> </script>
    <script src="{{ asset('/') }}js/syncfusion/lang/ej.culture.de-DE.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>




    <!-- Timepicker -->

    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>  
    

    <script>
   
       

       

    </script>

	<script>

       function resumenGeneral(){
     
           var fechaInicio1 = fechaAtexto($("#fechaInicio").val());
           var fechaTermino1 =  fechaAtexto($("#fechaTermino").val());
           if($("#b").text() == " Costo Flete y Tiempo de Traslado (Notas de Venta Vigentes)"){
               var urll = 'notaVentaVigenteCargos1';
               
           }else if($("#b").text() == " Costo Flete y Tiempo de Traslado (Notas de Venta Cerradas)"){

               var urll ='notaVentaCerradaCargos1';
              
           }else if($("#b").text()== " Costo Flete y Tiempo de Traslado (Asignaciones Pendientes)"){
              var urll= 'notaVentaCargosUrgente1'
              $("#filtro"). hide();
             

           }
     
           
            $.ajax({
                url: urlApp + urll,
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {
                    fechaInicio:fechaInicio1,
                    fechaTermino: fechaTermino1
                },
                success:function(dato){
                   
                    var tabla=$("#tablaNotas").DataTable();
                    tabla.clear().draw();
                    
                    for(var x=0;x<dato.length;x++){
                        
                        var codigo = "<input class='form-control input-sm' style='display: none;' value=" + dato[x].u_codigo + "  maxlength='7' onkeypress='return isIntegerKey(event)'>";
                        var idPlanta = "<input class='form-control input-sm' style='display: none;' value=" + dato[x].idPlanta + "  maxlength='7' onkeypress='return isIntegerKey(event)'>";
                       
                        var fechamedificada = dato[x].fecha_hora_creacion;
                        var fecha= fechamedificada.split(" ")[0];
                        var hora =  fechamedificada.split(" ")[1];
                        fecha1 = String(fecha);  
                        var ano = fecha1.split("-")[0];
                        var mes = fecha1.split("-")[1];
                        var dia = fecha1.split("-")[2];
                        var fecha2 = dia+"/"+mes+"/"+ano;
                        var rowNode= [
                                        idNotaVenta=dato[x].idNotaVenta ,
                                        dato[x].nombreCliente,
                                        dato[x].nombreObra,
                                        dato[x].nombrePlanta,
                                        dato[x].nombreUnidad,
                                        idPlanta,            
                                        fecha2+" "+hora 
                                                  
                                    ];
                       var flete = "<input class='form-control input-sm' value=" + number_format( dato[x].flete, 0, '.', ',' ) + "  maxlength='9' onkeypress='return isIntegerKey(event)'>";
                       var distancia = "<input class='form-control input-sm' value=" + number_format(dato[x].distancia, 0, '.', ',' ) + " maxlength='9' onkeypress='return isIntegerKey(event)'>";
                       var tiempoTraslado = "<input class='form-control input-sm' value=" + number_format( dato[x].tiempoTraslado, 0, '.', ',' ) + " maxlength='9' onkeypress='return isIntegerKey(event)'>";
                       
                       if($("#perfil").val() == 5 || $("#perfil").val() == 10 || $("#perfil").val() == 18){
                            var rowNode1=[
                                            flete,
                                            distancia,
                                            tiempoTraslado,
                                            codigo,
                                            dato[x].flete,
                                            dato[x].distancia,
                                            dato[x].tiempoTraslado, 
                                        ];
             
                       }else{
                        var rowNode1= [    
                                         number_format( dato[x].flete, 0, '.', ',' ),
                                         number_format( dato[x].distancia, 0, '.', ',' ),
                                         number_format( dato[x].tiempoTraslado, 0, '.', ',' ),
                                         codigo,
                                         dato[x].flete,
                                         Math.round(dato[x].distancia),
                                         Math.round(dato[x].tiempoTraslado), 
                                      
                                     ];

                       }
                     
                    
                      
                        var nn = tabla.row.add([rowNode[0],
                                                rowNode[1],
                                                rowNode[2],
                                                rowNode[3],
                                                rowNode[4],
                                                rowNode[6],
                                               
                                                rowNode1[0],
                                                rowNode1[1],
                                                rowNode1[2],
                                                rowNode1[3],
                                                rowNode[5],
                                                rowNode1[4],
                                                Math.round(rowNode1[5]),
                                                Math.round(rowNode1[6])
                                                
                                                ]);
                        tabla.columns([9,10,11,12,13]).visible(false, false);

                        var fila=tabla.row( nn ).index();
                        var celdas=tabla.row( nn).data();
               
                     
                        var celda=tabla.cell(fila,0).node();
                        $( celda ).css( 'text-align', 'right' ).css( 'width', '60px');

                        var celda=tabla.cell(fila,4).node();
                        $( celda ).css( 'text-align', 'right' ).css( 'width', '60px');

                    }
                  tabla.draw();
                  
                  actualizarFiltros( tabla );

                }
            })
       }
        function formato(texto){
            return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
        }

        $('#mdProcesando').on('shown.bs.modal', function (e) {
          guardarCambios();
        })  

        function abrirCuadroEspera(){
            $("#mdProcesando").modal('show');
        }      
		$(document).ready(function() {
            $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
            var hoy = new Date();
            hoy.setDate(hoy.getDate() - 3);

            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();

            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }
            if($("#b").text() == " Costo Flete y Tiempo de Traslado (Notas de Venta Cerradas)"){
                $("#fechaInicio").val(dd + '/' + mm + '/' + (yyyy-1));
            }else{
                $("#fechaInicio").val(dd + '/' + mm + '/' + (yyyy-5));
            }
   

            var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();
            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }

            $("#fechaTermino").val(dd + '/' + mm + '/' + yyyy);

            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true
            });     


            $('#tablaNotas thead tr').clone(true).appendTo( '#tablaNotas thead' );
            $('#tablaNotas thead tr:eq(1) th').each( function (i) {
                var title = $(this).text(); 
                if(title.trim()!='' && title.trim()=='Unidad' ){
                    $(this).html( '<select id="selUnidad" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Planta QLSA' ){
                    $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );                    
                }else{
                    if(i<5){
                        $(this).html( '<input type="text" class="form-control input-sm" placeholder="Buscar..." />' );
                        $( 'input', this ).on( 'keyup change', function () {
                            if ( tabla.column(i).search() !== this.value ) {
                                tabla
                                    .column(i)
                                    .search( this.value )
                                    .draw();
                            }
                        } ); 
                    }else{
                        $(this).html( '' );
                    }                  
                }
            
            } );
            if($("#b").text() == " Costo Flete y Tiempo de Traslado (Notas de Venta Vigentes)"){
               var urll = 'notaVentaVigenteCargos1';
               var titulo = 'nota Venta Vigente '
               
           }else if($("#b").text() == " Costo Flete y Tiempo de Traslado (Notas de Venta Cerradas)"){

               var urll ='notaVentaCerradaCargos1';
               var titulo = 'nota Venta Cerrada '

           }else if($("#b").text()== " Costo Flete y Tiempo de Traslado (Asignaciones Pendientes)"){
              var urll= 'notaVentaCargosUrgente1'
              $("#filtro"). hide();
              var titulo = 'nota Venta Asignaciones Pendientes '


           }
            
            var tabla=$('#tablaNotas').DataTable({
                orderCellsTop: true,
                "language": {
                        "search": "Buscar:"
                }, 
                fixedHeader: true,
                lengthMenu: [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                scrollX: true,
                scrollCollapse: true,
                dom: 'Bfrtip',              
                columnDefs:[
                                {
                                    render: function (data, type, full, meta) {
                                        return "<div style='white-space:normal;width:100%'>" + data + "</div>";
                                    },                                     
                                    targets:[1]
                                }
                            ],

                buttons: [
                    'pageLength', 
                    {
                        text: 'Actualizar',
                        action: function ( e, dt, node, config ) {
                            this.disable();    
                            location.reload(true);                        
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: titulo,
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,11,12,13]
                        }
                    }                    
                    
                ],        
                // "order": [[ 0, "desc" ]],                    
                // // language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function () {
                    actualizarFiltros(this.api())
                }               
            });     

            resumenGeneral();

        } );
 
        function actualizarFiltros(tabla){
            tabla.columns(4).every( function () {
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
            
            tabla.columns(3).every( function () {
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



		function guardarCambios(){
            document.getElementById('btnGuardarCambios').disabled=true;
			var tabla= $("#tablaNotas").DataTable();
		    var cadena='[';
		    var costo="0";
		    for (var i = 0; i < tabla.rows().count(); i++){
		    	if(tabla.cell(i,6).node().getElementsByTagName('input')[0].value==''){
		    		flete="0";
		    	}else{
		    		flete1=tabla.cell(i,6).node().getElementsByTagName('input')[0].value;
                    flete2=flete1.toString();
                    flete = flete2.replace(/\./g,'');
		    	}

                if(tabla.cell(i,7).node().getElementsByTagName('input')[0].value==''){
                    distancia="0";
                }else{
                    distancia1=tabla.cell(i,7).node().getElementsByTagName('input')[0].value;
                    distancia2=distancia1.toString();
                    distancia = distancia2.replace(/\./g,'');
                }
                if(tabla.cell(i,8).node().getElementsByTagName('input')[0].value==''){
                    tiempoTraslado="0";
                }else{
                    tiempoTraslado1=tabla.cell(i,8).node().getElementsByTagName('input')[0].value;
                    tiempoTraslado2=tiempoTraslado1.toString();
                    tiempoTraslado = tiempoTraslado2.replace(/\./g,'');
                    // tiempoTraslado= tiempoTraslado.remplace(".","");
                }   


                if( isNaN(flete) || isNaN(distancia) || isNaN(tiempoTraslado) ){
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
               
                
                u_codigo=tabla.cell(i,9).node().getElementsByTagName('input')[0].value;
                idPlanta=tabla.cell(i,10).node().getElementsByTagName('input')[0].value;



                cadena+='{';
                cadena+='"idNotaVenta":"'+ tabla.cell(i,0).data() + '", ';
                cadena+='"idPlanta":"'+ idPlanta+ '", ';
                cadena+='"u_codigo":"'+u_codigo + '", ';
                cadena+='"flete":"'+ flete  + '", ';
                cadena+='"distancia":"'+ distancia  + '", ';
                cadena+='"tiempoTraslado":"' + tiempoTraslado + '"';
                cadena+='}, ';
		    }

		    cadena=cadena.slice(0,-2);
		    cadena+=']';

            $.ajax({
                url: urlApp + "actualizarNotaVentaCargos",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {                      
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

                }
            })	

		}
        

        function ordenPorFecha(){
            var min = document.getElementById('min').value;
            var max = document.getElementById('max').value;
            n =  new Date();
            //Año
            y = n.getFullYear();
            //Mes
            m = n.getMonth() + 1;
            //Día
            d = n.getDate();
            dat = (d + "/" + m + "/" + y);
          
            var elem = min.split('/');
            
            dia = elem[0];
            mes = elem[1];
            anio = elem[2];
        };
   

    </script>


@endsection        	
