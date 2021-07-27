  function abrirGuia(tipoGuia, numeroGuia, fila, nombreTabla){
    document.getElementById('mensajeProceso').style.display="none";
    $.ajax({
        url: urlApp + "datosGuiaDespacho",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { tipoGuia: tipoGuia ,
                numeroGuia: numeroGuia
              },
        success:function(dato){
            if(dato[0]){
              if(dato[0].folioDTE>0){
                $("#folioDTE").val(dato[0].folioDTE);
              }else{
                $("#folioDTE").val('');
              }
              document.getElementById('folioDTE').dataset.numeroguia=dato[0].numeroGuia;
              $("#numGuia").val(dato[0].numeroGuia);
              $("#txtNumeroPedido").val( dato[0].idPedido );
              document.getElementById('folioDTE').dataset.numeropedido=dato[0].idPedido;
              $("#guiaPatente").val( dato[0].patenteCamionDespacho);
              $("#guiaNombreConductor").val( dato[0].despachadoPor);              
              $("#sellos").val(dato[0].retiradoPor);
              $("#temperatura").val(dato[0].temperaturaCarga);
              $("#nombreEmpresaTransportes").val(dato[0].nombreTransportista);
              document.getElementById('observacionDespacho').value=dato[0].observaciones;
              if(dato[0].salida==1){
                $("#fechaHoraSalida").val(dato[0].fechaHoraSalida);
              }else{
                $("#fechaHoraSalida").val('');
              }

              $("#codigoVendedor").val(dato[0].codigoVendedor);
              $("#razonSocial").val(dato[0].nombreCliente);
              $("#rutCliente").val(dato[0].rutCliente);
              $("#nombreArchivo").val(dato[0].nombreArchivo);
              $("#obsProcesoDTE").val(dato[0].descripcionProcesoDTE);
              $("#direccionCliente").val(dato[0].direccionCliente);
              $("#comuna").val(dato[0].comunaCliente);
              $("#ciudad").val(dato[0].ciudadCliente);

              if (dato[0].lugarDeEntrega == ''){
                $("#guiaRampla").attr('disabled', true);
              }
              else{
                $("#guiaRampla").attr('disabled', false); 
              }
              $("#guiaRampla").val(dato[0].numeroRampla);

              if($("#comuna").val().trim()=='' || $("#ciudad").val().trim()==''){
                $("#mensajeProceso").html("<strong>¡Advertencia!</strong> La guía no puede ser procesada si falta comuna o ciudad");
                document.getElementById('mensajeProceso').style.display="block";
              }

              if(dato[0].folioDTE>0){
                document.getElementById('btnRegistrarSalida').style.display='none';
                document.getElementById('btnGuardarDatosGuia').style.display='none';

                if(document.getElementById('btnEmitirGuia').dataset.idperfil=='5' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='6' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                  if(dato[0].salida==0){
                    document.getElementById('btnRegistrarSalida').style.display='inline';
                  }
                }
                
                document.getElementById('btnEmitirGuia').style.display='none';
                document.getElementById('btnBajar').style.display='inline';
                document.getElementById('btnAnularGuiaTemporal').style.display='none';

                $("#observacionDespacho").attr('readonly', true);
                $("#guiaPatente").attr('readonly', true);
                $("#guiaRampla").attr('readonly', true);
                $("#guiaNombreConductor").attr('readonly', true);
                $("#sellos").attr('readonly', true);
                $("#nombreEmpresaTransportes").attr('readonly', true);
                $("#temperatura").attr('readonly', true);
                document.getElementById('btnEmitirGuia').onclick = function() { emitirGuiaDespacho(false); }                
              }else{

                document.getElementById('btnRegistrarSalida').style.display='none';
                if(document.getElementById('btnEmitirGuia').dataset.idperfil=='5' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='6' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                  document.getElementById('btnGuardarDatosGuia').style.display='inline';
                  document.getElementById('btnEmitirGuia').style.display='inline';
                  document.getElementById('btnAnularGuiaTemporal').style.display='inline';
                }else{
                  document.getElementById('btnGuardarDatosGuia').style.display='none';
                  document.getElementById('btnEmitirGuia').style.display='none';
                  document.getElementById('btnAnularGuiaTemporal').style.display='none';
                }
                document.getElementById('btnBajar').style.display='none';

                $("#observacionDespacho").attr('readonly', false);
                $("#guiaPatente").attr('readonly', true);
                $("#guiaRampla").attr('readonly', true);
                $("#guiaNombreConductor").attr('readonly', true);
                $("#sellos").attr('readonly', false);
                $("#nombreEmpresaTransportes").attr('readonly', true);
                $("#temperatura").attr('readonly', false);
                document.getElementById('btnEmitirGuia').onclick = function() { emitirGuiaDespacho(true); }     
              }

             
              if(dato[0].nombreArchivo==""){
                if(document.getElementById('btnEmitirGuia').dataset.idperfil=='1' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='5' ||
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='6' ||
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                  document.getElementById('btnSubirPdf').style.display='inline';
                }else{
                  document.getElementById('btnSubirPdf').style.display='none';
                }
              }else{
                document.getElementById('btnSubirPdf').style.display='none';
              }
            

              cargarDetalleGuia(numeroGuia, dato[0].folioDTE);               
            }
          }
    }) 
  }

  function cargarDetalleGuia(numeroGuia, folioDTE){
    limpiarTabla('tablaDetalleGuia');
    $.ajax({
        url: urlApp + "datosGuiaDespachoDetalle",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: {
                numeroGuia: numeroGuia
              },
        success:function(dato){
            for(var x=0;x<dato.length;x++){
                cadena="<tr>";
                cadena+="<td style='width:100px' data-prodcodigo='"+ dato[x].prod_codigo + "'>" + dato[x].codigoProductoSF + "</td>";
                cadena+="<td style='width:350px'>" + dato[x].descripcion + "</td>";
                cadena+="<td style='width:100px'>" + dato[x].unidad + "</td>";
                cadena+="<td style='width:100px; text-align:right;'>" + number_format(dato[x].cantidadSolicitada,0)+ "</td>";
                if(folioDTE>0   ){
                  cadena+="<td style='width:100px; text-align:right;'>" + number_format(dato[x].cantidadDespachada,2) + "</td>";
                }else{
                  if(document.getElementById('btnEmitirGuia').dataset.idperfil=='5' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='6' || 
                  document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                    cadena+="<td style='width:100px; text-align:right;'><input class='form-control input-sm' value='" + 
                    number_format(dato[x].cantidadDespachada,2) + "' maxlenght='7' onblur='totalLinea(this," + 
                    dato[x].precioUnitario + ");' onkeypress='return isNumberKey(event)' style='text-align:right;'></td>";                    
                  }else{
                    cadena+="<td style='width:100px; text-align:right;'>" + number_format(dato[x].cantidadDespachada,2) + "</td>";
                  }
                }    
                cadena+="<td style='width:100px; text-align:right;'>" + number_format(dato[x].precioUnitario,0) + "</td>";
                cadena+="<td style='width:100px; text-align:right;'>" + number_format(dato[x].total,0) + "</td>";
                cadena+="</tr>";
                $("#tablaDetalleGuia").append(cadena);               
            }
            $("#mdGuia").modal("show");
        }
    })    
  }


  function totalLinea(cantidad, precio){
    var fila=cantidad.parentNode.parentNode.rowIndex;
    var tabla=document.getElementById(`tablaDetalleGuia`);
    var cant=cantidad.value;
    tabla.rows[fila].cells[6].innerHTML= number_format((cant.replace(",",".")) * precio,0);
  }

  function cerrarCajaGuia(){
      $("#mdGuia").modal("hide");
  }

  function emitirGuiaDespacho(){
    document.getElementById('btnProcesarGuia').style.display="inline";
    document.getElementById('btnCerrarModEmitirGuia').innerText="No";
    document.getElementById('mensajeModEmitirGuia').style.display="inline";
    document.getElementById('imagenProcesando').style.display="none";

    var tabla=document.getElementById('tablaDetalleGuia');
    for (var i = 1; i < tabla.rows.length; i++){
        if(tabla.rows[i].cells[4].getElementsByTagName('input')[0]){
          cantidad=tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "").replace(",",".");
          if(cantidad=='' || parseFloat(cantidad)<=0){
            swal(
                {
                    title: '¡Debe completar las cantidades para poder emitir la guía!' ,
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
    }

    actualizarDatosGuiaDespacho(false);
    
    $("#modEmitirGuia").modal("show");
  }

  function cerrarModEmitirGuia(){
    $("#modEmitirGuia").modal("hide");
  }

  function procesarGuia(){
    
    document.getElementById('btnProcesarGuia').style.display="none";
    document.getElementById('btnCerrarModEmitirGuia').innerText="Cerrar";
    document.getElementById('mensajeModEmitirGuia').style.display="none";
    document.getElementById('imagenProcesando').style.display="inline";

    $.ajax({
        async: false,
        url: urlApp + "crearguiatxt",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: {
                numeroGuia: document.getElementById('folioDTE').dataset.numeroguia,
                idPedido: document.getElementById('folioDTE').dataset.numeropedido
              },                    
        success:function(dato){
          $("#folioDTE").val(dato.FolioDte);


          if(dato.Error.trim()!=''){
            $("#mensajeProceso").html("<strong>¡Advertencia!</strong> " + dato.Error);
            document.getElementById('mensajeProceso').style.display="block";
            cerrarModEmitirGuia();
          }
          if(dato.FolioDte.trim()!=''){

            if(document.getElementById('btnHistorico')){
              if(dato.despachoCompleto==1){

                document.getElementById("btnHistorico").style.display='none';
                document.getElementById("btnAsignarGuia").style.display='none';

              }
            }
            if(document.getElementById('btnAnularGuiaTemporal')){
              document.getElementById('btnAnularGuiaTemporal').style.display='none';
            }
            

            document.getElementById('btnGuardarDatosGuia').style.display='none';
            document.getElementById('btnEmitirGuia').style.display='none';
            document.getElementById('btnBajar').style.display='inline';
            document.getElementById('btnRegistrarSalida').style.display='inline';

            var tabla=document.getElementById('tablaDetalleGuia');
            for (var i = 1; i < tabla.rows.length; i++){
                if(tabla.rows[i].cells[4].getElementsByTagName('input')[0]){
                   tabla.rows[i].cells[4].innerHTML=tabla.rows[i].cells[4].getElementsByTagName('input')[0].value;
                }
            }
            cerrarModEmitirGuia();
            var url= urlApp + "bajarGuiaDespacho/" + dato.FolioDte +"/";
            window.open(url, "Ver PDF");

            // Se recorre el DataTable para modificar la funcion abrirGuia con el nuevo número ingresado por el usuario

            var numeroGuiaOrigen="abrirGuia(1, " + document.getElementById('folioDTE').dataset.numeroguia + ", this.parentNode.parentNode)";
            var nuevoNumeroGuia ="abrirGuia(1, " + $('#folioDTE').val() + ", this.parentNode.parentNode)";
            var table = $( tablaDetalle ).DataTable();
            var cadena = "";
            var filas=table.rows().count();

            for (var i = 0; i < filas; i++){
                cadena=table.cell(i,1).data();
                table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
            }               
          }

        },
        error: function(result) {
           cerrarModEmitirGuia();
           alert("Ha ocurrido un error y el documento no pudo ser procesado");
        }
    })
              
  }

  function actualizarDatosGuiaDespacho(mostrarMensaje){
      var tipo='1';

      var tabla=document.getElementById('tablaDetalleGuia');
      var cadena='[';
      var paso="";
      var cantidad="0";
      for (var i = 1; i < tabla.rows.length; i++){
        if(tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "")>parseInt(tabla.rows[i].cells[3].innerHTML.trim().replace(".", ""))){
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
        }
          if(tabla.rows[i].cells[4].getElementsByTagName('input')[0]){
              paso=tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "").replace(",",".");
              if(paso!='' && paso!="0" ){
                cantidad=paso;
              }else{
                cantidad="0";
              }
              cadena+='{';
              cadena+='"numeroGuia":"'+ document.getElementById('folioDTE').dataset.numeroguia + '", ';
              cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].dataset.prodcodigo  + '", ';
              cadena+='"unidad":"'+  tabla.rows[i].cells[2].innerHTML.trim()  + '", ';
              cadena+='"cantidad":"'+  cantidad  + '"';
              cadena+='}, ';

          }
      }
      cadena=cadena.slice(0,-2);
      cadena+=']';

      if(cadena==']'){
        return;
      }
      var numRampla='0';
      if($("#guiaRampla").val().trim()==''){
        numRampla="0"
      }else{
        numRampla=$("#guiaRampla").val();
      }
      $.ajax({
          async: false,
          url: urlApp + "actualizarDatosGuiaDespacho",
          headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
          type: 'POST',
          dataType: 'json',
          data: { tipoGuia: tipo ,
                  numeroGuia: document.getElementById('folioDTE').dataset.numeroguia,
                  sellos: $("#sellos").val(),
                  temperaturaCarga: $("#temperatura").val(),
                  patente: $("#guiaPatente").val(),
                  nombreConductor: $("#guiaNombreConductor").val(),
                  observaciones: $("#observacionDespacho").val(),
                  numeroGuiaOrigen: document.getElementById('folioDTE').dataset.numeroguia,
                  nombreEmpresaTransportes: $("#nombreEmpresaTransportes").val(),
                  numeroRampla: numRampla,
                  detalle: cadena
                },                
          success:function(dato){
     
                  if(mostrarMensaje){
                    swal(
                        {
                            title: 'Se han actualizado los datos!' ,
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

                                cerrarCajaGuia();
                                return;                       
                            }
                        }
                    )                    
                  }    
                 
          }
      })    
  } 

  function bajarGuiaPdf(){
      var url= urlApp + "bajarGuiaDespacho/" + $("#folioDTE").val() +"/";
      window.open(url, "Ver PDF");                       
  }

  function abrirSubirGuiaPdf(){
    //$("#numGuia").val( $("#numeroGuia").val() );

    $("#nuevoFolioDTE").val('');
    document.getElementById('mensajeUpload').dataset.title="";
    $("#upload-demo").val('');
    $("#modSubirGuiaPdf").modal('show');
  }

  function cerrarModalSubirGuiaPdf(){
    $("#modSubirGuiaPdf").modal('hide');
  }


  function registrarSalida(){
    var numero=$("#folioDTE").val();
    swal(
        {
            title: "¿Desea confirmar la salida del pedido con guía Nº " + numero + " ?" ,
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm)
        {
            if(isConfirm){
                $.ajax({
                    url: urlApp + "registrarSalidaDespacho",
                    headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                    type: 'POST',
                    dataType: 'json',
                    data: { tipoGuia: 1,
                            numeroGuia: numero
                          },
                    success:function(dato){
                      document.getElementById('btnRegistrarSalida').style.display='none';
                      cerrarCajaGuia();
                      swal(
                        {
                            title: 'Se Registro la Salida!' ,
                            text: '',
                            type: 'success',
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
                    )  
                    }
                })                
                return;                        
            }

        }
    )              
  }

  function eliminarGuia(){
    $("#modEliminarGuia").modal('show');
    $("#motivoEliminacionGuia").val('');
  }

  function cerrarEliminarGuia(){
    $("#modEliminarGuia").modal('hide');
    $("#motivoEliminacionGuia").val('');    
  }

  function abrirModalAnularGuia(){
    var folioGuia=document.getElementById('folioDTE').value.trim();
    var numeroGuia=document.getElementById('folioDTE').dataset.numeroguia;

    if(folioGuia!=''){
      swal(
          {
              title: "Guía no puede ser anulada" ,
              text: 'La guía ya fue emitida y se le ha asignado un número definitivo',
              type: 'warning',
              showCancelButton: false,
              confirmButtonText: 'Entendido',
              cancelButtonText: '',
              closeOnConfirm: true,
              closeOnCancel: false
          }          
        ) 
        return;     
    }

    $("#obsAnulacion").val('');
    $("#mdAnularGuiaTemporal").modal('show');
  }

  function cerrarAnularGuia(){
    $("#obsAnulacion").val('');
    $("#mdAnularGuiaTemporal").modal('hide');
  }

  function anularGuiaTemporal(){
    if(obsAnulacion.value.trim()==''){
      swal(
          {
              title: "¡Debe ingresar el motivo!" ,
              text: '',
              type: 'warning',
              showCancelButton: false,
              confirmButtonText: 'Ok',
              cancelButtonText: '',
              closeOnConfirm: true,
              closeOnCancel: false
          }
        ) 
        return;        
    }

    $.ajax({
        url: urlApp + "eliminarGuiaDespacho",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { 
                numeroGuia: document.getElementById('folioDTE').dataset.numeroguia,
                motivo: obsAnulacion.value
              },
        success:function(dato){
            swal(
                 {
                    title: "¡Proceso realizado con éxito!" ,
                    text: 'A continuación se actualizará la pantalla anterior con los cambios realizados',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Entendido',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm)
                {
                    if(isConfirm){
                      location.reload(true);
                    }
                }
            );                 
            //  cerrarAnularGuia();
            //  cerrarCajaGuia();
        }
      }
    );
                    
    
  }

