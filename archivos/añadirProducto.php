<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<title>Añadir Producto</title>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

		<link rel="stylesheet" type="text/css" href="../css/body.css">
		<link rel="stylesheet" type="text/css" href="../css/menuAdministracion.css">
		<link rel="stylesheet" type="text/css" href="../css/listadoImagenes.css">
		<link rel="stylesheet" type="text/css" href="../css/editarArticulos.css">
	</head>
	<body onresize="ajustarTamaño()">
		<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-database.js"></script>
		<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-auth.js"></script>
		<script type="text/javascript">
			var firebaseConfig = {
                apiKey: "AIzaSyD8INPLN05ja3GxHm-7r69zytBj_hQ75lM",
			    authDomain: "pruebausuarios-81a47.firebaseapp.com",
			    databaseURL: "https://pruebausuarios-81a47-default-rtdb.firebaseio.com",
			    projectId: "pruebausuarios-81a47",
			    storageBucket: "pruebausuarios-81a47.appspot.com",
			    messagingSenderId: "928136037074",
			    appId: "1:928136037074:web:a8ce760d154fa133daa30b",
			    measurementId: "G-574YQRLJM6"
            };
            firebase.initializeApp(firebaseConfig);
            
            firebase.auth().onAuthStateChanged(function(user) {
                if(user){
                    var email = user.email;
                    $(".div-icono-usuario i").empty();
                    $(".div-icono-usuario i").html(email.substr(0,1).toUpperCase());
                    $(".dropdown-content").empty();
                    $(".dropdown-content").html("<div class='dropdown-contenedora'>"+
	                    	"<a class='cambiar-pwd' onclick='cambiarContraseña'>Cambiar contraseña</a>"+
                    		"<a class='logout' onclick='cerrarSesion()'>Cerrar Sesión</a>"+
                    	"</div>");
                }else{
                	window.location.assign("iniciarSesion.php");
                }
            });

            function cerrarSesion(){
                firebase.auth().signOut();
            }
		</script>
		<nav class="navbar">
			<div class="container-fluid">
				<div class="div-menu-izquierda">
					<div class="navbar-header">
			    	<a class="navbar-brand" href="../index.php">Mercadillo Parroquial</a>
				    </div>
				    <ul class="ul-navbar">
					    <li><a href="listadoProductos.php">Productos</a></li>
					    <li><a href="listadoCategorias.php">Categorias</a></li>
					    <li><a href="listadoImagenes.php">Imagenes</a></li>
				    </ul>
				</div>
				<div class="div-menu-derecha">
					<div class="div-añadir">
	                    <div class="div-contenedor-añadir">
	                        <a href="añadirProducto.php">Añadir Producto</a>
	                    </div>
	                </div>
	                <div class="div-usuario dropdown">
	                    <div class="div-icono-usuario"><i></i></a>
	                    <div class="dropdown-content"></div>
	                </div>
				</div>
			</div>
		</nav>
		<script type="text/javascript">
			var fotos = new Array();
			var categorias = new Array();
		<?php
			include("conexion.php");
			$con=mysqli_connect($servidor,$usuario,$contrasena);
			$conectado=mysqli_select_db($con,$baseDeDatos);
			if(!$conectado){
				echo 'alert("ERROR")';
			}

			$result = $con->query("SELECT id,nombre_foto FROM imagenes");
			$j = 0;
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$idFoto = $row["id"];
					$nombreFoto = $row["nombre_foto"];
		?>							
					var idFoto = <?php echo "$idFoto"; ?>;
					var nombreFoto = <?php echo "'$nombreFoto'"; ?>;
		<?php
					echo '	fotos['.$j.'] = {idFoto,nombreFoto};';
					$j++;
				}
			}
			$result = $con->query("SELECT id,nombre_categoria FROM categorias");
			$j = 0;
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$idCategoria = $row["id"];
					$nombreCategoria = $row["nombre_categoria"];
		?>							
					var idCategoria = <?php echo "$idCategoria"; ?>;
					var nombreCategoria = <?php echo "'$nombreCategoria'"; ?>;
		<?php
					echo '	categorias['.$j.'] = {idCategoria,nombreCategoria};';
					$j++;
				}
			}
		?>
		</script>
		<div class="articulo">
			<form action="listadoProductos.php" method="post">
				<div class="div-contenedora-info-articulo">
					<h3>Nuevo producto</h3>
					<div class="div-input div-text">
						<label for="nombreProducto">Nombre del producto: </label><br>
						<input type="text" id="nombreProducto" name="nombreProducto"><br>
					</div>
					<div class="div-input div-text">
						<label for="descripcion">Descripción del producto: </label><br>
						<input type="text" id="descripcion" name="descripcion"><br>
					</div>
					<div class="div-precio-check">
						<div class="div-precio">
							<label for="precio">Precio:</label>
							<input type="number" min="0" step="0.01" id="precio" name="precio">€<br>
						</div>
						<div class="div-check">
							<input type="checkbox" id="mostrar" name="mostrar" onclick="cambioMostrar()" value="1" checked>
							<label for="mostrar">El articulo se muestra</label><br>
						</div>
					</div>
					<div class="div-input-radio">
						<input type="radio" id="dispoNomal" name="disponibilidad" value="1" checked>
						<label for="dispoNomal">El articulo se muestra normalmente</label><br>
						<input type="radio" id="dispoAgotado" name="disponibilidad" value="2">
						<label for="dispoAgotado">El articulo se muestra como agotado</label><br>
						<input type="radio" id="dispoUnidadesLimitadas" name="disponibilidad" value="3">
						<label for="dispoUnidadesLimitadas">El articulo se muestra con disponible con unidades limitadas</label>
					</div>
				</div>
				<div class="modificar-categorias-articulo">
					<h3>Selecione categoria(s)</h3>
					<input type="hidden" name="numCategorias" id="numCategorias">
					<script type="text/javascript">
						for(var i=0;i<categorias.length;i++){
							document.write('<div class="div-input-categoria">'+
								'<input type="checkbox" id="input-categoria-'+i+'" name="input-categoria-'+i+'" value="'+categorias[i].idCategoria+'">'+
							'<label for="input-categoria-'+i+'">'+categorias[i].nombreCategoria+'</label></div>');
						}
						$("#numCategorias").val(categorias.length);
					</script>
				</div>
				<div class="div-añadirImagen">
					<div class="div-añadirImagen-id">
						<label class="label-select-imagen-id" for="select-imagen-id">Seleciona ID de imagen: </label>
	                    <select name="select-imagen-id" id="select-imagen-id" onchange="ajustarIDNombreImagen(this.value)">
	                    	<option value="">--</option>
	                    	<script type="text/javascript">
	                    		for(var i=0; i<fotos.length;i++){
	                    			document.write("<option value="+i+">"+fotos[i].idFoto+"</option>");
								}
	                        </script>         
						</select>
					</div>
					<div class="div-añadirImagen-nombre">
						<label class="label-select-imagen-nombre" for="select-imagen-nombre">Seleciona nombre de imagen: </label>
	                    <select name="select-imagen-nombre" id="select-imagen-nombre" onchange="ajustarIDNombreImagen(this.value)">
	                    	<option value="">--</option>
	                    	<script type="text/javascript">
	                    		for(var i=0; i<fotos.length;i++){
	                    			document.write("<option value="+i+">"+fotos[i].nombreFoto+"</option>");
								}
	                        </script>         
						</select>
					</div>
					<div class="div-añadirImagen-boton">	
						<a onclick="sumImagen()">Añadir foto</a>
					</div>
				</div>
				<div class="div-contenedora-imagenes-articulo"></div>
				<input type="hidden" name="hiddenContadorImagenes" id="hiddenContadorImagenes">
				<div class="div-boton-editar">
					<button>AÑADIR PRODUCTO</button>
				</div>
			</form>
		</div>
		<script type="text/javascript">
			var contadorImagenes = 0;
			var contadorImagenesReales = contadorImagenes;
			$("#hiddenContadorImagenes").val(contadorImagenesReales);

			function cambioMostrar() {
				if($("#mostrar").prop("checked")){
					$("[for='mostrar']").empty();
					$("[for='mostrar']").text("El articulo se muestra");
				}else{
					$("[for='mostrar']").empty();
					$("[for='mostrar']").text("El articulo no se muestra");
				}
			}
			
			function ajustarIDNombreImagen(num) {
				$("#select-imagen-id").val(num);
				$("#select-imagen-nombre").val(num);
			}

			function htmlImagen (num,idFoto,nombreFoto) {
				return '<div class="contenedora-imagen" id="contenedora-imagen-'+num+'">'+
							'<div class="imagen">'+
								'<img src="../imagenes/'+nombreFoto+'">'+
							'</div><div class="info-imagen">'+
								'<p><b>ID:</b> '+idFoto+'</p>'+
								'<p><b>Nombre:</b> '+nombreFoto+'</p>'+
							'</div>'+
							'<input type="hidden" name="input-imagen-'+num+'" id="input-imagen-'+num+'">'+
							'<span class="cerrar" onclick="resImagen('+num+')">&times;</span>'+
						'</div>';
			}

			function sumImagen(){
				var index = $("#select-imagen-id").val();
			    $(".div-contenedora-imagenes-articulo").append(htmlImagen(contadorImagenes,fotos[index].idFoto,fotos[index].nombreFoto));
			    $("#input-imagen-"+contadorImagenes).val(fotos[index].idFoto);
			    contadorImagenes++;
			    contadorImagenesReales++;
			    $("#hiddenContadorImagenes").val(contadorImagenesReales);
			    ajustarTamaño();
			}

			function resImagen(num){
			    $("#contenedora-imagen-"+num).css("margin",0);
			    $("#contenedora-imagen-"+num).remove();
			    contadorImagenesReales--;
			    if(contadorImagenesReales==0){
			        contadorImagenes = 0;
			    }
			    $("#hiddenContadorImagenes").val(contadorImagenesReales);
			    ajustarTamaño();
			}

			ajustarTamaño();
			function ajustarTamaño(){
				$(".div-icono-usuario").css("height",$(".div-icono-usuario").width());
				$(".dropdown-content").css("top",$(".div-usuario").height()-7);
				$(".dropdown-content").css("left",-($(".div-usuario").width()+130));
				$(".articulo").css("margin-top",$("nav").height());

				anchoImagen = $(".imagen").width();
				alturaImagen = anchoImagen*1.5;
				$(".imagen").css("height",alturaImagen);
				alturaContenedorImagen = alturaImagen + 100;
			    $(".div-contenedora-imagenes-articulo").css("height",(alturaContenedorImagen*Math.ceil((contadorImagenesReales)/4)));
			}
		</script>
	</body>
</html>