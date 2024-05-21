<?php
// Configuración de la aplicación
$client_id = 'your_id';
$redirect_uri = 'http://localhost:8082/callback.php'; //Change this to your spotify api project Redirect URI

// Construir la URL de autorización de Spotify
$authorize_url = 'https://accounts.spotify.com/authorize?' . http_build_query(array(
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => 'user-read-private user-read-email user-read-playback-state user-read-currently-playing user-modify-playback-state',
    // Puedes agregar más parámetros según sea necesario, como el estado
));

// Redirigir al usuario a la página de autorización de Spotify
header('Location: ' . $authorize_url);
exit();
