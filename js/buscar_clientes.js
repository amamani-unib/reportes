$(busca_clientes());
function busca_clientes(consulta){
     $.ajax({
         url: 'busquedas/filtrar_cliente.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#datos_clientes").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#cliente", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_clientes(valor);
    }else{
        busca_clientes();
    }
});
