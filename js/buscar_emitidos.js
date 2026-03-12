$(busca_emitidos());
function busca_emitidos(consulta){
     $.ajax({
         url: 'busquedas/filtrar_emitidos.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_emitido").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#emitido", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_emitidos(valor);
    }else{
        busca_emitidos();
    }
});
