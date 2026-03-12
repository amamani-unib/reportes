$(busca_dashboards());
function busca_dashboards(consulta){
     $.ajax({
         url: 'busquedas/filtrar_dashboard.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#busca_dashboard").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
$(document).on("keyup", "#dash", function(){
    var valor = $(this).val();
    if(valor!=""){
        busca_dashboards(valor);
    }else{
        busca_dashboards();
    }
});
