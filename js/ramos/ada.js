$(vista_adas());
function vista_adas(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_ada.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_ada").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
