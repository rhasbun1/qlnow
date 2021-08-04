<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Pedidos despachados a granel y entregados en obra</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Font Awesome -->
		<link href="css/font-awesome.min.css" rel="stylesheet">
		
		<!-- Perfect -->
		<link href="css/app.min.css" rel="stylesheet">

		<!-- Datatable -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <link href="{{ asset('/') }}css/datatables.min.css" rel="stylesheet">    
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
		<!-- Datepicker -->
		<link href="{{ asset('/') }}css/datepicker.css" rel="stylesheet"/>		
	</head>
	<body>
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div style="padding: 10px">
            <div>
                <h2>
                    Pedidos despachados (granel)
                </h2>
            </div>
			<div class="row" style="padding-top: 15px; padding-bottom: 15px">
                @if( Session::get('idPlanta')=='0')
    				<div class="col-md-3" style="display: inline">
                        
    					Planta de Origen&nbsp&nbsp&nbsp&nbsp&nbsp
    					<select id="idPlanta" class="form-control input-sm" style="display: inline; width: 170px" >
    						<option value="0">Todas</option>
                            @foreach($plantas as $item)
                                <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                            @endforeach					
    					</select>
                        
    				</div>
                @endif
				<div class="col-md-9" style="display: inline">
					Seleccione la(s) fecha(s) de despachos que desea consultar
					<input id="fechaInicio" class="form-control input-sm date" style="display: inline; width: 140px" data-date-end-date="-3d">&nbsp&nbsp
					<input id="fechaTermino" class="form-control input-sm date" style="display: inline; width: 140px" data-date-end-date="0d">
                    <button class="btn btn-success btn-sm" style="display: inline;" onclick="resumenGeneral();">Buscar</button>
				</div>					
			</div>
		</div>
		<div style="padding: 10px">
			<table id="tablaPedidos" class="table table-hover table-condensed table-responsive" style="width: 100%">
				<thead>
					<th style="width:80px;">Nº de Pedido</th>
                    <th></th>
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
				</tbody>
			</table>
		</div>
	</body>
</html>
<!-- Datepicker -->
<script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>  
<!-- Bootstrap -->
<script src="{{ asset('/') }}bootstrap/js/bootstrap.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('/') }}js/app/funciones.js"></script>
<script src="{{ asset('/') }}js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/') }}js/buttons.html5.min.js"></script>
<script>
    window.setInterval(function () {
        resumenGeneral();
   
    }, 15000);

    function resumenGeneral(){

        var codPlanta={{Session::get('idPlanta')}};
        
        if(codPlanta==0){
            codPlanta=$("#idPlanta").val();
        }

        $.ajax({
            url: urlApp + "obtenerPedidosDespachados",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',    
            data: {
            	idPlanta: codPlanta,
            	fechaInicio: fechaAtexto($("#fechaInicio").val()),
            	fechaTermino: fechaAtexto($("#fechaTermino").val())
            },
            success:function(dato){
                
            	var tabla=$("#tablaPedidos").DataTable();
            	tabla.clear().draw();
            	for(var x=0;x<dato.length;x++){
                    cadena="";
                    if(dato[x].modificado>0){
                        cadena+='<span class="badge badge-primary">'+dato[x].modificado+'</span>';
                    }
                                      
                    if(dato[x].tipoTransporte==2){
                        cadena+='<span class="badge badge-danger">M</span>';
                    }
         
                    
                    if( dato[x].numeroGuia>0 ){
                        cadena+='<a target="_blank" href="'+ urlApp + 'bajarGuiaDespacho/' + dato[x].numeroGuia + '"><img src="' + urlApp + 'img/iconos/guiaDespacho2.png" border="0"></a>';
                    }

                    if( dato[x].certificado!='' ){
                        cadena+='<a target="_blank" href="'+ urlApp + 'bajarCertificado/' + dato[x].certificado + '"><img src="'+ urlApp + 'img/iconos/certificado.png" border="0"></a>';
                    } 
                    var fechaHora = dato[x].fechaHoraSalida;
                    fecha = fechaHora.split(" ", 1 ); 
                    hora = fechaHora.split(" ")[1]; 
                    fecha1 = String(fecha);  
                    var ano = fecha1.split("-")[0];
                    var mes = fecha1.split("-")[1];
                    var dia = fecha1.split("-")[2];
                    var fecha2 = dia+"/"+mes+"/"+ano;
			        var rowNode= tabla.row.add([
			        							dato[x].idPedido,
                                                cadena,
			        							dato[x].plantaQLSA,
			        							dato[x].nombreCliente,
			        							dato[x].nombreObra,
			        							dato[x].prod_nombre,
			        							dato[x].cantidadDespachada,
			        							dato[x].nombreTransporte,
                                                dato[x].nombreConductor,
                                                (fecha2+" "+hora)                                                
                                                ]);

			        var fila=tabla.row( rowNode ).index();
                    var celdas=tabla.row( rowNode).data();

			        var celda=tabla.cell(fila,0).node();
			        $( celda ).css( 'text-align', 'center' ).css( 'width', '60px');

                    var celda=tabla.cell(fila,5).node();
                    $( celda ).css( 'text-align', 'center' ).css( 'width', '60px');

			    }
			    tabla.draw();


                tabla.columns(4).every( function () {
                    var column = this;
                    var select = $("#selObra" ).empty().append( '<option value=""></option>' )
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
                    var select = $("#selCliente" ).empty().append( '<option value=""></option>' )
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

                tabla.columns(5).every( function () {
                    var column = this;
                    var select = $("#selProducto").empty().append( '<option value=""></option>' )
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

                tabla.columns(7).every( function () {
                    var column = this;
                    var select = $("#selTransporte").empty().append( '<option value=""></option>' )
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

                tabla.columns(8).every( function () {
                    var column = this;
                    var select = $("#selConductor").empty().append( '<option value=""></option>' )
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

        })        
    }

	$(document).ready(function() {


		var hoy = new Date();
        hoy.setDate(hoy.getDate() - 3);

		var dd = hoy.getDate();
		var mm = hoy.getMonth()+1;
		var yyyy = hoy.getFullYear();

    	if (dd < 10) {dd = '0' + dd; }
    	if (mm < 10) {mm = '0' + mm; }
		$("#fechaInicio").val(dd + '/' + mm + '/' + yyyy);


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
        }) 

        $('#tablaPedidos thead tr').clone(true).appendTo( '#tablaPedidos thead' );
        $('#tablaPedidos thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();

            if(title.trim()!='' && title.trim()=='Obra/Planta' ){
                $(this).html( '<select id="selObra" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Cliente' ){
                $(this).html( '<select id="selCliente" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Producto' ){
                $(this).html( '<select id="selProducto" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Planta Origen' ){
                $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Transporte' ){
                $(this).html( '<select id="selTransporte" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Conductor' ){
                $(this).html( '<select id="selConductor" class="form-control input-sm"></select>' );                            
            }else{
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

        var table=$('#tablaPedidos').DataTable({
             orderCellsTop: true,
             fixedHeader: true,         
            "lengthMenu": [[6, 10, 20, -1], ["6", "10", "20", "Todos"]],
            dom: 'Bfrtip',
            "scrollX": true,
            buttons: [
                {
                    text: 'Actualizar',
                    action: function ( e, dt, node, config ) {
                       // this.disable();    
                        resumenGeneral(); 
                    }
                },
                'pageLength',
                {
                    extend: 'excel',
                    title: 'Pedidos en Proceso',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',                           
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                }                
            ],                  
            "order": [[ 9, "desc" ]],                        
            language:{url: "{{ asset('/') }}locales/datatables_ES.json"}

        });
	 });
     function formato(texto){
                             return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
                        }
</script>