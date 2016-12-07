<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// # Importa los datos de un archivo de excel o csv a una tabla
// importData(String tabName, String filePath) {
//     ...
//     return Boolean done;
// } 

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBNAME', 'funciones_emerson');

$templatesTableName = "templates";

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
function getListArray($tabName, $filterName, $filterValue) {
    global $mysqli;

    $header=getColumns($tabName);
    
    $sql="SELECT * FROM $tabName WHERE $filterName='$filterValue'";
	$resultado=doQuery($sql);
	return $resultado->fetch_all(MYSQLI_BOTH);
	/*$noColumnas = count($header);
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

    return "<table class='analyst-list'>".$html."</table>";*/
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
//hacer un csv de una tabla
/*
	Primer parametro el nombre de la tabla en la base de datos
	Segundo parametro el nombre del archivo csv, es opcional
*/
function exportData($tabName,$csvName=NULL){
	global $mysqli;

	if (!$csvName) {
		$csvName = $tabName;
	}
	$list[] = getColumns($tabName);
	$list_ = $list;

	$sql = "SELECT * from $tabName";

	if (!$resultado = $mysqli->query($sql)) {
		showErrors();
	}
	for ($i=0; $i < count($list_) ; $i++) { 
		foreach ($resultado->fetch_all(MYSQLI_NUM) as $key => $value) {
			$list[]=$value;
			
		}
	}
	$file = fopen($csvName.".csv","w");

	foreach ($list as $line)
	{
		fputcsv($file,$line,";");
	}

	fclose($file);
}
/*
	esta funcion toma los datos de un csv que se pasa en el segundo parametro
	genera una tabla con el nombre que se le pasa en el primer parametro
	$tabName -> nombre de la tabla a crear.
	$filePath -> archivo .csv de donde se tomaran los datos.
*/
function importData($tabName, $filePath) 
{
	global $mysqli;
	if (!file_exists($filePath)) {
		die("Error: El archivo ".$filePath." No existe!");
	}
	$data = array();
	if (($gestor = fopen($filePath, "r")) !== FALSE) {
		while ($datos = fgetcsv($gestor, 1000, ";")) {
			$data[]=$datos;
		}
	    
	    fclose($gestor);
	}	
	$create="CREATE TABLE IF NOT EXISTS `$tabName` (
	  `id` int(8) NOT NULL AUTO_INCREMENT,
	  `name` varchar(100) NOT NULL,
	  `author` varchar(30) NOT NULL,
	  `isbn` varchar(30) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

	$insert = "INSERT INTO $tabName (";

	$create="CREATE TABLE IF NOT EXISTS `$tabName` (";
	for ($i=0; $i < count($data[0]) ; $i++) { 
		if ($i==count($data[0])-1) {
			$insert.=strtolower($data[0][$i]." )");
			$create.=" `".$data[0][$i]."` varchar(200)";
		}else{
			$insert.=strtolower($data[0][$i].",");

			$create.=" `".$data[0][$i]."` varchar(200),";
		}
	}
	$create.=") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

	$insert.=" VALUES ";
	for ($j=1; $j < count($data); $j++) { 
		if ($j != 1) {
			# code...
			$insert.=", ( ";

		}else{

			$insert.=" ( ";
		}
		for ($i=0; $i < count($data[$j]) ; $i++) { 
			if ($i==count($data[$j])-1) {
				$insert.="
				'".strtolower($data[$j][$i]."' )");
				//$create.=" `".$data[$j][$i]."` varchar(200)";
			}else{
				$insert.="
				'".strtolower($data[$j][$i]."',");

				//$create.=" `".$data[$j][$i]."` varchar(200),";
			}
		}
	}
	
	if (!$mysqli->query($create)) {
		showErrors();
	}


	
	if (!($mysqli->query($insert))) {
	    echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
	    die;
	};

	return true;
}


/*
	crea una tabla si no existe para guardar los templates de los clientes
*/
function createTableToTemplates(){

	global $mysqli,$templatesTableName;

	$create="CREATE TABLE IF NOT EXISTS `$templatesTableName` (
	  `id` int(8) NOT NULL AUTO_INCREMENT,
	  `clienteName` varchar(100) NOT NULL,
	  `method` text NOT NULL,
	  `created` TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";	

	if (!$mysqli->query($create)) {
		showErrors();
	}
	return true;
}
/*
	function para guardar la plantilla con la información en method

*/
function saveTemplateInDb($clientName,$method){

	global $mysqli,$templatesTableName;

	$sql = "INSERT INTO $templatesTableName (clienteName,method) VALUES ('$clientName','$method')";

	if (!$mysqli->query($sql)) {
		showErrors();
	}
	return true;

}
# Genera y guarda una plantilla de reporte
function saveReportTemplate($logo1=null,$logo2=null, $title=null,
	$subtitle=null, $frt=null, $notes=null,
	$firmText1=null, $firmText2=null,$botInfo=null,
	$footer=null) {

	saveTemplateInDb(time(),"$logo1;-;$logo2;-;$title;-;$subtitle;-;$frt;-;$notes;-;$firmText1;-;$firmText2;-;$botInfo;-;$footer");

}
function getTemplates(){

	global $mysqli,$templatesTableName;

	$sql = "SELECT * FROM $templatesTableName order by id asc";

	$result = $mysqli->query($sql);

	$rows = $result->fetch_all(MYSQLI_ASSOC);
	
	return $rows;
	
}
/*
	function para imprimir en html un reporte
*/
function printReportTemplate($logo1=null,$logo2=null, $title=null,
	$subtitle=null, $frt=null, $notes=null,
	$firmText1=null, $firmText2=null,$botInfo=null,
	$footer=null){

	global $results, $clientData,$encabezado;

		?>
	<style type="text/css">
		body{
			margin:auto 5% ;
		}
	</style>
	<table class="table" cellspacing="0" width="100%">
		<thead>
			<td width="50%">
				<?php if ($logo1 and file_exists($logo1)): ?>
					<img width="300" height="100" src="<?php echo $logo1 ?>" alt="">
				<?php else: ?>
					<img src="" alt="IMAGEN NO ENCONTRADA">
				<?php endif ?>
			</td>
			<td width="50%" style="text-align: right;">
				<?php if ($logo2 and file_exists($logo2)): ?>
					<img width="200" height="50" src="<?php echo $logo2 ?>" alt="">
				<?php else: ?>
					<img src="" alt="IMAGEN NO ENCONTRADA">
				<?php endif ?>				
			</td>
		</thead>
		
		
	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 33%"></td>
			<td style="width: 33%;text-align: center"><?php echo strtoupper($title) ?></td>
			<td style="width: 33%"></td>
		</tr>
	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 50%"></td>
			<td style="width: 50%;text-align: center"><?php echo strtoupper($subtitle) ?></td>
		</tr>

	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 25%"></td>
			<td style="width: 25%"></td>
			<td style="width: 25%"></td>
			<td style="width: 25%;text-align: center;font-size: 10px"><?php echo strtoupper($frt) ?></td>
		</tr>
	</table>
	<?php echo $encabezado ?>
	<?php echo $clientData ?>
	<?php echo $results ?>

	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 100%">
				<p style="text-align: justify;padding: 10px;font-size: 14px">
					<?php echo $notes ?>
				</p>
			</td>
		</tr>
	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 50%;text-align: center"><?php echo strtoupper($firmText1) ?></td>
			<td style="width: 50%;text-align: center"><?php echo strtoupper($firmText2) ?></td>
		</tr>

	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 100%">
				<p style="text-align: justify;padding: 10px">
					<?php echo $botInfo ?>
				</p>
			</td>
		</tr>
	</table>
	<table class="table" cellspacing="0" width="100%">
		<tr>
			<td style="width: 100%">
				<p style="text-align: center;padding: 10px">
					<?php echo $footer ?>
				</p>
			</td>
		</tr>
	</table>
		<?php	
}
# Obtiene la tabla Resultados en html 
# como aparece en la imágen de los ejemplos
/*results = Array[
	[parameter=>"", method=>"", ref=>"", res=>"", date=>""],
	[parameter=>"", method=>"", ref=>"", res=>"", date=>""],
	...
]*/
function getResultData($results) {
	if ($results) {
		$table = "<h3>Resultados:</h3>";
		$table .= "<table class='analyst-resultData' cellspacing='0' border='1' width='100%'>
			<thead>
				<th>Parámetro</th>
				<th>Método</th>
				<th>Referencia</th>
				<th>Resultados</th>
				<th>Fecha</th>
			</thead>";
			foreach ($results as $key => $value):
				$table .= "<tr>
					<td>".$value['parameter']."</td>
					<td>".$value['method']."</td>
					<td>".$value['ref']."</td>
					<td>".$value['res']."</td>
					<td>".$value['date']."</td>
				</tr>";
			endforeach;
		$table .= "</table>";

		return $table;
	}
}
function encabezado($code=null,$fechaRecepcion=null){


	$table = "<table class='' cellspacing='0' border='1'>
		<tr>
			<td><b>Código</b>: </td>
			<td>$code</td>
		</tr>
		<tr>
			<td><b>Fecha y hora de Recepción: </b></td>
			<td>$fechaRecepcion</td>
		</tr>

	</table>";

	return $table;	
}
# Obtiene la tabla información suministrada por el cliente en html 
# como aparece en la imágen de los ejemplos
# Estado del tiempo(weather), Tipo de Muestra(sampleType), ...
function getClientData($weather = null, $sampleType = null, $pH = null, 
	$requestedBy = null, $source = null, $sampledBy = null,
	$samplingSite = null, $samplingDate = null){

	$table = "<h4>INFORMACIÓN SUMINISTRADA POR EL CLIENTE</h4>";
	$table .= "<table class='analyst-clientData' width='100%' cellspacing='0' border='1'>
		<tr>
			<td>Estado del tiempo: </td>
			<td>$weather</td>
			
		</tr>
		<tr>
			<td><b>Tipo de muestra: </b></td>
			<td>$sampleType</td>
			<td><b>Procedencia: </b></td>
			<td>$source</td>
		</tr>
		<tr>
			<td><b>pH (U, pH): </b></td>
			<td>$pH</td>	
			<td><b>Fecha y hora de toma:</b></td>
			<td>$samplingDate</td>
		</tr>
		<tr>
			<td></td>
			<td></td>	
			<td><b>Muestreado por:</b></td>
			<td>$sampledBy</td>
		</tr>
		<tr>
			<td><b>Solicitado por : </b></td>
			<td>$requestedBy</td>	
			<td><b>Sitio de Muestreo:</b></td>
			<td>$samplingSite</td>
		</tr>
	</table>";

	return $table;
	
}

function cambiarPass($newPass,$id,$tabName="users",$passwordColumn="passwd"){

	global $mysqli;

	$sql = "UPDATE $tabName set $passwordColumn='$newPass' where id = '$id'";

	if (!$mysqli->query($sql)) {
		showErrors();
	}
	return true;

}