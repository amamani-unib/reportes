$(vista_arms());
function vista_arms(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_arm.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_arm").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
