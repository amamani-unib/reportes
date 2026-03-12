$(vista_sdps());
function vista_sdps(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_sdp.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_sdp").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
