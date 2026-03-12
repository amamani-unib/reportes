function actualizar_datos(){
	var datos=$("").serialize();
	$.ajax({
		method:"POST",
		url:"../config/actualiza_factura.php",
		data:datos,
		success: function(e){
			if (e==1){
				alert("Registro acturalizado");
			}else{
				alet("Error de Registro");
			}
		}

	});
	return false;
}