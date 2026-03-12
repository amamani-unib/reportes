$(busca_emisor());
function busca_emisor(consulta){
     $.ajax({
         url: 'busquedas/filtrar_emisor.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#busca_emisor").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#emisor", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_emisor(valor);
    }else{
        busca_emisor();
    }
});
