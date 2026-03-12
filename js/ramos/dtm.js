$(vista_dtms());
function vista_dtms(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_dtm.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_dtm").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
