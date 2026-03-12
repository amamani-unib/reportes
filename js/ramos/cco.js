$(vista_ccos());
function vista_ccos(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_cco.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_cco").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
