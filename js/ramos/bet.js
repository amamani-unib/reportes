$(vista_bets());
function vista_bets(consulta){
     $.ajax({
         url: 'vistas_ramos/ramo_bet.php',
         type: 'POST',
         dataType: 'html',
         data: {consulta: consulta},
         
     })
    .done(function(respuesta){
         $("#vista_bet").html(respuesta);
     })
    .fail(function(){
         console.log("error");
     });
}
