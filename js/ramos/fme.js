$(vista_fmes());
function vista_fmes(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_fme.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_fme").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
