$(vista_eses());
function vista_eses(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_ese.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_ese").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
