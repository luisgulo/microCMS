<?php

// Leer Directorios y Ficheros en directorio actual
//Directorio
$dir = getcwd();
$directorio = opendir($dir);

$archivos = array();
$carpetas = array();

//Carpetas y Archivos a excluir
$excluir = array('.', '..', 'index.php', 'favicon.ico');

while ($f = readdir($directorio)) {
    if (is_dir("$dir/$f") && !in_array($f, $excluir)) {
        $carpetas[] = $f;
    } else if (!in_array($f, $excluir)){
        //No es una carpeta, por ende lo mandamos a archivos
        $archivos[] = $f;
    }
}
closedir($directorio);
// Ordenamos los Directorios y Archivos obtenidos
sort($carpetas,SORT_NATURAL | SORT_FLAG_CASE);
sort($archivos,SORT_NATURAL | SORT_FLAG_CASE);

// Nos quedamos solo con fichero en formato NNN.* (jpg,png,txt)
$archivosNNN = array();
foreach ($archivos as $a) {
   $iniciofichero=substr($a,0,3);
   $punto=substr($a,3,1);
   if (is_numeric($iniciofichero) and $punto == "." and $iniciofichero > 0) {
           $archivosNNN[] = $a;
   }
}

// Nos quedamos solo con directorios en formato NNN.* (el resto se ignoran)
$carpetasNNN = array();
foreach ($carpetas as $c) {
   $iniciodir=substr($c,0,3);
   $punto=substr($c,3,1);
   if (is_numeric($iniciodir) and $punto == "." and $iniciodir > 0) {
           $carpetasNNN[] = $c;
   }
}



// ------------   FUNCIONES   -------------------

function titulo($ruta="") {
  // Leemos la primera linea de 000.txt
  $fich=fopen("./".$ruta."/000.txt","r");
  $linea=fgets($fich);
  echo $linea;
  fclose($fich);
}

function descripcion($ruta="") {
  // Leemos fichero  000.txt
  $fich=fopen("./".$ruta."/000.txt","r");
  $linea=fgets($fich);
  // echo $linea; // no imprimimos la primera linea
  while (!feof($fich)) {
	  $linea=fgets($fich);
	  $linea=str_replace("·", "<br>",$linea);
	  echo '<code>'. $linea .'</code>';
  }
  fclose($fich);
}

function texto($fichero="") {
  // Leer fichero y generar saltos de linea <br> en parrafo <p>...</p>	
  echo '<div class="row"> <blockquote class="blockquote ml-3 text-justify">';
  if ("$fichero" == "") {
     echo '<br>';
  } else {
    // Leer fichero
    $texto=file_get_contents($fichero);
    $texto='<p>' . str_replace("·", "<br>",$texto) . '</p>';
    echo $texto;	  
  }	  
  echo '</blockquote> </div>';
}

function imagen_texto($fimagen="", $ftexto="") {
  echo '<article class="row no-gutters">
        <div class="col">
            <div class="image-wrapper float-left pr-3">';	
  echo '        <img class="img-fluid thumbnail pr-2" src="' . $fimagen . '" alt=""  style="max-width:350px;">
            </div>';
  echo '<!-- <div class="single-post-content-wrapper"> --><blockquote class="blockquote text-justify">';
  // Leer fichero
  $texto=file_get_contents($ftexto);
  $texto= str_replace("·", "<br>",$texto);
  echo $texto;	  
  echo '</blockquote> <!-- </div> -->
        </div>
    </article>';
}

function imagen($fimagen="") {
   echo '<div class="row">
	  <img class="img-fluid p-2" src="' . $fimagen .'" alt="">
	</div>
        <br>';
}

function menu_navegacion() {
  echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand mayusculas" href="#">';
  titulo();
  echo '</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/"><img src="/logo.png" style="max-width:30px;">
              <span class="sr-only">(current)</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>';
}

function cuadricula_directorios() {
  global $carpetasNNN;
  echo '<div class="row text-center">';
  // Recorremos directorios y mostramos
  foreach ($carpetasNNN as $c) {
    echo ' <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
	 <a href="./' . $c . '"><img class="card-img-top" src="' . $c . '/000.png"';
    echo ' alt=""></a>
          <div class="card-body">
            <h4 class="card-title">';
    echo substr($c,4);
    echo '</h4>';
    echo '<p class="card-text">';
    //descripcion($c);
    $texto=file_get_contents($c."/000.txt");
    $texto=str_replace("..", ".<br>",$texto);
    echo $texto;
    echo '</p>';
    echo '</div>
          <div class="card-footer">
	    <a href="./' . $c . '/" class="btn btn-primary">';
    echo substr($c,4);
    echo '</a>
          </div>
        </div>
	</div> ';
  }
  echo '</div>';
}

function copyright() {  
  echo'</div>
 <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">
	<!-- Copyright --> &copy; 2017-2021<br>
         &laquo;<small>Actualizado el ' . date("d-m-Y H:i:s", filectime("000.txt")) . '</small>&raquo;
        <br><br>
	<small>
	  <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">
          <img alt="Licencia de Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a>
      </small>
      </p>
    </div>
  </footer> ';
}

function jumbotron_titulo() {  
  echo '<header class="jumbotron my-0 pl-0">  <h1 class="display-5 text-primary">';
  titulo();
  echo '</h1> <p class="lead"> ';
  descripcion();
 //echo '</h1> </header> ';
  echo '</header> ';
}

function inicio_contenedor() {  
  echo '<div class="container">';
}

function bucle_textos() {
  global $archivosNNN;
  // Recorrer array de archivosNNN.*
   $numfich = count($archivosNNN);
   for ($i =1 ; $i <= $numfich; $i=$i+1) {
      // miro el fichero actual y el siguiente
      $iniant=substr($archivosNNN[$i-1],0,3);
      $iniact=substr($archivosNNN[$i],0,3);
      if ($iniant == $iniact ) {
              // echo "funcion imagen_texto --> " .$archivosNNN[$i-1] . " " . $archivosNNN[$i] ."<br>";
	      imagen_texto($archivosNNN[$i-1],$archivosNNN[$i]);
              $i=$i+1;
      } else {
              if ( substr($archivosNNN[$i-1], -3) == "txt") {
                      // echo "funcion texto --> " .$archivosNNN[$i-1] . "<br>";
		      texto($archivosNNN[$i-1]);
              } else {
                      // echo "funcion imagen --> " .$archivosNNN[$i-1] . "<br>";
		      imagen($archivosNNN[$i-1]);
              }
      }
   }
}

function indice() {
  //global $carpetas;
  // Menu de navegacion
  menu_navegacion();
  // Inicio contenedor pagina
  inicio_contenedor();
  // Jumbotron del titulo pagina
  jumbotron_titulo();
  // Bucle para generar la Cuadricula de directorios
  cuadricula_directorios();
  // Copyrigth
  copyright();
  // Cierre de contenedor en <html>
}

function contenido() {
  // Menu de navegacion	
  menu_navegacion();
  // Inicio de contenedor
  inicio_contenedor();
  // Jumbotron del titulo pagina
  jumbotron_titulo();
  // Bucle para cargar todos los textos e imagenes...
  bucle_textos();
  // Copyright
  copyright();
  // Cierre de contenedor en <html>
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" type="image/png" href="/logo.png">
  <title><?php titulo(); ?></title>

  <!-- Bootstrap core CSS -->
  <link href="/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
	body {
	  padding-top: 56px;
	}
       .jumbotron{
          /* color: #FFFFFF; */
          background-color:white !important;
       }
       .minusculas{
	text-transform:lowercase;
       }
       .mayusculas{
	text-transform:uppercase;
       }	
    </style>

</head>
<body>

<?php
// Si no existe fichero 001.* se trata de pagina de tipo INDICE
$fichBuscar = "001";
$resultadoBuscar = array_filter($archivos, function($var) use ($fichBuscar) {return stristr($var, $fichBuscar); });
if ( ! $resultadoBuscar) {
	indice();
} else {
	contenido();
}

?>
<!-- </div> -->
  <!-- Bootstrap core JavaScript -->
  <script src="/css/jquery/jquery.min.js"></script>
  <script src="/css/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Micro CMS creado por LuisGulo -->
</body>
</html>
