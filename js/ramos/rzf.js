$(vista_rzfs());
function vista_rzfs(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_rzf.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_rzf").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
