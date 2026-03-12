$(busca_polizas3());
function busca_polizas3(consulta){
     $.ajax({
         url: 'busquedas/filtrar_poliza3.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_poliza3").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#poliza", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_polizas3(valor);
    }else{
        busca_polizas3();
    }
});
