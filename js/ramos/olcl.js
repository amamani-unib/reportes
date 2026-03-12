$(vista_olcls());
function vista_olcls(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_olcl.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_olcl").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
