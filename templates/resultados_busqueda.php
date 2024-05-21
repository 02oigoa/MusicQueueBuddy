<?php
require_once("/var/www/html/functions/funtzioak.php");
require_once("/var/www/html/ink/konexioa.php");


$busqueda = isset($_POST['busqueda']) ? urlencode($_POST['busqueda']) : ''; // Obtener la consulta de búsqueda y codificarla para usarla en la URL

#$busqueda = "el%20conjuntito";

$tipo = "track";

$access_token = get_authentication_token();


$item_results = SearchForItem($busqueda, $tipo, $access_token);


if (!empty($item_results)) {

    echo '<div class="resultados">';

    foreach ($item_results as $tipo => $tipo_items) {
        $x = 1;
        if (isset($tipo_items['items']) && is_array($tipo_items['items'])) {

            foreach ($tipo_items['items'] as $item) {
                echo '<div class="resultados_bloque">';
                    echo '<div>';
                        echo '<form method="post">';
                            echo '<input type="submit" name="addsong" value="+" class="plus-boton"/>';
                            echo '<input type="hidden" name="uri" value="' . $item['uri'] . '">';
                        echo '</form>';
                    echo '</div>';
                    echo '<div class="irudia-izena"> ';
                        echo '<div class="image-container">';
                            $smallest_image = null;
                            foreach ($item['album']['images'] as $image) {
                                if (!$smallest_image || ($image['height'] * $image['width']) < ($smallest_image['height'] * $smallest_image['width'])) {
                                    $smallest_image = $image;
                                }
                            }
                            if ($smallest_image) {
                                echo '<img src="' . $smallest_image['url'] . '" height="' . $smallest_image['height'] . '" width="' . $smallest_image['width'] . '"><br>';
                            }
                        echo '</div>';
                        echo '<div class="text-container">';
                            $artistas = array();
                            foreach ($item['artists'] as $artista) {
                                $artistas[] = $artista['name'];
                            }
                            echo '<div class="cancion">' . $item['name'] . '</div>';

                            echo '<div class="artista">' . implode(', ', $artistas) . '</div>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div>'. $item['album']['name'] . '</div>';
                    $duracionms = $item['duration_ms'];
                    $duracion_minutos = floor($duracionms / (60 * 1000)); 
                    $duracion_segundos = floor(($duracionms % (60 * 1000)) / 1000); 

                    echo '<div>'. $duracion_minutos . ':' . $duracion_segundos . '</div>';
                 echo '</div>';
                 $x++;
            }
        }
    }
    echo '</div>';
}else{
    echo "no hay resultados";
}
