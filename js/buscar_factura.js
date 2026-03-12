$(busca_factura());
function busca_factura(consulta){
     $.ajax({
         url: 'busquedas/filtrar_factura.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#busca_factura").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#factura", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_factura(valor);
    }else{
        busca_factura();
    }
});
