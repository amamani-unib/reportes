$(vista_cias());
function vista_cias(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_cia.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_cia").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
