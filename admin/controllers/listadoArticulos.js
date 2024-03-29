var numArticulosCargados;
var totalArticulos;
var articulos;

$.ajax({
	method: "POST",
	url: "../../models/obtenerCategorias.php",
	success: function(categorias){
		for(let i in categorias){
			$("#seleccionCategoria").append(
				$("<option>").attr("value",categorias[i].Id).text(categorias[i].nombre_categoria)
			);
		}
		$("#seleccionCategoria").change(() => {
			cargarArticulos();
		});
		$("#seleccionOrden").change(() => {
			cargarArticulos();
		});
	},
	dataType: "json"
});

cargarArticulos();

$(window).scroll(function(){
	if($(window).scrollTop() + $(window).height() == $(document).height()){
		if(numArticulosCargados < totalArticulos){
			mostrarArticulos();
		}
	}
});

function cargarArticulos(){
	$.ajax({
		method: "POST",
		url: "../models/obtenerArticulos.php",
		data: {
			"seleccionCategoria": $("#seleccionCategoria").val(),
			"seleccionOrden": $("#seleccionOrden").val()
		},
		success: function(resultArticulos){
			$("#divListadoArticulos").empty();
			articulos = resultArticulos;
			numArticulosCargados = 0;
			totalArticulos = articulos.length;
			mostrarArticulos();
		},
		dataType: "json"
	});
}

function mostrarArticulos(){
	let numArticulosAcargar = 15;
	if(numArticulosCargados + numArticulosAcargar > totalArticulos){
		numArticulosAcargar = totalArticulos - numArticulosCargados;
	}
	for(let i = numArticulosCargados; i < numArticulosCargados + numArticulosAcargar; i++){
		articulos[i].indexImagenesArticulo = 0;
		$("#divListadoArticulos").append(
			$("<div>").addClass("contenedor-de-articulo").append(
				$("<div>").addClass("articulo").attr("id","articulo"+articulos[i].id).append(
					$("<div>").addClass("mySlides").addClass("fade").append(
						$("<img>").attr("id","imagenesArticulo"+articulos[i].id)
					),
					$("<div>").addClass("div_info_articulo").append(
						$("<h1>").addClass("nombre_articulo").text(articulos[i].nombre),
						$("<p>").text("art:" + articulos[i].id + " - Precio: " + articulos[i].precio),
						$("<p>").addClass("descripcion_articulo").text(articulos[i].descripcion)
					)
				),
				$("<div>").addClass("disponibilidad").append(
					$("<p>").text(textoDisponibilidad(articulos[i]))
				),
				$("<button>").addClass("button").text("Editar articulo").click(() => {
					window.location.assign("editarArticulo.php?id="+articulos[i].id);
				})
			)
		);
		//Si el articulo.disponibilidad es 2 se muestra como agotado
		if(articulos[i].disponibilidad == 2){
			$("#articulo"+articulos[i].id).append(
				$("<div>").addClass("agotado").append(
					$("<div>").addClass("banda_agotado").append(
						$("<h1>").text("PRODUCTO AGOTADO")
					)
				)
			);
		}
		//Si el articulo tiene imagenes asociadas se las agregamos y mostramos la primera
		if(articulos[i].imagenes != null){
			$("#imagenesArticulo"+articulos[i].id).attr("src","../../imagenes/"+articulos[i].imagenes[0]);
			//Si tiene más de una imagen asociada creamos las flechas y al pulsarlas se mostrará la siguiente o la anterior imagen
			if(articulos[i].imagenes.length > 1){
				$("#articulo"+articulos[i].id).append(
					$("<a>").addClass("prev").html("&#10094;").click(() => {
						articulos[i].indexImagenesArticulo -= 1;
						if(articulos[i].indexImagenesArticulo < 0){
							articulos[i].indexImagenesArticulo = articulos[i].imagenes.length-1;
						}
						mostrarImagen(articulos[i]);
					}),
					$("<a>").addClass("next").html("&#10095;").click(() => {
						articulos[i].indexImagenesArticulo += 1;
						if(articulos[i].indexImagenesArticulo >= articulos[i].imagenes.length){
							articulos[i].indexImagenesArticulo = 0;
						}
						mostrarImagen(articulos[i]);
					})
				)
			}
		}	
	}
	numArticulosCargados += numArticulosAcargar;
	ajustarTamañoImagenes();
}

function textoDisponibilidad(articulo){
	if(articulo.mostrar == 0){
		return "El articulo no se muestra";
	}else{
		switch (articulo.disponibilidad) {
			case "1":
			return "El articulo se muestra normalmente";
			case "2":
			return "El articulo se muestra como agotado";
			case "3":
			return "El articulo se muestra como unidades limitadas";
			default:
			return "El articulo se muestra normalmente";
		}
	}
}

function mostrarImagen(articulo) {
	imagenAMostrar = "../../imagenes/"+articulo.imagenes[articulo.indexImagenesArticulo];
	$("#imagenesArticulo"+articulo.id).attr("src",imagenAMostrar);
}