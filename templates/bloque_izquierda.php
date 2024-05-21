

<div class="bloque_menu_izquierda" >

    <?php
    include("ink/konexioa.php");
    require_once("functions/funtzioak.php");

    if(isset($_GET['user_id'])){

        echo '<div class="arriba-queue">';
            echo '<div class="header_queue" style="text-align: center;">';
                $user_id = $_GET['user_id'];
                echo "<br>";
                echo $user_id;
            echo '</div>';
            echo '<hr>';

            $queue = get_user_queue($user_id, $conn);


            if($queue){
                if(!empty($queue['currently_playing']['name'])){
                    echo '<div class="header_queue">';
                        echo "Sonando: <br><br>";
                        $cancion = $queue['currently_playing']; 

                        echo '<div class="resultados_bloque_queue">';
                            echo '<div class="irudia-izena"> ';
                                echo '<div class="image-container">';
                                    $smallest_image = null;
                                    foreach ($cancion['album']['images'] as $image) {
                                        if (!$smallest_image || ($image['height'] * $image['width']) < ($smallest_image['height'] * $smallest_image['width'])) {
                                            $smallest_image = $image;
                                        }
                                    }
                                    if ($smallest_image) {
                                        echo '<img src="' . $smallest_image['url'] . '" height="' . $smallest_image['height']/2 . '" width="' . $smallest_image['width']/2 . '"><br>';
                                    }
                                echo '</div>';

                                echo '<div>';
                                    echo '<div class="text-container">';
                                        $artistas = array();
                                        foreach ($cancion['artists'] as $artista) {
                                            $artistas[] = $artista['name'];
                                        }
                                        echo '<div class="cancion-queue">' . $cancion['name'] . '</div>';

                                        echo '<div class="artista-queue">' . implode(', ', $artistas) . '</div>';

                                    echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                        echo "<hr>";

                        echo "Cola de canciones: <br><br>";
                    echo '</div>';
                }else{
                    echo "Spotify no se esta ejectuando, abre spotify para conseguir tu cola";
                }
            }
        echo '</div>';
        
        if($queue){
            if(!empty($queue['currently_playing']['name'])){
                echo '<div>';
                
                    foreach ($queue['queue'] as $cancion) {

                        echo '<div class="resultados_bloque_queue">';
                            echo '<div class="irudia-izena"> ';
                                echo '<div class="image-container">';
                                    $smallest_image = null;
                                    foreach ($cancion['album']['images'] as $image) {
                                        if (!$smallest_image || ($image['height'] * $image['width']) < ($smallest_image['height'] * $smallest_image['width'])) {
                                            $smallest_image = $image;
                                        }
                                    }
                                    if ($smallest_image) {
                                        echo '<img src="' . $smallest_image['url'] . '" height="' . $smallest_image['height']/2 . '" width="' . $smallest_image['width']/2 . '"><br>';
                                    }
                                echo '</div>';

                                echo '<div>';
                                    echo '<div class="text-container">';
                                        $artistas = array();
                                        foreach ($cancion['artists'] as $artista) {
                                            $artistas[] = $artista['name'];
                                        }
                                        echo '<div class="cancion-queue">' . $cancion['name'] . '</div>';

                                        echo '<div class="artista-queue">' . implode(', ', $artistas) . '</div>';


                                    echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo "<br>";
                    }
                echo '</div>';
            }

        }



    }else{
        echo '<div style="text-align: center;">';
            echo '<a href="login.php" > Login </a>';
        echo '</div>';
    }



    ?>

</div>


