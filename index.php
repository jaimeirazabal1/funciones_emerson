<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Funciones de Emerson</title>
	<?php require_once("functions.php") ?>
</head>
<body>
<?php 
$results = [
	['parameter'=>"", 'method'=>"", 'ref'=>"", 'res'=>"", 'date'=>""],
	['parameter'=>"", 'method'=>"", 'ref'=>"", 'res'=>"", 'date'=>""]
]
 ?>
	<?php echo saveReportTemplate("logo1.png",
									"logo2.png",
									"TITULO",
									"LABORATORIO DE SERVICIOS - ANALISIS DE AGUAS",
									"AQUI OTRO TEXTO",
									"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem ab amet, beatae ratione voluptatibus! Eius dolor a non pariatur aperiam commodi velit voluptatem. Voluptates, numquam eos tenetur consequuntur consequatur nobis.",
									"FIRMA1",
									"FIRMA2",
									"",
									"FOOTER",
									"",
									$results); ?>
</body>
</html>