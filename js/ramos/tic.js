$(vista_tics());
function vista_tics(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_tic.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_tic").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
