$(vista_beos());
function vista_beos(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_beo.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_beo").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
