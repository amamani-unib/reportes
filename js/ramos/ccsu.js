$(vista_ccsus());
function vista_ccsus(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_ccsu.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_ccsu").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });

}
function cargar() {
    cadenas="id=asdasd";
    $.ajax({
         type:'POST',
          url:'vistas_ramos/ramo_ccsu.php',
          data:cadenas,
      });
    
    
  }
