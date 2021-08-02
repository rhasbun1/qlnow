    function nuevaObra(){
        btnAgregarObra.innerHTML="Crear";
        $("#idObra").val("0");
        $("#filaObra").val("0");
        $("#txtNombreObra").val('');
        $("#txtDescripcionObra").val('');
        $("#txtNombreContactoObra").val('');
        $("#txtCorreoContactoObra").val('');
        $("#txtTelefonoObra").val('');
        $("#txtDistancia").val('');
        $("#txtTiempo").val('');
        $("#txtCostoFlete").val('');
        $("#txtCodigoSoftland").val('');
        $("#mdNuevaObra").modal("show");
        document.getElementById('habilitada').checked=true;
        document.getElementById('idCliente').selectedIndex=-1;
        $("#txtNombreObra").focus();
    }

    function editarObra(idObra, row){
        btnAgregarObra.innerHTML="Guardar";
        $("#idObra").val(idObra);
        var tabla=$("#tabla").DataTable();

        $("#filaObra").val( tabla.row(row).index() );
        $.ajax({
            url: urlApp + "datosObra",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    idObra: idObra
                  },
            success:function(dato){
                obra=dato['obra'];
                distancias=dato['distancias']
                $("#txtNombreObra").val(obra[0].nombre);
                $("#txtDescripcionObra").val(obra[0].descripcion);
                $("#txtNombreContactoObra").val(obra[0].nombreContacto);
                $("#txtCorreoContactoObra").val(obra[0].correoContacto);
                $("#txtTelefonoObra").val(obra[0].telefonoContacto);
                $("#txtDistancia").val(obra[0].distancia);
                $("#txtTiempo").val(obra[0].tiempo);
                $("#txtCostoFlete").val(obra[0].costoFlete);
                var sel=document.getElementById("idCliente");
                

                for(var x=0; x<sel.length; x++){
                    if(sel.options[x].value==obra[0].emp_codigo){
                        sel.selectedIndex=x;
                        break;
                    }
                }

                $("#tabDistancias tbody").remove();
                for(var x=0; x<distancias.length; x++){
                    cadena="<tr><td>"+ distancias[x].nombrePlanta + 
                    "</td><td><input class='form-control input-sm' maxlength='5' style='width: 80px' data-idplanta='" + distancias[x].idPlanta + 
                    "' onkeypress='return isNumberKey(event)' value='" + distancias[x].tiempoTraslado +"'></td></tr>";
                    $("#tabDistancias").append(cadena);
                }

                if(obra[0].habilitada==1){
                    document.getElementById('habilitada').checked=true;
                }else{
                    document.getElementById('habilitada').checked=false;
                }
                
                $("#mdNuevaObra").modal("show"); 
                $("#txtNombreObra").focus();
            }
        }) 
               
    }


    function cerrarNuevaObra(){
        $("#mdNuevaObra").modal("hide");
    }

    function agregarNuevaObra(){
        
        if($("#idCliente").val()==null || $("#txtNombreObra").val().trim()=='' || $("#txtNombreContactoObra").val().trim()=='' || $("#txtTelefonoObra").val().trim()=='' ){
            swal(
                {
                    title: 'Debe ingresar todos los datos exigidos (*).',
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

        var tabla=$("#tabla").DataTable();
        var fila = $("#filaObra").val();

        for (var i = 0; i < tabla.rows().count(); i++){
            if( (tabla.cell(i,0).data().trim().toUpperCase()==$("#txtNombreObra").val().trim().toUpperCase()) && (fila!=i) ){
                swal(
                    {
                        title: 'El nombre de obra ingresado ya existe, no puede repetirlo!',
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                );               
                return;
            }
        }



        var ruta= urlApp + "agregarObra";
        var cont=0;
        var cadena='[';

        var tablaDistancias=document.getElementById('tabDistancias');

        for (var i = 1; i < tablaDistancias.rows.length; i++){
            if(tablaDistancias.rows[i].cells[1].getElementsByTagName('input')[0].value!=""){
                cadena+='{';
                cadena+='"idPlanta":"'+  tablaDistancias.rows[i].cells[1].getElementsByTagName('input')[0].dataset.idplanta + '", ';
                cadena+='"tiempoTraslado":"'+  tablaDistancias.rows[i].cells[1].getElementsByTagName('input')[0].value + '"';
                cadena+='}, ';                
            }
        }

        cadena=cadena.slice(0,-2);
        cadena+=']';
        
        var habilitar=0;

        if( document.getElementById('habilitada').checked ){
            habilitar=1;
        }
        $.ajax({
            url: ruta,
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    idObra: $("#idObra").val(),
                    nombre: $("#txtNombreObra").val(), 
                    emp_codigo: $("#idCliente").val(),
                    nombreContacto: $("#txtNombreContactoObra").val(),
                    correoContacto: $("#txtCorreoContactoObra").val(),
                    telefonoContacto: $("#txtTelefonoObra").val(),
                    descripcion: $("#txtDescripcionObra").val(),
                    habilitada: habilitar,
                    distanciaplantas: cadena
                  },
            success:function(dato){
                    var resp='No';
                    if(habilitar==1){
                        resp='Si';
                    }

                    var cadena='';
                    if($("#idObra").val()=="0"){
                        cadena="<button class='btn btn-xs btn btn-warning' onclick='editarObra(" + dato.idObra +  ", this.parentNode.parentNode )' title='Editar'><i class='fa fa-edit fa-lg'></i></button>";
                        cadena+="<button class='btn btn-xs btn btn-danger'  onclick='eliminarObra(" + dato.idObra +  ", this.parentNode.parentNode )' title='Eliminar'><i class='fa fa-trash-o fa-lg'></i></button>";                        
                        tabla.row.add( [
                                $("#txtNombreObra").val(),
                                $("#idCliente option:selected").html(),
                                $("#txtNombreContactoObra").val(),
                                resp,
                                cadena
                            ]).draw();
                    }else{
                        //obra editada
                        tabla.cell(fila,0).data( $("#txtNombreObra").val() );
                        tabla.cell(fila,1).data( $("#idCliente option:selected").html() );
                        tabla.cell(fila,2).data( $("#txtNombreContactoObra").val() );
                        tabla.cell(fila,3).data( resp );
                    }


                    cerrarNuevaObra();
            }
        })
        swal(
            {
                title: 'Se Actualizó La Obra',
                text: '',
                type: 'warning',
                showCancelButton: false,
                confirmButtonText: 'ok',
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
    }

    function eliminarObra(idObra, row){
        swal(
            {
                title: '¿Elimina la obra seleccionada?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm)
            {
                if(isConfirm){
                    $.ajax({
                        url: urlApp + "eliminarObra",
                        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                                idObra: idObra
                              },
                        success:function(dato){
                            if (dato[0].idObra==-1){
                                swal(
                                    {
                                        title: 'La obra no puede ser eliminada',
                                        text: 'Esta obra ya tiene información relacionada',
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonText: 'Si',
                                        cancelButtonText: '',
                                        closeOnConfirm: true,
                                        closeOnCancel: true
                                    });                               

                            }else{
                                var tabla=$("#tabla").DataTable();
                                tabla.row(row).remove().draw();
                                return;                                
                            }

                        }
                    }) 
                }
            }
        )  


        
    }    