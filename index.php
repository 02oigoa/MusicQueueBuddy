<?php

require_once("/var/www/html/functions/funtzioak.php");
require_once("/var/www/html/ink/konexioa.php");

if (isset($_POST['addsong'])) { 
    $user_id = $_GET['user_id'];
    $addsong = add_song_queue($_POST['uri'], $user_id, $conn);
    unset($_POST['addsong']);
    unset($_POST['uri']);
    header("Location: index.php?user_id=" . $user_id);
    exit;
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify group queue</title>
</head>

<body>

    <link rel="stylesheet" href="../css/styles.css" />


    <div class="container">

        <?php        
        require("templates/bloque_izquierda.php");

        require("templates/bloque_central.php");
        ?>


    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.busquador input[type="text"]').on('input', function(){
                var busqueda = $(this).val();
                if(busqueda.length >= 3) { // Solo enviar la búsqueda si tiene al menos 3 caracteres
                    $.ajax({
                        url: 'templates/resultados_busqueda.php',
                        type: 'POST',
                        data: { busqueda: busqueda },
                        success: function(response) {
                            $('.resultados').html(response);
                        }
                    });
                }
            });
        });
    </script>


</body>
</html>