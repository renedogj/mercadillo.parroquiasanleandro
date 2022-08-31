<?php
$idArticulo = $_POST['idArticulo'];
include_once "../../db/db.php";

$sql="SELECT id, nombre, descripcion, precio, mostrar, disponibilidad FROM articulos WHERE id = $idArticulo";

$articulo = obtenerArraySQL($conexion, $sql)[0];

$sql = "SELECT id, nombre_foto
	FROM relacion_producto_imagen
	LEFT JOIN imagenes on relacion_producto_imagen.ID_imagen = imagenes.ID
	WHERE relacion_producto_imagen.ID_producto = $idArticulo";

$articulo["imagenes"] = obtenerArraySQL($conexion, $sql);

$sql = "SELECT id, nombre_categoria
	FROM relacion_producto_categoria
	LEFT JOIN categorias on relacion_producto_categoria.ID_categoria = categorias.ID
	WHERE relacion_producto_categoria.ID_producto = $idArticulo";

$articulo["categorias"] = obtenerArraySQL($conexion, $sql);

echo json_encode($articulo);
?>