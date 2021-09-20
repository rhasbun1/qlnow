var arrCamiones=new Array();
var arrConductores=new Array();



function cargarListas(idEmpresaTransporte, fila){
    var tabla=document.getElementById("tablaDetalle");
    var selConductor = tabla.rows[fila].cells[9].getElementsByTagName("select")[0];
    selConductor.length=0; 
    var selCamion = tabla.rows[fila].cells[7].getElementsByTagName("select")[0];
    selCamion.length=0; 

    empresaConductores(idEmpresaTransporte, fila, selConductor);
    empresaCamiones(idEmpresaTransporte, fila, selCamion);
  
}

function agregarNota(){
    if($("#txtNota").val().trim()==''){
        swal(
            {
                title: 'La nota no puede estar en blanco!' ,
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
                return
            }
        )          
        return;
    }
    $.ajax({
        url: urlApp + "agregarNota",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { idPedido: $("#idPedido").val(),
                nota: $("#txtNota").val()
              },
        success:function(dato){
            cadena="<tr>";
            cadena+="<td>" + dato[0].fechaHora + "</td>";
            cadena+="<td>" + dato[0].nombreUsuario + "</td>";
            cadena+="<td>" + $("#txtNota").val() + "</td>";
            cadena+="<td>" + "<button class='btn btn-warning btn-sm' onclick='eliminarNota(" + dato[0].idNota + ", this.parentNode.parentNode.rowIndex)'>Eliminar</button></td>";
            cadena+="</tr>";
            $("#tablaNotas").append(cadena);
            $("#txtNota").val('');
            $("#contNotas").html(document.getElementById('tablaNotas').rows.length-1);
        }
    })
}

function eliminarNota(idNota, fila){
    $.ajax({
        url: urlApp + "eliminarNota",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { idNota: idNota
              },
        success:function(dato){
            document.getElementById('tablaNotas').deleteRow(fila);
            swal(
                {
                    title: 'La nota ha sido eliminada!' ,
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
                    return
                }
            )
        }
    })    
}

function empresaConductores(idEmpresaTransporte, fila, selConductor){

    for(var x=0;x<arrConductores.length;x++){
        if(arrConductores[x][0]==idEmpresaTransporte){
            var opt = document.createElement('option');
            opt.value = arrConductores[x][1];
            opt.innerHTML = arrConductores[x][2]+' '+arrConductores[x][3]+' ' +arrConductores[x][4];
            if(idConductor==arrConductores[x][1]){
                opt.selected=true;
            }else{
                opt.selected=false;
            }
            selConductor.appendChild(opt);
        }
    } 
}

function empresaCamiones(idEmpresaTransporte, fila, selCamion){
    for(var x=0;x<arrCamiones.length;x++){
        if(arrCamiones[x][0]==idEmpresaTransporte){
            var opt = document.createElement('option');
            opt.value = arrCamiones[x][1];
            opt.innerHTML = arrCamiones[x][2];
            if(idCamion==arrCamiones[x][1]){
                opt.selected=true;
            }else{
                opt.selected=false;
            }                                
            selCamion.appendChild(opt);
        }
    }        
}


function guardarDatosProgramacion(idPedido, origen){
    var tabla = document.getElementById('tablaDetalle');
    var cadena='[';

    if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=="2"){

        for (var i = 1; i < tabla.rows.length; i++){

                if ( parseInt(tabla.rows[i].cells[1].dataset.guia)==0 ){

                    fechaCarga='';
                    horaCarga='';
                    peso="0";
                    idTransporte='0';
                    idCamion="0";
                    idConductor="0";
                    nombreEmpresaTransporte="";
                    nombreConductor="";
                    patente="";
                    idPlanta=tabla.rows[i].cells[4].getElementsByTagName('select')[0].value;
                    if(tabla.rows[1].cells[4].getElementsByTagName('select')[0].value.trim()!= 
                        tabla.rows[2].cells[4].getElementsByTagName('select')[0].value.trim()){
                        swal(
                            {
                                title: 'las plantas de origen tienen que ser las mismas' ,
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                    confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            });
                            document.getElementById("btnAsignarGuia").disabled=false;

                            return;
                    }
                    if( tabla.rows[i].cells[5].innerHTML.trim()!="Retira"  ){
                        fila=1;
                        idTransporte=tabla.rows[fila].cells[6].getElementsByTagName('select')[0].value;
                        idCamion=tabla.rows[fila].cells[7].getElementsByTagName('select')[0].value;
                        rampla=tabla.rows[fila].cells[8].getElementsByTagName('select')[0].value
                        idConductor=tabla.rows[fila].cells[9].getElementsByTagName('select')[0].value;
                        if(tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim() !='' ){
                            fechaCarga=fechaAtexto(  tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value );
                        }
                        if(tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()!=''){
                            horaCarga=tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value;  
                        }
                    }else{
                        if(tabla.rows[i].cells[1].dataset.guia==0){
                            if(tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
                                fila=i;
                            }else{
                                fila=1;
                            }
                            nombreEmpresaTransporte=tabla.rows[fila].cells[6].getElementsByTagName('input')[0].value;
                            patente=tabla.rows[fila].cells[7].getElementsByTagName('input')[0].value;
                            rampla='0';
                            nombreConductor=tabla.rows[fila].cells[9].getElementsByTagName('input')[0].value;
                            if(tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim() !='' ){
                                fechaCarga=fechaAtexto(  tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value );
                            }
                            if(tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()!=''){
                                horaCarga=tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value;  
                            }
                        }

                    }

                    cadena+='{';
                    cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML.trim()  + '", ';
                    cadena+='"idEmpresaTransporte":"'+ idTransporte + '", ';
                    cadena+='"idCamion":"'+ idCamion + '", ';
                    cadena+='"idConductor":"'+  idConductor + '", ';
                    cadena+='"nombreEmpresaTransporte":"'+  nombreEmpresaTransporte + '", ';
                    cadena+='"patente":"'+  patente + '", ';
                    cadena+='"numeroRampla":"' + rampla + '", ';
                    cadena+='"nombreConductor":"'+  nombreConductor + '", ';
                    cadena+='"peso":"0", ';
                    cadena+='"fechaCarga":"'+ fechaCarga  + '", ';
                    cadena+='"horaCarga":"'+ horaCarga + '", ';
                    cadena+='"idPlanta":"'+ idPlanta + '"';
                    cadena+='}, ';
                }

        }
        cadena=cadena.slice(0,-2);
        cadena+=']';

    }else{
        
        for (var i = 1; i < tabla.rows.length; i++){

                if ( parseInt(tabla.rows[i].cells[1].dataset.guia)==0 ){            

                    fechaCarga='';
                    horaCarga='';
                    peso="0";
                    idTransporte='0';
                    idCamion="0";
                    idConductor="0";
                    nombreEmpresaTransporte="";
                    nombreConductor="";
                    patente="";
                    
                    if(tabla.rows[i].cells[4].getElementsByTagName('select')[0]){
                        idPlanta=tabla.rows[i].cells[4].getElementsByTagName('select')[0].value;  
                    }else{
                        idPlanta=tabla.rows[i].cells[4].dataset.idplanta;
                    } 

                    if( tabla.rows[i].cells[5].innerHTML.trim()!="Retira"  ){

         //               if(tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
          //                  fila=i;
          //              }else{
          //                  fila=1;
          //              }
                        fila=i;
                        idTransporte=tabla.rows[fila].cells[6].getElementsByTagName('select')[0].value;
                        idCamion=tabla.rows[fila].cells[7].getElementsByTagName('select')[0].value;
                        rampla=tabla.rows[fila].cells[8].getElementsByTagName('select')[0].value
                        idConductor=tabla.rows[fila].cells[9].getElementsByTagName('select')[0].value;
                        if(tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim() !='' ){
                            fechaCarga=fechaAtexto(  tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value );
                        }
                        if(tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()!=''){
                            horaCarga=tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value;  
                        }
                    }else{
                        fila=i;
                        if(tabla.rows[fila].cells[6].getElementsByTagName('input')[0]){
                            nombreEmpresaTransporte=tabla.rows[fila].cells[6].getElementsByTagName('input')[0].value;
                            patente=tabla.rows[fila].cells[7].getElementsByTagName('input')[0].value;
                            rampla="0";
                            nombreConductor=tabla.rows[fila].cells[9].getElementsByTagName('input')[0].value;
                            if(tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim() !='' ){
                                fechaCarga=fechaAtexto(  tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value );
                            }
                            if(tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()!=''){
                                horaCarga=tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value;  
                            }                            
                        }else{
                            nombreEmpresaTransporte=tabla.rows[fila].cells[6].innerHTML;
                            patente=tabla.rows[fila].cells[7].innerHTML;
                            rampla="0";
                            nombreConductor=tabla.rows[fila].cells[9].innerHTML;
                            fechaCarga=fechaAtexto(  tabla.rows[fila].cells[10].innerHTML );
                            horaCarga=tabla.rows[fila].cells[11].innerHTML;                      
                        }

                    }

                    cadena+='{';
                    cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML.trim()  + '", ';
                    cadena+='"idEmpresaTransporte":"'+ idTransporte + '", ';
                    cadena+='"idCamion":"'+ idCamion + '", ';
                    cadena+='"idConductor":"'+  idConductor + '", ';
                    cadena+='"nombreEmpresaTransporte":"'+  nombreEmpresaTransporte + '", ';
                    cadena+='"patente":"'+  patente + '", ';
                    cadena+='"numeroRampla":"' + rampla + '", ';
                    cadena+='"nombreConductor":"'+  nombreConductor + '", ';
                    cadena+='"peso":"0", ';
                    cadena+='"fechaCarga":"'+ fechaCarga  + '", ';
                    cadena+='"horaCarga":"'+ horaCarga + '", ';
                    cadena+='"idPlanta":"'+ idPlanta + '"';
                    cadena+='}, ';
                }

        }
        cadena=cadena.slice(0,-2);
        cadena+=']';        
    }

    var ruta= urlApp + "guardarDatosProgramacion";
    $.ajax({
        async: false, 
        url: ruta,
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { idPedido: idPedido,
                detalle: cadena
              },
        success:function(dato){
            if(origen==1){
                swal(
                    {
                        title: 'Se han guardado los datos de Programación' ,
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
                            location.href=urlApp+"programacion";                               
                        }
                    }
                )
            }                   
        }
    })

}

function asignarFolio(){
    var tabla=document.getElementById('tablaDetalle');

    var retira=false;
    var enObra=false;
    var chequeaDatosTransporte='';
    var cont=0;
    var compara='';

    document.getElementById("btnAsignarGuia").disabled=true;

    if(tabla.rows[1].cells[13].innerHTML.trim() == "2"){

            
            if(tabla.rows[1].cells[4].getElementsByTagName('select')[0].value.trim()!= 
            tabla.rows[2].cells[4].getElementsByTagName('select')[0].value.trim()){
                swal(
                    {
                        title: 'las plantas de origen tienen que ser las mismas' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                            confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    });
                    document.getElementById("btnAsignarGuia").disabled=false;

                    return;
            }
        }
    guardarDatosProgramacion( $("#idPedido").val() , 2);

   
    for (var i = 1; i < tabla.rows.length; i++){
        if(tabla.rows[i].cells[12].getElementsByTagName('input')[0]){
            if(tabla.rows[i].cells[12].getElementsByTagName('input')[0].checked){

                if(tabla.rows[i].cells[5].innerHTML.trim()=='Retira'){
                    if(tabla.rows[i].cells[3].innerHTML.trim()!='tonelada'){
                       
                        fila=i;
                    }else{
                        fila=1;
                    } 

                    retira=true;
                    cont=1;
                 //   if(tabla.rows[i].cells[6].getElementsByTagName('input')[0] ){
                    if( tabla.rows[fila].cells[6].getElementsByTagName('input')[0].value.trim()=='' ||
                    tabla.rows[fila].cells[7].getElementsByTagName('input')[0].value.trim()==''||
                    tabla.rows[fila].cells[9].getElementsByTagName('input')[0].value.trim()==''||
                    tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim()==''||
                    tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()==''){

                            swal(
                                {
                                    title: 'Debe completar todos los datos de transporte' ,
                                    text: '',
                                    type: 'warning',
                                    showCancelButton: false,
                                        confirmButtonText: 'OK',
                                    cancelButtonText: '',
                                    closeOnConfirm: true,
                                    closeOnCancel: false
                                });
                            document.getElementById("btnAsignarGuia").disabled=false;
                            return;

                        }                        
                   // }

                }else if(tabla.rows[i].cells[5].innerHTML.trim()!='Retira'){
                   

                    if(tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
                        fila=i;
                    }else{
                        fila=1;
                    }                    

                    if( tabla.rows[fila].cells[6].getElementsByTagName('select')[0].value.trim()=='0' ||
                                  tabla.rows[fila].cells[7].getElementsByTagName('select')[0].value.trim()=='0' ||
                                  tabla.rows[fila].cells[9].getElementsByTagName('select')[0].value.trim()=='0'||
                                  tabla.rows[fila].cells[10].getElementsByTagName('input')[0].value.trim()==''||
                                  tabla.rows[fila].cells[11].getElementsByTagName('input')[0].value.trim()=='' ) {

                        swal(
                            {
                                title: 'Debe completar todos los datos de transporte' ,
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            });
                        document.getElementById("btnAsignarGuia").disabled=false;
                        return;

                    }


                    enObra=true;
                    compara = tabla.rows[fila].cells[6].getElementsByTagName('select')[0].value.trim()+"|"+
                              tabla.rows[fila].cells[7].getElementsByTagName('select')[0].value.trim()+"|"+
                              tabla.rows[fila].cells[8].getElementsByTagName('select')[0].value.trim()+"|"+
                              tabla.rows[fila].cells[9].getElementsByTagName('select')[0].value.trim();
                    if( chequeaDatosTransporte!=compara){
                        chequeaDatosTransporte=compara;
                        cont+=1;
                    }

                    if(tabla.rows[fila].cells[6].getElementsByTagName('select')[0].selectedIndex<0 || tabla.rows[fila].cells[7].getElementsByTagName('select')[0].selectedIndex<0 ||
                        tabla.rows[fila].cells[8].getElementsByTagName('select')[0].selectedIndex<0 || tabla.rows[fila].cells[9].getElementsByTagName('select')[0].selectedIndex<0  ){
                        document.getElementById("btnAsignarGuia").disabled=false;
                        alert('Los productos seleccionados deben tener los datos de transporte completo y deben ser los mismos para todos los productos.');
                        return
                    }                    
                }

            } 
        }

    }

    if(retira && enObra){
        document.getElementById("btnAsignarGuia").disabled=false;
        alert('No puede emitir una guía con productos retirados por cliente y otros entregados en obra');
        return;
    }

    if(cont>1){
        document.getElementById("btnAsignarGuia").disabled=false;
        alert('Los productos seleccionados para incluir en la guía deben tener los mismos datos de transporte.');
        return;
    }else if(cont==0){
        document.getElementById("btnAsignarGuia").disabled=false;
        alert('No hay productos seleccionados para generar la guía.');
        return;        
    }

    if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=="2"){
        for (var i = 1; i < tabla.rows.length; i++){
            if(tabla.rows[i].cells[11].getElementsByTagName('input')[0]){
                tabla.rows[i].cells[11].getElementsByTagName('input')[0].checked=true;
            }
        }
    }


    var cadena='[';
    var transporte = "";
    var camion = "";
    var conductor = "";
    var plantaElegida = "";
    var contador = 1;
    for (var i = 1; i < tabla.rows.length; i++){
        if(tabla.rows[i].cells[12].getElementsByTagName('input')[0]){
            if(tabla.rows[i].cells[12].getElementsByTagName('input')[0].checked){
                cadena+='{';
                cadena+='"idPedido":"'+  $("#idPedido").val()  + '", ';
                cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML.trim()  + '", ';
                cadena+='"unidad":"'+  tabla.rows[i].cells[3].innerHTML.trim()  + '", ';
                cadena+='"cantidad":"0"';
                cadena+='}, ';
            
                if(tabla.rows[1].cells[5].innerHTML.trim()=='En Obra'){
                    if(tabla.rows[1].cells[3].innerHTML.trim()!='tonelada'){
                        if(contador>1){
                            if(transporte != tabla.rows[i].cells[6].getElementsByTagName('select')[0].value.trim() || camion != tabla.rows[i].cells[7].getElementsByTagName('select')[0].value.trim() || conductor !=tabla.rows[i].cells[9].getElementsByTagName('select')[0].value.trim()){
        
                                swal(
                                    {
                                        title: 'los datos de transporte deben ser los mismos!!' ,
                                        text: '',
                                        type: 'warning',
                                        showCancelButton: false,
                                            confirmButtonText: 'OK',
                                        cancelButtonText: '',
                                        closeOnConfirm: true,
                                        closeOnCancel: false
                                    });
                                    document.getElementById("btnAsignarGuia").disabled=false;
        
                                    return;                   
                                 }

                                 if(plantaElegida!=tabla.rows[i].cells[4].getElementsByTagName('select')[0].value.trim()){
        
                                    swal(
                                        {
                                            title: 'los datos de planta deben ser los mismos!!' ,
                                            text: '',
                                            type: 'warning',
                                            showCancelButton: false,
                                                confirmButtonText: 'OK',
                                            cancelButtonText: '',
                                            closeOnConfirm: true,
                                            closeOnCancel: false
                                        });
                                        document.getElementById("btnAsignarGuia").disabled=false;
            
                                        return;                   
                                     }
                            
                        }
                        transporte = tabla.rows[i].cells[6].getElementsByTagName('select')[0].value.trim();
                        camion =tabla.rows[i].cells[7].getElementsByTagName('select')[0].value.trim();
                        conductor =tabla.rows[i].cells[9].getElementsByTagName('select')[0].value.trim();
                        plantaElegida = tabla.rows[i].cells[4].getElementsByTagName('select')[0].value.trim();
                        contador++;
    
                    }
                }else{
                    if(tabla.rows[1].cells[3].innerHTML.trim()!='tonelada'){
                        if(contador>1){
                            if(transporte != tabla.rows[i].cells[6].getElementsByTagName('input')[0].value.trim() || camion != tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim() || conductor !=tabla.rows[i].cells[9].getElementsByTagName('input')[0].value.trim()){
        
                                swal(
                                    {
                                        title: 'los datos de transporte deben ser los mismos!!' ,
                                        text: '',
                                        type: 'warning',
                                        showCancelButton: false,
                                            confirmButtonText: 'OK',
                                        cancelButtonText: '',
                                        closeOnConfirm: true,
                                        closeOnCancel: false
                                    });
                                    document.getElementById("btnAsignarGuia").disabled=false;
        
                                    return;                   
                                 }
                                 if(plantaElegida!=tabla.rows[i].cells[4].getElementsByTagName('select')[0].value.trim()){
        
                                    swal(
                                        {
                                            title: 'los datos de planta deben ser los mismos!!' ,
                                            text: '',
                                            type: 'warning',
                                            showCancelButton: false,
                                                confirmButtonText: 'OK',
                                            cancelButtonText: '',
                                            closeOnConfirm: true,
                                            closeOnCancel: false
                                        });
                                        document.getElementById("btnAsignarGuia").disabled=false;
            
                                        return;                   
                                     }
                            
                        }
                        transporte = tabla.rows[i].cells[6].getElementsByTagName('input')[0].value.trim();
                        camion =tabla.rows[i].cells[7].getElementsByTagName('input')[0].value.trim();
                        conductor =tabla.rows[i].cells[9].getElementsByTagName('input')[0].value.trim();
                        plantaElegida = tabla.rows[i].cells[4].getElementsByTagName('select')[0].value.trim();
                        contador++;
    
                    }
                }
                
              
            }
        }
    }
    cadena=cadena.slice(0,-2);
    cadena+=']';
 
    $.ajax({
        url: urlApp + "crearGuiaDespachoElectronica",
        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
        type: 'POST',
        dataType: 'json',
        data: { idPedido: $("#idPedido").val() ,
                detalle: cadena
              },
        success:function(dato){
                var fila=0; 
                for (var i = 1; i < tabla.rows.length; i++){
                    if(tabla.rows[i].cells[12].getElementsByTagName('input')[0]){
                        if(tabla.rows[i].cells[12].getElementsByTagName('input')[0].checked){

                           tabla.rows[i].cells[1].innerHTML=tabla.rows[i].cells[1].innerHTML+
                            "<span onclick='abrirGuia(1, " + dato.nuevaGuia + ", this.parentNode.parentNode);'" +  
                            "style='cursor:pointer; cursor: hand'><img src='" + urlApp + "img/iconos/guiaDespacho2.png' border='0'></span>"

                            tabla.rows[i].cells[12].innerHTML='';

                            var planta=tabla.rows[i].cells[4].getElementsByTagName('select')[0];
                            tabla.rows[i].cells[4].innerHTML=planta.options[planta.selectedIndex].text;
                            
                            if(tabla.rows[i].cells[5].innerHTML.trim()=='Retira'){
                                if(tabla.rows[i].cells[6].getElementsByTagName('input')[0]){
                                    tabla.rows[i].cells[6].innerHTML=tabla.rows[i].cells[6].getElementsByTagName('input')[0].value;
                                    tabla.rows[i].cells[7].innerHTML=tabla.rows[i].cells[7].getElementsByTagName('input')[0].value;
                                    tabla.rows[i].cells[8].innerHTML=tabla.rows[i].cells[8].getElementsByTagName('input')[0].value;
                                    tabla.rows[i].cells[9].innerHTML=tabla.rows[i].cells[9].getElementsByTagName('input')[0].value;                                    
                                }

                            }else{

                                if(tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
                                    var emp=tabla.rows[i].cells[6].getElementsByTagName('select')[0];
                                    tabla.rows[i].cells[6].innerHTML=emp.options[emp.selectedIndex].text;
                                    var camion=tabla.rows[i].cells[7].getElementsByTagName('select')[0];
                                    tabla.rows[i].cells[7].innerHTML=camion.options[camion.selectedIndex].text;                                
                                    var rampla=tabla.rows[i].cells[8].getElementsByTagName('select')[0];
                                    tabla.rows[i].cells[8].innerHTML=rampla.options[rampla.selectedIndex].text;                                    
                                    var conductor=tabla.rows[i].cells[9].getElementsByTagName('select')[0];
                                    tabla.rows[i].cells[9].innerHTML=conductor.options[conductor.selectedIndex].text;                                    
                                }
                            }

                            if(tabla.rows[i].cells[10].getElementsByTagName('input')[0]){
                                tabla.rows[i].cells[10].innerHTML=tabla.rows[i].cells[10].getElementsByTagName('input')[0].value;
                                tabla.rows[i].cells[11].innerHTML=tabla.rows[i].cells[11].getElementsByTagName('input')[0].value;
                            }

                            fila=i;                        

                        }                         
                    }

                } 
                if($("#tipoCarga").val()=="1" && $("#tipoTransporte").val()=="2"){
                    for (var i = 1; i < tabla.rows.length; i++){
                        tabla.rows[i].cells[12].innerHTML='';
                    }
                    document.getElementById('btnGuardarProgramacion').style.display="none";
                    document.getElementById('btnAsignarGuia').style.display="none";
                    abrirGuia(1, dato.nuevaGuia, fila );     
                }else{
                    document.getElementById("btnAsignarGuia").disabled=false;                      
                    abrirGuia(1, dato.nuevaGuia, fila );                    
                }


        },
        error: function(xhr, ajaxOptions, thrownError) {
            document.getElementById("btnAsignarGuia").disabled=false;
        }
    })
}

