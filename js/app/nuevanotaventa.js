    var arrCodigosSoftland=new Array();

    $(document).ready(function() {
        document.getElementById('idUsuarioEncargado').selectedIndex=-1;
        document.getElementById('btnNuevaObra').disabled=true;
        $.ajax({
            async:false, 
            url: urlApp + 'productosCodigosSoftland',
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: {},
            success:function(dato){
                for(var x=0;x<dato.length;x++){
                    arrCodigosSoftland.push([ dato[x].prod_nombre, dato[x].u_nombre, dato[x].codigoSoftland]);
                }
            }
        }); 



    });        

    function datosCotizacion(){
        $("#notificaciones").html('');
        $("#btnDatosCotizacion").prop("disabled", true);
        $("#txtNombreCliente").val('');
        $("#txtFechaCotizacion").val('');
        $("#txtObra").val('');
        $("#idCliente").val('');
        $("#txtUsuarioCrea").val('');
        $("#txtUsuarioValida").val('');
        document.getElementById('idObra').selectedIndex=0;
        $("#txtNombreContacto").val('');
        $("#txtCorreoContacto").val('');
        $("#txtTelefono").val('');
        $("#txtCodClienteSoftland").val('');

        limpiarTabla('tablaDetalle');

        if( $("#txtNumeroCotizacion").val().trim()=='' || $("#txtAno").val().trim()=='' ) {
            $("#btnDatosCotizacion").prop("disabled", false);
            swal(
                {
                    title: 'Debe ingresar número y año de la Cotización!',
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Cerrar',
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
            )  
            return;            
        }

        var cadenaPlantas="<select class='form-control input-sm'>";
        var cadenaFormaEntrega="<select class='form-control input-sm'>";       
        var estadoCot=0;
        $.ajax({
            async:false, 
            url: urlApp + 'datosCotizacion',
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    idCotizacion:  $("#txtNumeroCotizacion").val(),
                    ano: $("#txtAno").val()
                },
            success:function(dato){
                if(dato.length>0){
                    estadoCot=dato[0].cot_estado;
                    if(dato[0].cot_estado!=2) {
                        swal(
                            {
                                title: 'La cotización debe estar enviada!!',
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'Cerrar',
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
                        )
                        return;                        
                    }else{
                        $("#txtNombreCliente").val(dato[0].emp_nombre);
                        $("#txtFechaCotizacion").val(dato[0].cot_fecha_creacion);
                        $("#txtObra").val(dato[0].cot_obra);
                        $("#idCliente").val( dato[0].emp_codigo );
                        $("#txtUsuarioCrea").val( dato[0].usuario_creacion );
                        $("#txtUsuarioValida").val( dato[0].usuario_validacion );
                        if(dato[0].notaVentaSolicitaCodigo){
                            $("#txtCodClienteSoftland").val('');
                            document.getElementById('txtCodClienteSoftland').readOnly=false;                        
                        }else{
                            $("#txtCodClienteSoftland").val(dato[0].emp_codigoSoftland);
                            document.getElementById('txtCodClienteSoftland').readOnly=true;
                        }
                        if(dato[0].emp_solicitaOC=='1'){
                            $("#divOcCliente").html("O.Compra (*)");
                            document.getElementById('txtOrdenCompra').dataset.solicitaOC="1";
                        }else{
                            $("#divOcCliente").html("O.Compra");
                            document.getElementById('txtOrdenCompra').dataset.solicitaOC="0";
                        }                        
                    }
                }else{
                    swal(
                        {
                            title: 'La cotización indicada no existe, ingrese un número de cotización válido.',
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'Cerrar',
                            cancelButtonText: '',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        },
                        function(isConfirm)
                        {
                            if(isConfirm){
                                $("#txtNumeroCotizacion").val('');
                                $("#txtAno").val('');
                                return;
                                
                            }
                        }
                    )                      
                }
            }
        }); 

        if(estadoCot==2){
            $.ajax({
                async:false, 
                url: urlApp + 'apiPlantas',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {},
                success:function(dato){
                    cadenaPlantas+="<option value='0'></option>";
                    for(var x=0;x<dato.length;x++){
                         cadenaPlantas+="<option value='" + dato[x].idPlanta+ "'>" + dato[x].nombre + "</option>";
                    }
                }
            }); 
            cadenaPlantas+="</select>";

            $.ajax({
                async:false,
                url: urlApp + 'apiFormadeEntrega',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {},
                success:function(dato){
                    cadenaFormaEntrega+="<option value='0'></option>";
                    for(var x=0;x<dato.length;x++){
                        cadenaFormaEntrega+="<option value='" + dato[x].idFormaEntrega+ "'>" + dato[x].nombre + "</option>";
                    }             
                }
            }) 
            cadenaFormaEntrega+="</select>";

            listarObras();

            $.ajax({
                async:false,
                url: urlApp + 'cotizacionProductos',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { idCotizacion: $("#txtNumeroCotizacion").val(),
                        ano: $("#txtAno").val() },
                success:function(data){
                    var cont=0;
                    for(var x=0;x<data.length;x++){
                        cont++;
                        cadena="<tr>";
                        cadena+="<td style='display:none'>"+data[x].prod_codigo+"</td>";
                        if(data[x].requiere_diseno=="1"){
                           cadena+="<td style='width:80px'>" + "<input class='form-control input-sm' type='text' maxlength='20'></td>"  
                       }else{
                           cadena+="<td style='width:80px'>" + "<input class='form-control input-sm' type='text' maxlength='20' readonly></td>" 
                       }
                        

                        cadena+="<td style='width:150px'>"+data[x].prod_nombre+"</td>";
                        cadena+="<td style='width:80px; text-align:right;'><input class='form-control input-sm' value='"+data[x].cp_cantidad+"' maxlength='5' onkeypress='return isNumberKey(event)'></td>";
                        cadena+="<td style='width:50px'>"+data[x].u_nombre+"</td>";
                        codConta='';
                        for(var y=0;y<arrCodigosSoftland.length;y++){
                            if(arrCodigosSoftland[y][0] == data[x].prod_nombre && arrCodigosSoftland[y][1] == data[x].u_nombre ){
                                codConta=arrCodigosSoftland[y][2];
                            }
                        }
                        if(codConta==''){
                            notificacion="<div class='alert alert-danger'><strong>Advertencia</strong> ";
                            notificacion=notificacion + "El producto " + data[x].prod_nombre + " / " + data[x].u_nombre + 
                                " no está codificado en Softland, debe solucionar esto para realizar pedidos para esta nota de venta.";
                            notificacion=notificacion + "</div>";    

                            $("#notificaciones").append(notificacion);
                        }
                        
                        cadena+="<td style='width:50px; text-align:right;'>"+number_format(data[x].cp_precio,0)+"</td>";
                        cadena+="<td style='width:250px'>"+data[x].cp_glosa_reajuste+"</td>";            
                        cadena+="<td style='width:150px'>" + cadenaPlantas + "</td>";
                        cadena+="<td style='width:120px'>" + cadenaFormaEntrega + "</td>";
                        cadena+="</tr>";
                        $("#tablaDetalle").append(cadena);
                    }


                    if(cont==0){
                        swal(
                            {
                                title: 'No se encontró información del número y año de cotización solicitado',
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'Cerrar',
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
                        )  
                        return                
                    }           
                }
            }) 
        }
        $("#btnDatosCotizacion").prop("disabled", false);       
    }

    function listarObras(){
        $("#idObra").empty();
        $("#idObra").append( '<option value="-2">Seleccione...</option>');
        $("#idObra").append( '<option value="-1">* RETIRA CLIENTE *</b></option>');
        $("#idObra").append( '<option value="0">** INGRESAR NUEVA OBRA **</b></option>');
        document.getElementById('idObra').selectIndex=0;
         $('#idTransaccion').prop('disabled', true); 
        var ruta= urlApp + "listarObras";
        $.ajax({
            async: false,
            url: ruta,
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    emp_codigo: $("#idCliente").val()
                  },
            success:function(dato){
               
                for(var x=0;x<dato.length;x++){
                    $("#idObra").append( "<option value=" + dato[x].idObra + ">" + dato[x].nombre +"</option>" );
                }
                            
            }
        })        
    }

    function datosObra(){
        var valor='';
        document.getElementById('btnNuevaObra').disabled=true;
        if(document.getElementById('idObra').selectedIndex>=0){
            valor=document.getElementById('idObra').value;
        }

        if( parseInt(valor)==0 ){
            document.getElementById('btnNuevaObra').disabled=false;           
        }

        if( parseInt(valor)>0 ){
            var ruta= urlApp + "datosObra";
            $.ajax({
                url: ruta,
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        idObra: $("#idObra").val()
                      },
                success:function(dato){
                    $("#txtNombreContacto").val( dato['obra'][0].nombreContacto );
                    $("#txtCorreoContacto").val( dato['obra'][0].correoContacto );
                    $("#txtTelefono").val( dato['obra'][0].telefonoContacto );
                }
            })
        }else{
            $("#txtNombreContacto").val('');
            $("#txtCorreoContacto").val('');
            $("#txtTelefono").val('');            
        }     
    }  


    $('#datosNotaVenta').on('submit', function(e) {
        // evito que propague el submit
        e.preventDefault();
        // agrego la data del form a formData
        verSiExistePlanta();

        

    });    

    function verSiExistePlanta(){
        var tabla = document.getElementById('tablaDetalle');
        var seguir = 1;

        for (var i = 1; i < tabla.rows.length; i++){
                var codigoPlanta = tabla.rows[i].cells[7].getElementsByTagName('select')[0].value;
                var codigoUnidad = tabla.rows[i].cells[4].innerHTML;
                var codigoProducto =  tabla.rows[i].cells[0].innerHTML;
                var nombreProducto = tabla.rows[i].cells[2].innerHTML;
            $.ajax({
                async: false,
                url: urlApp + 'selectPlantas',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                    codigoProducto: codigoProducto,                
                    codigoUnidad: codigoUnidad,
                    codigoPlanta: codigoPlanta
                    },
            success:function(data){
                if(data.identificador==0 && codigoPlanta!=0){
                    seguir= 0;
                    swal(
                        {
                            title: 'El producto '+nombreProducto+' no esta en la planta seleccionada',
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
                }

            },
            error: function(jqXHR, text, error){
                alert('Error!, No se pudo Añadir los datos');
            }
        });
        }

        for (var i = 1; i < tabla.rows.length; i++){
            var codigoPlanta = tabla.rows[i].cells[7].getElementsByTagName('select')[0].value;
            var codigoUnidad = tabla.rows[i].cells[4].innerHTML;
            var codigoProducto =  tabla.rows[i].cells[0].innerHTML;
            var nombreProducto = tabla.rows[i].cells[2].innerHTML;
            if(codigoPlanta>0){
            $.ajax({
                async: false,
                url: urlApp + 'verificarTiempoProduccion',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                    codigoProducto: codigoProducto,                
                    codigoUnidad: codigoUnidad,
                    codigoPlanta: codigoPlanta            
                    },
            success:function(data){
                if(data.identificador==0){
                    seguir= 0;
                    swal(
                        {
                            title: 'El producto '+nombreProducto+' no tiene tiempo de producción en esta planta o unidad',
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
                }

            },
            error: function(jqXHR, text, error){
                alert('Error!, No se pudo Añadir los datos');
            }
        });
          }   
        }


        if(seguir == 1){
            crearNotaVenta();
        }else{
            return;
        }
    }


    function crearNotaVenta(){



       

        if($("#txtNumeroCotizacion").val()==''){
            swal(
                {
                    title: 'El Campo Cotización es Obligatorio!!',
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
        }
        if($("#txtAno").val()==''){
            swal(
                {
                    title: 'El Campo Año es Obligatorio!!',
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
        }
        if($("#txtCodClienteSoftland").val().trim()==''){
            swal(
                {
                    title: 'Para crear la Nota de Venta debe ingresar el Código de Cliente Softland!!',
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
        }

          var existe=true;  

          $.ajax({
                async: false,
                url: urlApp + 'validarCodigoSoftlandEmpresa',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                    codigoSoftland: txtCodClienteSoftland.value
                      },
              success:function(data){
                if(data.identificador==0){

                    existe=false;
                    swal(
                        {
                            title: '¡El código softland del cliente no existe en contabilidad!' ,
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
         


        if( document.getElementById('idUsuarioEncargado').selectedIndex<1 ){
            swal(
                {
                    title: 'Debe seleccionar el Ejecutivo de QLSA encargado de esta venta.',
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
        }

        if( document.getElementById('idCondicionPago').selectedIndex<1 ){
            swal(
                {
                    title: 'Debe seleccionar una condición de pago!!',
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
        }
        if( document.getElementById('txtOrdenCompra').dataset.solicitaOC=="1" && $("#txtOrdenCompra").val().trim()==""){
            swal(
                {
                    title: 'Debe ingresar el Nº de Orden de Compra del cliente!!',
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
        }

        var cont=0;

        var tabla = document.getElementById('tablaDetalle');
        var cadena='[';
        var errorPlantaFormaEntrega=false;

        var entregaEnObra=0;

        for (var i = 1; i < tabla.rows.length; i++){
            if (tabla.rows[i].cells[7].getElementsByTagName('select')[0].value=='0' || tabla.rows[i].cells[8].getElementsByTagName('select')[0].value=='0' ) {
                errorPlantaFormaEntrega=true;
                break;
            }
            if (tabla.rows[i].cells[8].getElementsByTagName('select')[0].value=='1') {
                entregaEnObra++;
            }
            cadena+='{';
            cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML  + '", ';
            cadena+='"cantidad":"'+  tabla.rows[i].cells[3].getElementsByTagName('input')[0].value  + '", ';
            cadena+='"formula":"'+  tabla.rows[i].cells[1].getElementsByTagName('input')[0].value + '", ';
            cadena+='"u_codigo":"'+  tabla.rows[i].cells[4].innerHTML  + '", ';
            cadena+='"precio":"'+  tabla.rows[i].cells[5].innerHTML.replace('.','').replace('.','')  + '", ';
            cadena+='"idPlanta":"'+  tabla.rows[i].cells[7].getElementsByTagName('select')[0].value + '", ';
            cadena+='"idFormaEntrega":"'+  tabla.rows[i].cells[8].getElementsByTagName('select')[0].value + '"';
            cadena+='}, ';  
            
            
                var codigoPlanta = tabla.rows[i].cells[7].getElementsByTagName('select')[0].value;
                var codigoUnidad = tabla.rows[i].cells[4].innerHTML;
                var codigoProducto =  tabla.rows[i].cells[0].innerHTML;
            $.ajax({
                async: false,
                url: urlApp + 'selectPlantas',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                    codigoProducto: codigoProducto,                
                    codigoUnidad: codigoUnidad,
                    codigoPlanta: codigoPlanta
                    },
            success:function(data){
                if(data.identificador==0){
                    swal(
                        {
                            title: 'El producto no esta en la planta seleccionada',
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
                }
            },
            error: function(jqXHR, text, error){
                alert('Error!, No se pudo Añadir los datos');
            }
        });

        }

       
      
     

        


        if (errorPlantaFormaEntrega){
                swal(
                    {
                        title: 'Debe completar en todas las líneas la Planta y Forma de Entrega',
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
        }


        if ($("#idObra").val()<0 && entregaEnObra>0){
            swal(
                {
                    title: 'Existe al menos un producto con entrega en obra o esta seleccionando ingresar nueva obra, debe ingresar los datos de la obra o seleccionar alguna obra existente.',
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
    }
        
        cadena=cadena.slice(0,-2);
        cadena+=']';


        var formData = new FormData( $("#datos")[0] );

        formData.append("cot_numero", $("#txtNumeroCotizacion").val());
        formData.append("cot_ano", $("#txtAno").val());
        formData.append("idObra", $("#idObra").val());
        formData.append("observaciones", $("#txtObservaciones").val());
        formData.append("contacto", $("#txtNombreContacto").val() );
        formData.append("correo", $("#txtCorreoContacto").val() );
        formData.append("telefono", $("#txtTelefono").val() );
        formData.append("contacto", $("#txtNombreContacto").val() );
        formData.append("ordenCompraCliente", $("#txtOrdenCompra").val());
        formData.append("idUsuarioEncargado", $("#idUsuarioEncargado").val() );
        formData.append("codigoClienteSoftland", $("#txtCodClienteSoftland").val());
        formData.append("idCondicionPago", $("#idCondicionPago").val());
        formData.append("detalle", cadena);

        var ruta= urlApp + "grabarNuevaNotaVenta";
        $.ajax({
            url: ruta,
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,            
            success:function(dato){
                    swal(
                        {
                            title: 'Se ha creado la Nota de Venta Nº ' + dato.identificador ,
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
                               location.href="listarNotasdeVenta";                               
                            }
                        }
                    )                   
            }
        })
    }