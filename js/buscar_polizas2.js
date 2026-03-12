$(busca_polizas2());
function busca_polizas2(consulta){
     $.ajax({
         url: 'busquedas/filtrar_poliza2.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_poliza2").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#poliza", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_polizas2(valor);
    }else{
        busca_polizas2();
    }
});
