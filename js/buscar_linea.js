$(busca_lineas());
function busca_lineas(consulta){
     $.ajax({
         url: 'busquedas/filtrar_linea.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#busca_linea").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#linea", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_lineas(valor);
    }else{
        busca_lineas();
    }
});
