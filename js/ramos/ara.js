$(vista_aras());
function vista_aras(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_ara.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_ara").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
