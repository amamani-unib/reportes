$(vista_ccses());
function vista_ccses(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_ccse.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_ccse").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
