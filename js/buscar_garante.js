$(busca_garantes());
function busca_garantes(consulta){
     $.ajax({
         url: 'busquedas/filtrar_garante.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_garante").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#garante", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_garantes(valor);
    }else{
        busca_garantes();
    }
});
