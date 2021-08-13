    var arrCodigosSoftland=new Array();
    var cmtten=0;
    var cmttem1=0;
    var cmttem2=0;
    var valorFleteFalso;
    var tiempoProduccion_val =new Array();
    var arrFeriados =new Array();
    var mensajeFlete=1;

    $(document).ready(function() {
        // Datepicker      
        $('.date').datepicker({
            todayHighlight: true,
            format: "dd/mm/yyyy",
            weekStart: 1,
            language: "es",
            autoclose: true,
            startDate: '+0d'
        }) 

        document.getElementById('tipoCarga').selectedIndex=-1;
        document.getElementById('tipoTransporte').selectedIndex=-1;
        $("#cantidadFleteFalso").val("0");
        $("#valorFleteFalso").val("0");
        $("#mensajeFlete").val("1");
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

        $.ajax({
            async:false, 
            url: urlApp + 'obtenerParametros',
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: {},
            success:function(dato){
                if(dato.length>0){
                    cmtten=dato[0].carga_max_granel_tte_normal;
                    cmttem1=dato[0].carga_max_granel_tte_mixto_1;
                    cmttem2=dato[0].carga_max_granel_tte_mixto_2;
                    valorFleteFalso=dato[0].valorFleteFalso;
                }
            }
        }); 

        var tabla=document.getElementById('tablaDetallePedidoGranel'); 
        for(var x=1; x<tabla.rows.length; x++){
            tabla.rows[x].style.display='none';
            codConta='';
            for(var y=0;y<arrCodigosSoftland.length;y++){
                if(arrCodigosSoftland[y][0] == tabla.rows[x].cells[1].innerHTML.trim() && arrCodigosSoftland[y][1] == tabla.rows[x].cells[5].innerHTML.trim() ){
                    codConta=arrCodigosSoftland[y][2];
                }
            }
            if(codConta==''){
                notificacion="<div class='alert alert-danger'><strong>Advertencia</strong> ";
                notificacion=notificacion + "El producto " + tabla.rows[x].cells[1].innerHTML.trim()  + " / " + tabla.rows[x].cells[5].innerHTML.trim() + 
                    " no está codificado en Softland, debe solucionar esto para realizar pedidos para esta nota de venta.";
                notificacion=notificacion + "</div>";    

                $("#notificaciones").append(notificacion);
                $("#btnCrearPedido").attr("disabled", true);
            }           
        }           
                
    });
    

    function verificarCantidad(valor){
        if ($("#tipoCarga").val()=='1'){
            var tabla=document.getElementById('tablaDetallePedidoGranel'); 
        }else{
            var tabla=document.getElementById('tablaDetallePedidoNormal'); 
        } 
        var fila=valor.parentNode.parentNode.rowIndex;
        
        if( valor.value!=''){
            if( parseInt(tabla.rows[fila].cells[7].innerHTML) < parseInt(valor.value) ){
                swal(
                    {
                        title: 'Corrija el valor ingresado, es mayor al Saldo.' ,
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
                            valor.value='';                           
                        }
                    }
                )                 
            }
        }

    }

    function selTipoCarga(){
        if ($("#tipoCarga").val()=='1'){
            var lista=document.getElementById('listaProductos');
            if( lista.length<=1 ){
                swal(
                    {
                        title: 'Este pedido no tiene productos con formato GRANEL' ,
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
                )
                return;                  
            }else{
                document.getElementById('tipoTransporte').selectedIndex=-1;
                document.getElementById("opciones").style.display = "block";

            }
          
        }else{
            var tabla=document.getElementById('tablaDetallePedidoNormal');
            if(tabla.rows.length>1){            
                document.getElementById('tipoTransporte').selectedIndex=0;
                document.getElementById("divPedidoProductosaGranel").style.display="none";
                document.getElementById("divPedidoProductosporUnidad").style.display="block";
                document.getElementById("divPiePedido").style.display="block";
                document.getElementById("opciones").style.display = "none";
            }else{
                swal(
                    {
                        title: 'Este pedido no tiene productos con formato OTROS' ,
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
                            document.getElementById('tipoCarga').selectedIndex=-1;
                            return;                         
                        }
                    }
                )
                return;                  
            }                           
        }
    }

    function selTipoTransporte(){
        var linea1="<span style='cursor: pointer;' onclick='vernota()' style='color:#2980B9'><span class='glyphicon glyphicon-info-sign' style='font-size:20px;color:#2980B9'></span> <b><u>Consideraciones para pedidos a granel</u></b></span>";
        var linea2="<br>Debe agregar 2 productos para este pedido(transporte en camión mixto sujeto a disponibilidad).";
        if ($("#tipoTransporte").val()=='1'){
            $("#notaMaxProducto").html(linea1)
        }else{
            $("#notaMaxProducto").html(linea1+linea2);
        }
        document.getElementById("divPedidoProductosaGranel").style.display="block";
        document.getElementById("divPedidoProductosporUnidad").style.display="none";
        document.getElementById("divPiePedido").style.display="block";
        var tabla=document.getElementById('tablaDetallePedidoGranel'); 
        for(var x=1; x<tabla.rows.length; x++){
            tabla.rows[x].cells[7].getElementsByTagName('input')[0].value='';
            tabla.rows[x].style.display='none';
        }  
    }

    function vernota(){
        $("#modNotasGranel").modal('show');
    }

    function nuevaObra(){
        $("#txtDescripcionObra").val( $("#txtObra").val() );
        $("#txtCliente").val( $("#txtNombreCliente").val() );
        $("#txtNombreContacto").val('');
        $("#txtCorreoContactoObra").val('');
        $("#txtTelefonoObra").val('');
        $("#mdNuevaObra").modal("show"); 
        $("#txtNombreObra").focus();
    }

    function cerrarNuevaObra(){
        $("#mdNuevaObra").modal("hide");
    }


    function listarObras(){
        $("#idObra").empty();
        var ruta="listarObras";
        $.ajax({
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

        var ruta="datosObra";
        $.ajax({
            url: ruta,
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    idObra: $("#idObra").val()
                  },
            success:function(dato){

                    $("#txtNombreContacto").val( dato[0].nombreContacto );
                    $("#txtCorreoContacto").val( dato[0].correoContacto );
                    $("#txtTelefono").val( dato[0].telefonoContacto );

            }
        })        
    }  
    
    function agregarProducto(){

        if(document.getElementById('tipoTransporte').selectedIndex<0){
            swal(
                {
                    title: 'Primero debe indicar el Tipo de Transporte!!',
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


        var tabla=document.getElementById('tablaDetallePedidoGranel'); 
        var cont=0;
        var maximo=1;

        if($("#tipoTransporte").val()=='1'){
            maximo=1;
        }else{
            maximo=2;
        }

        for(var x=1; x<tabla.rows.length; x++){
            if(tabla.rows[x].style.display==''){
                cont++;
            }
        }

        if(cont==maximo){
            if(maximo==1){
                swal(
                    {
                        title: 'Solo puede solicitar un producto en un pedido a granel con transporte Normal',
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
            }else{
                swal(
                    {
                        title: 'Solo puede solicitar dos productos en un pedido a granel con transporte Mixto',
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
            }

            return;
        }

        for(var x=1; x<tabla.rows.length; x++){
            if(tabla.rows[x].cells[0].innerHTML==$("#listaProductos").val()){
                tabla.rows[x].style.display='';
                break;
            }
        }
        $.ajax({
            url: urlApp + "traerPlantaNotaVenta",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    idNotaVenta: $("#txtNumeroNotaVenta").val(),
                    idProducto: $("#listaProductos").val()
                  },
            success:function(dato){
                $("#selectPlanta option[value='"+ dato[0].idPlanta +"']").attr("selected",true);

            }
        });
    }

    function ocultarFila(fila){
        var tabla=document.getElementById('tablaDetallePedidoGranel');
        tabla.rows[fila].cells[7].getElementsByTagName('input')[0].value='';
        tabla.rows[fila].style.display='none';
    }


    function crearPedido(Origen){
        var tabla=document.getElementById('tablaDetallePedidoGranel');
        if($("#idCliente1").val() == 14){
            if($("#tipoCarga").val() == 1){
                var dato = "";
                var dato9 = "";
                if($("#tipoTransporte").val() == 1){
                        for (var i = 1; i < tabla.rows.length; i++){
                            if(dato == ""){
                                
                              if(tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim() !=  ""){
                                  dato = tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim();
                                  dato9 = tabla.rows[i].cells[9].getElementsByTagName('select')[0].value;
                              }
                              
                            }
                        }
                        
                            if(dato != $("#cmgttn").val()){
                                swal(
                                 {
                                     title: 'no puede crear pedidos con carga mayor o menor a '+$("#cmgttn").val()+' toneladas',
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
                         

                        
                       
                    
                }else{
                   
                        var dato = "";
                        var dato1 = "";
    
                        for (var i = 1; i < tabla.rows.length; i++){
                          if(dato == ""){
                              
                            if(tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim() !=  ""){
                                dato = tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim();
                                dato9 = tabla.rows[i].cells[9].getElementsByTagName('select')[0].value;

                            }
                            
                          }
                          if(dato != ""){
                            if(tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim() !=  ""){
                                dato1 = tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim();
                            }
                        }
                      
                        }
                   
                        if(dato != $("#cmgttm1").val()){
                            swal(
                             {
                                 title: 'El primer producto seleccionado debe ser por una cantidad de '+$("#cmgttm1").val()+' toneladas',
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
                         if((parseFloat(dato1)) != $("#cmgttm2").val()){
                            swal(
                             {
                                 title: 'El segundo producto seleccionado debe ser por una cantidad de '+$("#cmgttm2").val()+' toneladas',
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
    
                        
        
                }

            }
            
    

        }
        
        var verificadorFeriado;
        $.ajax({
            async:false,
            url: urlApp + "verificarFeriado",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                fecha: $("#txtFechaEntrega").val()
            },
            success:function(dato){
             verificadorFeriado=dato[0].verificador;
            }
        });

        if(verificadorFeriado==1){
            swal(
                {
                    title: 'No puede hacer un pedido en un dia feriado' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }
            )
            $("#btnCrearPedido").attr("disabled", false);
            return;
        }

        $("#btnCrearPedido").attr("disabled", true);
      
       
       
      
        if($("#txtFechaEntrega").val().trim()=='' ){
            swal(
                {
                    title: 'Debe ingresar la Fecha de Entrega (*).',
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
                        $("#btnCrearPedido").attr("disabled", false);
                        return;
                        
                    }
                }
            )
            $("#btnCrearPedido").attr("disabled", false);
            return
        }       


        if ($("#tipoCarga").val()=='1'){
            var tabla=document.getElementById('tablaDetallePedidoGranel');             
        }else{
            var tabla=document.getElementById('tablaDetallePedidoNormal'); 
        }

        var verificadorFlete = 1;
        var verificarRetiro = 1;
        var fletePlanta;
        
        for (var i = 1; i < tabla.rows.length; i++){
            fletePlanta = tabla.rows[i].cells[8].getElementsByTagName('select')[0].value;
            if(tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim()!="" ){
                $.ajax({
                    async:false,
                    url: urlApp + "verificarFlete",
                    headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                        idNotaVenta: $("#txtNumeroNotaVenta").val(),
                        planta: fletePlanta
                    },
                    success:function(dato){                 
                        verificadorFlete = dato[0].verificador;
        
                    }
                });
            }
        }

        for (var i = 1; i < tabla.rows.length; i++){
            if(verificarRetiro==1){         
              if(tabla.rows[i].cells[9].getElementsByTagName('select')[0].value == "2" && tabla.rows[i].style.display !='none' ){
                verificarRetiro = 0;
                
              }
              
            }
        }
        if(verificarRetiro==1){
            if(verificadorFlete==0){
                swal(
                    {
                        title: 'No se puede ingresar pedido, jefe de transporte debe ingresar la información de flete',
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
                            $("#btnCrearPedido").attr("disabled", false);
                            return;
                            
                        }
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;
            }
        }
        
        var ind1=0;
        var ind2=0;
        var opc1="";
        var opc2="";
        var retiraCliente=false;
        var incluyeFleteFalso="0";
        var cantFleteFalso="0";
        var valFleteFalso="0";
        
        for(var x=1; x<tabla.rows.length; x++){
           if(tabla.rows[x].cells[9].getElementsByTagName('select')[0].value=='2'){
                retiraCliente=true;
                break;
           }
        }        

        if (  ($("#tipoCarga").val()=='1' && $("#tipoTransporte").val()=='2') || $("#tipoCarga").val()=='2'){

            var cont1=0;
            var plantaSel=0;
            var cont2=0;
            var entregaSel=0;

            var mensaje='';

            if($("#tipoCarga").val()=='2'){
                mensaje='El pedido OTROS, debe tener misma Planta de origen y Forma de entrega para todos productos!!(*).';
            }else{
                mensaje='El pedido Granel/Mixto, debe tener misma Planta de origen y Forma de entrega para ambos productos!!(*).';
            }

            for(var x=1; x<tabla.rows.length; x++){
                if(tabla.rows[x].style.display!='none'){
                    if(tabla.rows[x].cells[7].getElementsByTagName('input')[0].value.trim()!="" ){
                        if(plantaSel!=tabla.rows[x].cells[8].getElementsByTagName('select')[0].value ){
                            plantaSel=tabla.rows[x].cells[8].getElementsByTagName('select')[0].value;
                            cont1++;
                        }
                        if(entregaSel!=tabla.rows[x].cells[9].getElementsByTagName('select')[0].value){
                            entregaSel=tabla.rows[x].cells[9].getElementsByTagName('select')[0].value;
                            cont2++;
                        }
                    }
                }
            }

            if(cont1>1 || cont2>1 ){
                swal(
                    {
                        title: mensaje,
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
                            $("#btnCrearPedido").attr("disabled", false);
                            return;
                            
                        }
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return
            }
        }


        var cont=0;
        var total=0;
        var toneladas=0;
        var productos=0;
        var productosSolicitados=0;
        var noPuedePonerCero = 0;
        //var lineasDetalle=0;
        for(var x=1; x<tabla.rows.length; x++){
            if(tabla.rows[x].style.display!='none'){
                //lineasDetalle++;
                if(tabla.rows[x].cells[7].getElementsByTagName('input')[0].value.trim()=="" ||  tabla.rows[x].cells[7].getElementsByTagName('input')[0].value.trim()=="0"  ){
                    cont++;
                }else{
                    productosSolicitados++;
                    total+= ( parseInt(tabla.rows[x].cells[3].innerHTML.replace('.','').replace('.','')) * parseInt( tabla.rows[x].cells[7].getElementsByTagName('input')[0].value ) );
                    if(tabla.rows[x].cells[5].innerHTML.trim()=='tonelada'){
                        if ($("#tipoCarga").val()=='1'){
                            toneladas+=parseInt( tabla.rows[x].cells[7].getElementsByTagName('input')[0].value )
                        }

                        productos++;
                    }

                }
            }
        }

        for(var x=1; x<tabla.rows.length; x++){
               
                if(tabla.rows[x].cells[7].getElementsByTagName('input')[0].value.trim()=="0"  ){
                    noPuedePonerCero++;     
                    console.log(noPuedePonerCero);
                }      
            
        }


        if($("#tipoCarga").val()=="1"){
            if(cont>0){
                swal(
                    {
                        title: 'No ha ingresado una cantidad para generar el pedido' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;             
            }            
        }else{
            if(productosSolicitados==0){
                swal(
                    {
                        title: 'No ha ingresado una cantidad para generar el pedido' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;             
            } else if(noPuedePonerCero>0){
                swal(
                    {
                        title: 'No puede poner 0 en la cantidad si no desea el producto deje la caja vacia' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;  
            }
        }

        if(document.getElementById('horario').selectedIndex<0){
            swal(
                {
                    title: 'No ha seleccionado el horario de carga' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: false
                }
            )
            $("#btnCrearPedido").attr("disabled", false);
            return;             
        }


        //Si tipo de carga es A Granel
        if( $("#tipoCarga").val()=='1' ){

            if( productos!=1 &&  $("#tipoTransporte").val()=='1' ){
                swal(
                    {
                        title: 'Transporte Normal debe contener en el detalle UN producto!!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;                 
            }

            if( productos!=2 &&  $("#tipoTransporte").val()=='2' ){
                swal(
                    {
                        title: 'Transporte Mixto debe contener en el detalle DOS productos!!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    }
                )
                $("#btnCrearPedido").attr("disabled", false);
                return;                 
            }            
        }


            //Verificacion de carga máxima para pedidos tipo transporte NORMAL

            if(valorFleteFalso>0){
                if($("#valorFleteFalso").val().trim()!=''){
                    valFleteFalso=parseInt($("#valorFleteFalso").val());
                    cantFleteFalso=parseInt($("#cantidadFleteFalso").val());
                }
            }else{
                valFleteFalso=0;
                cantFleteFalso=0;
            }        

            //Verificacion de carga máxima para pedidos tipo transporte NORMAL
            if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='1'){
                if( (toneladas+cantFleteFalso) > cmtten){
                    swal(
                        {
                            title: 'La cantidad de toneladas excede el máximo permitido por pedido (máx. ' + cmtten +')!!' ,
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: '',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        }
                    )
                    $("#btnCrearPedido").attr("disabled", false);
                    return;                 
                }
            }

            if(mensajeFlete>0){
                if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='1'){                  
                    if( (toneladas) < cmtten){
                        swal(
                            {
                                title: 'Advertencia está creando un pedido con una carga menor a la máxima(' + (cmtten) +' toneladas)',
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'ENTENDIDO',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            }
                        )
                        mensajeFlete=0;
                        $("#btnCrearPedido").attr("disabled", false);
                        return;                 
                    }
                }if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='2'){
                    if( (toneladas) < (cmttem1+cmttem2) ){
                        swal(
                            {
                                title: 'Advertencia está creando un pedido con una carga menor a la máxima (' + cmttem1+" y "+cmttem2 +' toneladas)',
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            }
                        )
                        mensajeFlete=0;
                        $("#btnCrearPedido").attr("disabled", false);
                        return;                 
                    }
                }      

            }

            if(valorFleteFalso>0){

                if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='1'){
                    if( (toneladas+cantFleteFalso) < cmtten){
                        if( document.getElementById('idCliente').dataset.idperfil=="1"){
                            mensaje="La cantidad NO PUEDE SER MENOR a la carga máxima";
                        }else{
                            mensaje="La cantidad es menor a la carga máxima, ¿Desea aplicar flete falso?";
                        }
                        
                        swal(
                            {
                                title: mensaje,
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
                                    if(document.getElementById('idCliente').dataset.idperfil=="1"){
                                        $("#btnCrearPedido").attr("disabled", false);
                                        return;
                                    }else{
                                        incluyeFleteFalso="1";
                                        cantidadFleteFalso=cmtten-toneladas;
                                        $("#valorFleteFalso").val(valorFleteFalso);
                                        $("#cantidadFleteFalso").val(cantidadFleteFalso);
                                        $("#totalFleteFalso").val(cantidadFleteFalso*valorFleteFalso);
                                        document.getElementById('divFleteFalso').style.display='block';
                                        $("#btnCrearPedido").attr("disabled", false);
                                    }
                                    return;
                                }
                            }                    
                        )
           
                    }
                } 

                total+=(cantFleteFalso*valFleteFalso);       

            }
         
            //Verificacion de carga máxima para pedidos tipo transporte MIXTO
            if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='2'){
                if( (toneladas+cantFleteFalso) > (cmttem1+cmttem2) ){
                    swal(
                        {
                            title: 'La cantidad total de toneladas excede el máximo permitido por pedido (máx. ' + cmtten +')!!' ,
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: '',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        }
                    )
                    $("#btnCrearPedido").attr("disabled", false);
                    return;                 
                }
            }

            if(valorFleteFalso>0){
                //Verificacion de carga máxima para pedidos tipo transporte MIXTOS
                if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=='2'){

                    if( (toneladas+cantFleteFalso) < (cmttem1+cmttem2) ){
                        if(document.getElementById('idCliente').dataset.idperfil=="1"){
                            mensaje='La cantidad NO PUEDE SER MENOR a la carga máxima (' + (cmttem1+cmttem2) +' toneladas)';
                        }else{
                            mensaje="La cantidad total es menor a la carga máxima, ¿Desea aplicar flete falso?";
                        }                
                        swal(
                            {
                                title: mensaje ,
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
                                    if(document.getElementById('idCliente').dataset.idperfil=="1"){
                                        $("#btnCrearPedido").attr("disabled", false);
                                        return;
                                    }else{
                                        incluyeFleteFalso="1";
                                        cantidadFleteFalso=cmtten-toneladas;
                                        $("#valorFleteFalso").val(valorFleteFalso);
                                        $("#cantidadFleteFalso").val(cantidadFleteFalso);
                                        $("#totalFleteFalso").val(cantidadFleteFalso*valorFleteFalso);
                                        document.getElementById('divFleteFalso').style.display='block';
                                        $("#btnCrearPedido").attr("disabled", false);
                                    }
                                    return;
                                }else{
                                    $("#btnCrearPedido").attr("disabled", false);
                                    return;                            
                                }
                            }                    
                        )
                        return;               
                    }
                }
            }
        


        var cont=0;
        var cadena='[';

        for (var i = 1; i < tabla.rows.length; i++){
            if(tabla.rows[i].cells[7].getElementsByTagName('input')[0].value!=""){
                cadena+='{';
                cadena+='"idNotaVenta":"'+  $("#txtNumeroNotaVenta").val()  + '", ';
                cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML  + '", ';
                cadena+='"u_codigo":"'+  tabla.rows[i].cells[5].innerHTML  + '", ';
                cadena+='"cantidad":"'+  tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.replace(",", ".") + '", ';
                cadena+='"precio":"'+  tabla.rows[i].cells[3].innerHTML.replace('.','').replace('.','')   + '", ';

                if($("#tipoCarga").val()=='1'){
                    cadena+='"idPlanta":"'+  tabla.rows[i].cells[8].getElementsByTagName('select')[0].value  + '",';
                    cadena+='"idFormaEntrega":"'+  tabla.rows[i].cells[9].getElementsByTagName('select')[0].value  + '"';   
                }else{
                    cadena+='"idPlanta":"'+  tabla.rows[i].cells[8].getElementsByTagName('select')[0].value  + '",';
                    cadena+='"idFormaEntrega":"'+  tabla.rows[i].cells[9].getElementsByTagName('select')[0].value  + '"';                    
                }
                cadena+='}, ';                
            }
        }
        cadena=cadena.slice(0,-2);
        cadena=cadena+="]";
        console.log(cadena);

        var noExceder=0;

        if(document.getElementById('noExcederCantidad').checked){
            noExceder=1;
        }

        //Validar la Fecha Entrega
        var fechaCreacionPedido = new Date();
        //console.log("fecha creacion al tiro: ", fechaCreacionPedido);
        var fechaEntrega = $("#txtFechaEntrega").val();
        var fechaEntregaMaxima;
        if ($("#horario option:selected").html() == "AM"){
            fechaEntregaMaxima = new Date(fechaEntrega.split('/')[2], fechaEntrega.split('/')[1]-1, fechaEntrega.split('/')[0], 11, 59, 0, 0);
            console.log(fechaEntregaMaxima);
        }
        else{
            fechaEntregaMaxima = new Date(fechaEntrega.split('/')[2], fechaEntrega.split('/')[1]-1, fechaEntrega.split('/')[0], 23, 59, 0, 0);   
            console.log(fechaEntregaMaxima);
        }
        //console.log("Fecha Creacion Pedido: ", fechaCreacionPedido);
        //console.log("Fecha Entrega Maximo: ", fechaEntregaMaxima);
        var listTiempoPlanta = [];
        for (var j=1; j<tabla.rows.length; j++){
            $.ajax({
                async:false,
                url: urlApp + "buscarTiempoProduccion",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        nombreProducto: tabla.rows[j].cells[1].innerHTML.trim(),
                        idPlanta: tabla.rows[j].cells[8].getElementsByTagName('select')[0].value,
                        nombre: tabla.rows[j].cells[5].innerHTML.trim()
                },
                success:function(dato){
                    console.log(dato);
                    if (dato.length > 0){
                        if (dato[0].tiempoProduccion == null)
                        { 
                            dato[0].tiempoProduccion = 0;  
                            
                        }
                        tiempoProduccion_val.push(dato[0].tiempoProduccion);
                        listTiempoPlanta.push(dato);
                    }
                }
            });
        }
        fechaCreacionPedido.setHours(fechaCreacionPedido.getHours() + Math.max.apply(Math, tiempoProduccion_val));
        //console.log("fecha que lleva despues del tiempo de produccion: ", fechaCreacionPedido);
        var mayorTiempoP = Math.max.apply(Math, tiempoProduccion_val);
        var auxTiempo = 0;
        var id_planta_val;
        for(var i=0; i<listTiempoPlanta.length; i++){
            if (listTiempoPlanta[i][0].tiempoProduccion > auxTiempo){
                auxTiempo = listTiempoPlanta[i][0].tiempoProduccion;
                id_planta_val = listTiempoPlanta[i][0].idPlanta;
            }
        }
        $.ajax({
            async:false,
            url: urlApp + "buscarFeriados",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                ano: fechaCreacionPedido.getFullYear()
            },
            success:function(dato){
                arrFeriados = dato;

            }
        });
       
        
        //console.log("Feriados: ", arrFeriados);
        if (fechaCreacionPedido.getHours() >= 17 && fechaCreacionPedido.getMinutes() >= 0){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);    
        }
        //console.log("fecha que lleva despues del primer: ", fechaCreacionPedido);
        var weekday = ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
        if (weekday[fechaCreacionPedido.getDay()] == "Sabado"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 2);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del segundo: ", fechaCreacionPedido);
        if (weekday[fechaCreacionPedido.getDay()] == "Domingo"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del tercero: ", fechaCreacionPedido);
        var feriado_val;
        $.each(arrFeriados, function (i, item) {
            feriado_val = new Date(item.fecha.split('/')[2], item.fecha.split('/')[1]-1, item.fecha.split('/')[0], 0, 0, 0, 0);
            if (fechaCreacionPedido.getFullYear() == feriado_val.getFullYear() 
                && fechaCreacionPedido.getMonth() == feriado_val.getMonth()
                && fechaCreacionPedido.getDate() == feriado_val.getDate())
            {
                fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
                fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);    
            }
        });
        //console.log("fecha que lleva despues de los feriados: ", fechaCreacionPedido);
        if (weekday[fechaCreacionPedido.getDay()] == "Sabado"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 2);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del cuarto: ", fechaCreacionPedido);
        if (weekday[fechaCreacionPedido.getDay()] == "Domingo"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del quinto: ", fechaCreacionPedido);
        $.each(arrFeriados, function (i, item) {
            feriado_val = new Date(item.fecha.split('/')[2], item.fecha.split('/')[1]-1, item.fecha.split('/')[0], 0, 0, 0, 0);
            if (fechaCreacionPedido.getFullYear() == feriado_val.getFullYear() 
                && fechaCreacionPedido.getMonth() == feriado_val.getMonth()
                && fechaCreacionPedido.getDate() == feriado_val.getDate())
            {
                fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
                fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);    
            }
        });
        //console.log("fecha que lleva despues del feridos 2: ", fechaCreacionPedido);
        if (weekday[fechaCreacionPedido.getDay()] == "Sabado"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 2);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del sexto: ", fechaCreacionPedido);
        if (weekday[fechaCreacionPedido.getDay()] == "Domingo"){
            fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
            fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);
        }
        //console.log("fecha que lleva despues del septirmo: ", fechaCreacionPedido);
        $.each(arrFeriados, function (i, item) {
            feriado_val = new Date(item.fecha.split('/')[2], item.fecha.split('/')[1]-1, item.fecha.split('/')[0], 0, 0, 0, 0);
            if (fechaCreacionPedido.getFullYear() == feriado_val.getFullYear() 
                && fechaCreacionPedido.getMonth() == feriado_val.getMonth()
                && fechaCreacionPedido.getDate() == feriado_val.getDate())
            {
                fechaCreacionPedido.setDate(fechaCreacionPedido.getDate() + 1);
                fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), 8, 0, 0, 0);    
            }
        });
        //console.log("fecha que lleva despues del feriados 3: ", fechaCreacionPedido);
        fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), fechaCreacionPedido.getHours(), 0, 0, 0);    
        //console.log("fecha que lleva antes del traslado: ", fechaCreacionPedido);
        $.ajax({
            async:false,
            url: urlApp + "buscarTiempoTraslado",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                notaVenta: $("#txtNumeroNotaVenta").val(),
                idPlanta: id_planta_val
            },
            success:function(dato){
                if (dato.length > 0){
                    if (dato[0].tiempoTraslado == null)
                    { 
                        dato[0].tiempoTraslado = 0;  
                    }
                    fechaCreacionPedido.setHours(fechaCreacionPedido.getHours() + dato[0].tiempoTraslado);
                    fechaCreacionPedido = new Date(fechaCreacionPedido.getFullYear(), fechaCreacionPedido.getMonth(), fechaCreacionPedido.getDate(), fechaCreacionPedido.getHours(), 0, 0, 0);
                }
            }
        });
        var atrasado = 0;
        var grabar = true;
        if (new Date(fechaCreacionPedido) >= new Date(fechaEntregaMaxima)){
            swal(
                {
                    title: 'Está creando un pedido fuera de plazo, ¿Desea continuar con la creación del pedido?',
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
                    if (isConfirm){
                        atrasado = 1;
                        grabarPedido(total, cadena, noExceder, incluyeFleteFalso, cantFleteFalso, valFleteFalso, Origen, atrasado);
                    }
                });
                $("#btnCrearPedido").attr("disabled", false);
        }
        else{
            grabarPedido(total, cadena, noExceder, incluyeFleteFalso, cantFleteFalso, valFleteFalso, Origen, atrasado);
        }
        //Fin Validar la Fecha Entrega
    }

    function grabarPedido(total, cadena, noExceder, incluyeFleteFalso, cantFleteFalso, valFleteFalso, Origen, Atrasado){
        var formData = new FormData( $("#datos")[0] );

        formData.append("idNotaVenta", $("#txtNumeroNotaVenta").val() );
        formData.append("fechaEntrega", fechaAtexto( $("#txtFechaEntrega").val() ) );
        formData.append("observaciones", $("#txtObservaciones").val() );
        formData.append("idPlanta", $("#idPlanta").val() );
        formData.append("idFormaEntrega", $("#idTransaccion").val() );
        formData.append("horarioEntrega", $("#horario option:selected").html() );
        if(Origen=='QL'){
            formData.append("idEstadoPedido", '1');
        }else{
            formData.append("idEstadoPedido", '-1');
        }
        
        formData.append("usu_codigo_estado", $("#idUsuarioSession").val() );
        formData.append("totalNeto", total);
        formData.append("contacto", $("#txtNombreContacto").val() );
        formData.append("telefono", $("#txtTelefono").val() );
        formData.append("email", $("#txtCorreoContacto").val() );
        formData.append("detalle", cadena);
        formData.append("noExcederCantidad", noExceder);
        formData.append("tipoCarga", $("#tipoCarga").val() );
        formData.append("tipoTransporte", $("#tipoTransporte").val() );
        formData.append("ordenCompra", $("#txtOrdenCompra").val() );
        formData.append("incluyeFleteFalso", incluyeFleteFalso );
        formData.append("cantidadFleteFalso", cantFleteFalso );
        formData.append("valorFleteFalso", valFleteFalso );
        formData.append("atrasado", Atrasado );

        var ruta= urlApp + "grabarNuevoPedido";
        $("#btnCrearPedido").attr("disabled", true);
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
                            title: 'Se ha creado el Pedido Nº ' + dato.identificador +" ¿Desea crear otro pedido para la misma nota de venta?",
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
                               
                                    //location.href= urlApp + "gestionarpedido/"+ $("#txtNumeroNotaVenta").val() + "/"; 
                                    actualizarDetalleNotaVenta();

                                
                            }else{
                                if(Origen=='QL'){
                                    location.href= urlApp + "listarPedidos";
                                }else{
                                    location.href= urlApp + "clientePedidos";
                                }
                            }
                        }
                    )                   
            }
        })
    }

    function actualizarDetalleNotaVenta(){
        console.log("SI LLEGA");
        var cadenaPlantas="<select class='form-control input-sm'>";
        var cadenaFormaEntrega="<select class='form-control input-sm'>";
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

        var tablaGranel=document.getElementById('tablaDetallePedidoGranel');
        var tablaNormal=document.getElementById('tablaDetallePedidoNormal');

        $.ajax({
            url: urlApp + "detalleNotaVenta",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { idNotaVenta: $("#txtNumeroNotaVenta").val()
                  },
            success:function(dato){
                for(var x=0;x<dato.length;x++){

                    for ( var i=1; i< tablaGranel.rows.length;i++){
                        if( tablaGranel.rows[i].cells[0].innerHTML.trim()==dato[x].prod_codigo ){
                            tablaGranel.rows[i].cells[4].innerHTML=number_format(dato[x].cantidad,0);
                            tablaGranel.rows[i].cells[6].innerHTML=number_format(dato[x].saldo,0);
                            tablaGranel.rows[i].cells[7].getElementsByTagName('input')[0].value='';
                            break;
                        }
                    }

                    for ( var i=1; i< tablaNormal.rows.length;i++){
                        if( tablaNormal.rows[i].cells[0].innerHTML.trim()==dato[x].prod_codigo ){
                            tablaNormal.rows[i].cells[4].innerHTML=number_format(dato[x].cantidad,0);
                            tablaNormal.rows[i].cells[6].innerHTML=number_format(dato[x].saldo,0);
                            tablaNormal.rows[i].cells[7].getElementsByTagName('input')[0].value='';                           
                            break;
                        }
                    }
                }

                var tabla=document.getElementById('tablaDetallePedidoGranel'); 
                for(var x=1; x<tabla.rows.length; x++){
                    tabla.rows[x].style.display='none';
                }

                $("#txtFechaEntrega").val('');
                document.getElementById('horario').selectedIndex=-1;
                $("#btnCrearPedido").attr("disabled", false);
            }
        })        
    }

    function verNotaVenta(){
        $("#mdNotaVenta").modal('show');
    }

    function cerrarDetalleNotaVenta(){
        $("#mdNotaVenta").modal('hide');   
    }
    function agregarNuevoPedido(){

        var tabla = document.getElementById('tablaDetalle');
        var cadena='[';

        for (var i = 1; i < tabla.rows.length; i++){
             if(tabla.rows[i].cells[6].getElementsByTagName('input')[0].value!=""){
                cadena+='{';
                cadena+="'prod_codigo':'"+  tabla.rows[i].cells[0].innerHTML  + "', ";
                cadena+="'cantidad':'"+  tabla.rows[i].cells[3].innerHTML.trim()  + "', ";
                cadena+="'formula':'"+  tabla.rows[i].cells[1].innerHTML  + "', ";
                cadena+="'u_codigo':'"+  tabla.rows[i].cells[4].innerHTML  + "'";
                cadena+='}, ';
            }
        }

        cadena=cadena.slice(0,-2);
        cadena+=']';

        var ruta="agregarObra";

        $.ajax({
            url: ruta,
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { numeroNotaVenta: $("#txtNumeroCotizacion").val(),
                    detalle: cadena
                  },
            success:function(dato){
                $("#txtNombreContacto").val( $("#txtNombreContactoObra").val() );
                $("#txtCorreoContacto").val( $("#txtCorreoContactoObra").val() );
                $("#txtTelefono").val( $("#txtTelefonoObra").val() );
                cerrarNuevaObra();
            }
        })
    }