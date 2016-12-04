<?php

// # Importa los datos de un archivo de excel o csv a una tabla
// importData(String tabName, String filePath) {
//     ...
//     return Boolean done;
// } 

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBNAME', 'funciones_emerson');


$mysqli = new mysqli(HOST, USER, PASS, DBNAME);

if ($mysqli->connect_errno) {

    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}
function showErrors(){
	global $mysqli;
    echo "Error: La ejecución de la consulta falló debido a: \n";
    echo "Errno: " . $mysqli->errno . "\n";
    echo "Error: " . $mysqli->error . "\n";
    exit;
}
function doQuery($sql){
	global $mysqli;
	if (!$resultado = $mysqli->query($sql)) {
		showErrors();
	}
	return $resultado;	
}
function getColumns($tabName){
	global $mysqli;
	$sql = "SHOW columns FROM $tabName";
	if (!$resultado = $mysqli->query($sql)) {
		showErrors();
	}
	$header = array();
	foreach ($resultado->fetch_all(MYSQLI_BOTH) as $key => $value) {
		$header[]=ucwords($value[0]);
	}
	return $header;
	
}
// # Lista y filtra una tabla
function getList($tabName, $filterName, $filterValue) {
    global $mysqli;
    $header=getColumns($tabName);
    
    $sql="SELECT * FROM $tabName WHERE $filterName='$filterValue'";
	$resultado=doQuery($sql);
	$noColumnas = count($header);
	$html='<thead>';
	for ($i=0; $i < count($header) ; $i++) { 
		$html.="<th>".$header[$i]."</th>";
	}

	$html.='</thead>';
	
	foreach ($resultado->fetch_all(MYSQLI_BOTH) as $key => $value) {
		
		$html.='<tr>';
		for ($j=0; $j < $noColumnas ; $j++) { 
			
			$html.="<td>".$value[$j]."</td>";
		}
		$html.='</tr>';
	}	

    return "<table class='analyst-list'>".$html."</table>";
}
# Adiciona una fila a una tabla
function addRow($tabName, $tabData) {
	global $mysqli;
	$keys = "(".implode(", ",array_keys($tabData)).")";
	$values = "'".implode("','",array_values($tabData))."'";
	
	$sql = "INSERT INTO $tabName $keys VALUES ($values)";
	if (!$mysqli->query($sql)) {
		showErrors();
	}
    return true;
}
# Edita una fila de una tabla
function editRow($tabName, $id, $tabData) {
	global $mysqli;
	
	$update='';
	foreach ($tabData as $key => $value) {
		$update.= $key."='".$value."', ";
	}
	$num=2;
	$update = substr($update, 0, -$num);	
	$sql = "UPDATE $tabName SET $update WHERE id='$id'";

	if (!$mysqli->query($sql)) {
		showErrors();
	}
    return true;
}
// # Elimina una fila de una tabla
function rmRow($tabName, $id) {
	global $mysqli;
	
	$sql = "DELETE FROM $tabName  WHERE id='$id'";

	if (!$mysqli->query($sql)) {
		showErrors();
	}
    return true;
}
// var_dump(editRow('usuario','2',array('username'=>'Jaime 3','nombre'=>'Jaime Irazabal 2','cedula'=>'16923509 2')));
rmRow('usuario',2);
echo getList('usuario','username','tayme');

?>