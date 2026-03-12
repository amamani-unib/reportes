$(vista_osafis());
function vista_osafis(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_osafi.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_osafi").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
