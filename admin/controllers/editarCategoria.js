$.ajax({
	method: "POST",
	url: "../../models/obtenerCategoria.php",
	data : {
		"IdCategoria" : IdCategoria
	},
	success: function(categoria){
		$("#idCategoriaTxt").text("ID: "+IdCategoria);
		$("#idCategoria").val(IdCategoria);
		categoria.nombre_categoria = cambiarATilde(categoria.nombre_categoria);
		$("#nombre").val(categoria.nombre_categoria);

		if(categoria.mostrar_categoria==1){
			$("#inputMostrar").attr("checked","checked");
			$("[for='inputMostrar']").empty();
			$("[for='inputMostrar']").text("Los articulos de esta categoria se muestran");
		}else{
			$("#inputMostrar").removeAttr("checked");
			$("[for='inputMostrar']").empty();
			$("[for='inputMostrar']").text("Los articulos de esta categoria no se muestran");
		}
	},
	dataType: "json"
});

$("#inputMostrar").change(() => {
	if($("#inputMostrar").prop("checked")){
		$("[for='inputMostrar']").empty();
		$("[for='inputMostrar']").text("Los articulos de esta categoria se muestran");
	}else{
		$("[for='inputMostrar']").empty();
		$("[for='inputMostrar']").text("Los articulos de esta categoria no se muestran");
	}
});

$("#bttnDeshacer").click(() => {
	event.preventDefault();
	document.location.reload();
});

$("#bttnEditar").click(() => {
	var nombre = $("#nombre").val().trim();
	if(nombre != null && nombre != ""){	
		$.ajax({
			method: "POST",
			data: {
				idCategoria : IdCategoria,
				nombre : nombre,
				mostrar : $("#inputMostrar")[0].checked
			},
			url: "../models/editarCategoria.php",
			success: function(result){
				window.location.assign("listadoCategorias.php");
			},
			dataType: "text"
		});
	}
});

function cambiarATilde(string) {
	string = string.replace("&aacute","á");
	string = string.replace("&eacute","é");
	string = string.replace("&iacute","í");
	string = string.replace("&oacute","ó");
	string = string.replace("&uacute","ú");
	return string;
}