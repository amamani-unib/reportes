$(busca_polizas());
function busca_polizas(consulta){
     $.ajax({
         url: 'busquedas/filtrar_poliza.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_poliza").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#poliza", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_polizas(valor);
    }else{
        busca_polizas();
    }
});
