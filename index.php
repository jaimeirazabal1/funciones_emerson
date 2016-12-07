<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Funciones de Emerson</title>
	<?php require_once("functions.php") ?>
</head>
<body>
<?php 
/*se crea la tabla de la base de datos*/
createTableToTemplates();
/*con esto se obtienen los templates guardados en la tabla de la base de datos*/
$templates = getTemplates();

/*esta es la estructura de deben tener los resultados*/
$results = [
	['parameter'=>"PARAMETRO 1", 'method'=>"METODO 1", 'ref'=>"REFERENCIA 1", 'res'=>"RESULTADO 1", 'date'=>"FECHA 1"],
	['parameter'=>"PARAMETRO 2", 'method'=>"METODO 2", 'ref'=>"REFERENCIA 2", 'res'=>"RESULTADO 2", 'date'=>"FECHA 2"],
	['parameter'=>"PARAMETRO 3", 'method'=>"METODO 3", 'ref'=>"REFERENCIA 3", 'res'=>"RESULTADO 3", 'date'=>"FECHA 3"],

];
/*esos resultados deben pasarte a esta función*/
$results = getResultData($results);
$clientData = getClientData("Verano", "Residual", "PHas", 
	"Jaime Irazabal", "Emerson Potes", "Jorge Rodriguez",
	"ARD Etapa 3", "07/12/2016 11:22");
/*
	esta funcion es para generar el encabezado donde va el codigo y fecha
*/
$encabezado = encabezado("165165156","07/12/2016 11:25");
/*con esto se llama la funcion para imprimir el template en html pasandole los parametros guardados
en la base de datos que mostrara*/
call_user_func_array("printReportTemplate", explode(";-;",$templates[0]['method']) );

/*esta funcion crea una plantilla y la guarda en la base de datos*/
/*se comentó para no guardar siempre la misma información*/

 /*echo saveReportTemplate("logo1.png",
									"logo2.png",
									"TITULO",
									"LABORATORIO DE SERVICIOS - ANALISIS DE AGUAS",
									"AQUI OTRO TEXTO",
									"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.",
									"FIRMA1",
									"FIRMA2",
									"",
									"FOOTER"
									); */
	?>
</body>
</html>