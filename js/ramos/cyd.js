$(vista_cyds());
function vista_cyds(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_cyd.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_cyd").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
