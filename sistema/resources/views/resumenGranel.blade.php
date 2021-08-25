<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Próximos pedidos a granel</title>
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
        <link rel="shortcut icon" href="{{ asset('/') }}favicon.ico">	
	</head>
	<body onload="maximizar();">
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div style="padding: 10px">
            <div>
               <h2>Próximos Pedidos (Granel)</h2> 
              
            </div>
            <div class="row" style="padding-top: 15px; padding-bottom: 15px">
				<div class="col-md-3" style="display: inline">
                    @if( Session::get('idPlanta')=='0')
					Planta de Origen&nbsp&nbsp&nbsp&nbsp&nbsp
					<select id="idPlanta" class="form-control input-sm" style="display: inline; width: 170px" >
						<option value="0">Todas</option>
                        @foreach($plantas as $item)
                            <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                        @endforeach					
					</select>
                    @endif
				</div>
				<div class="col-md-4" style="display: inline">
					Rango fecha carga&nbsp&nbsp&nbsp&nbsp&nbsp
					<input id="fechaInicio" class="form-control input-sm date" style="display: inline; width: 140px">&nbsp&nbsp
					<input id="fechaTermino" class="form-control input-sm date" style="display: inline; width: 140px">
				</div>
				<div class="col-md-3">
                    <label class="label-checkbox"><input type="checkbox" id="incluirSinFecha"><span class="custom-checkbox"></span>Incluir pedidos sin fecha de carga asignada</label>
				</div>
				<div class="col-md-1">
					<button class="btn btn-success btn-sm" style="width: 120px" onclick="resumenGeneral();">Buscar</button>
				</div>							
			</div>
		</div>
		<div style="padding: 10px">
			<table id="tablaPedidos" class="table table-hover table-condensed table-responsive" style="width: 100%">
				<thead>
                    <th style="width:90px"></th>
					<th style="width:60px">Forma Entrega</th>
					<th style="width:60px;text-align: right;">Nº Pedido</th>
					<th style="width:80px">Fecha Carga</th>
					<th style="width:80px">Planta Origen</th>
					<th style="width:80px">Producto</th>
					<th style="width:50px;text-align: right;">Cantidad</th>
					<th style="width:150px">Cliente</th>
					<th style="width:150px">Obra/Planta</th>
					<th style="width:100px">Transportista</th>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

<script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
<script>

    function maximizar(){
    window.moveTo(0,0);
    window.resizeTo(screen.width,screen.height);
    }

    window.setInterval(function () {
        resumenGeneral();
    }, 15000);

    function resumenGeneral(){
    	var incluirPedidosSinFechaCarga='0';
    	if( document.getElementById("incluirSinFecha").checked ){
    		incluirPedidosSinFechaCarga='1';
    	}


        var codPlanta={{Session::get('idPlanta')}};

        if(codPlanta==0){
            codPlanta=$("#idPlanta").val();
        }

        $.ajax({
            url: urlApp + "resumenGranel",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: {
            	idPlanta: codPlanta,
            	fechaInicio: fechaAtexto($("#fechaInicio").val()),
            	fechaTermino: fechaAtexto($("#fechaTermino").val()),
            	incluirSinFecha: incluirPedidosSinFechaCarga
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
                               
                    if(dato[x].cantidadReal>0 ){
                        cadena+='<span><img src="' + urlApp +'img/iconos/cargacompleta.png" border="0"></span>';
                    }

                    if(dato[x].horaCarga!='' ){
                        cadena+='<span><img src="' + urlApp + 'img/iconos/time.png" border="0" title="' +dato[x].fechaCarga_dma + ' ' + dato[x].horaCarga +'"></span>';
                    }

                    if(dato[x].empresaTransporte!='' ){
                        cadena+='<span><img src="' + urlApp + 'img/iconos/user.png" border="0" title="' + dato[x].empresaTransporte + '/' +  dato[x].apellidoConductor +'"></span>';
                    }
                                 
                    if( dato[x].numeroGuia>0 ){
                        cadena+='<a href="'+ urlApp + 'bajarGuiaDespacho/' + dato[x].numeroGuia + '"><img src="' + urlApp + 'img/iconos/guiaDespacho2.png" border="0"></a>';
                    }

                    if( dato[x].certificado!='' ){
                        cadena+='<a href="'+ urlApp + 'bajarCertificado/' + dato[x].certificado + '"><img src="'+ urlApp + 'img/iconos/certificado.png" border="0"></a>';
                    } 

                    if ( dato[x].salida==1 ){
                        cadena+='<span><img src="'+ urlApp +'img/iconos/enTransporte.png" border="0" style="cursor:pointer; cursor: hand"></span>'; 
                    }
                                     



			        var rowNode= tabla.row.add([cadena,
                                                dato[x].formaEntrega,
			        							dato[x].idPedido,
			        							dato[x].fechaCarga + ' ' + dato[x].horaCarga ,
			        							dato[x].nombrePlanta,
			        							dato[x].prod_nombre,
			        							dato[x].cantidad,
			        							dato[x].nombreCliente,
			        							dato[x].nombreObra,
			        							dato[x].empresaTransporte]);

			        var fila=tabla.row( rowNode ).index();
                    var celdas=tabla.row( rowNode).data();

			        var celda=tabla.cell(fila,2).node();

			        $( celda )
			           .css( 'text-align', 'right' )
			           .css( 'width', '60px');
                       
			        var celda=tabla.cell(fila,6).node();
			        $( celda )
			           .css( 'text-align', 'right' )
			           .css( 'width', '60px');	

                    if(dato[x].idEstadoPedido=='0'){
                        for(var z=0;z<celdas.length;z++){
                            var celda=tabla.cell(fila,z).node();
                            $( celda ).css('background-color','red').css('color','white');
                        }
                    }   				           	        
			    } 
			    tabla.draw();


                tabla.columns(8).every( function () {
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


                tabla.columns(7).every( function () {
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

                tabla.columns(1).every( function () {
                    var column = this;
                    var select = $("#selFormaEntrega" ).empty().append( '<option value=""></option>' )
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

                tabla.columns(4).every( function () {
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
        })        
    }

	$(document).ready(function() {
        $.fn.dataTable.moment('DD-MM-YYYY HH:mm');

		var hoy = new Date();
		var dd = hoy.getDate();
		var mm = hoy.getMonth()+1;
		var yyyy = hoy.getFullYear();

    	if (dd < 10) {dd = '0' + dd; }
    	if (mm < 10) {mm = '0' + mm; }
		$("#fechaInicio").val(dd + '/' + mm + '/' + yyyy);


		hoy.setDate(hoy.getDate() + 3);
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
            }else if(title.trim()!='' && title.trim()=='Forma Entrega' ){
                $(this).html( '<select id="selFormaEntrega" class="form-control input-sm"></select>' );
            }else if(title.trim()!='' && title.trim()=='Planta Origen' ){
                $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );
            }else{
                if(title.trim()!=''){
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
            }
         
        } );

    
            var table=$('#tablaPedidos').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                "scrollX": true,
                lengthMenu: [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],                
                dom: 'Bfrtip',
                buttons: [
                {
                    text: 'Actualizar',
                    action: function ( e, dt, node, config ) {
                       // this.disable();    
                        resumenGeneral(); 
                    }
                },  
                
                
                'pageLength'
                 
            ],                  
            "order": [[ 1, "asc" ]],                        
            language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                preDrawCallback: function( settings ) {
                    // document.getElementById('panelBody').style.display="block";
                }
        });
     });
     function formato(texto){
			return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
		}
</script>