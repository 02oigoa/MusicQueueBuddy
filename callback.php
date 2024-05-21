<?php
include("ink/konexioa.php");
require_once("functions/funtzioak.php");

$client_id = 'your_id';
$client_secret = 'your_secret';
$redirect_uri = 'http://localhost:8082/callback.php'; //Change this to your spotify api project Redirect URI

if (isset($_GET['code'])) {

    $code = $_GET['code'];

    $data = array(
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    );

    $ch = curl_init('https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);

    // Procesar la respuesta (la respuesta es un JSON)
    $token_data = json_decode($response, true);


    if (!empty($token_data)) {
        $access_token = $token_data['access_token'];
        $refresh_token = $token_data['refresh_token'];
        $expires_at = time() + $token_data['expires_in'];
    
        $user_id = mysqli_real_escape_string($conn, get_user_id($token_data['access_token']));
        $refresh_token = mysqli_real_escape_string($conn, $refresh_token);
        $expires_at = mysqli_real_escape_string($conn, $expires_at);
    
        $galdera = "INSERT INTO users (user_id, access_token, refresh_token, expires_at)
                    VALUES ('$user_id', '$access_token', '$refresh_token', '$expires_at')
                    ON DUPLICATE KEY UPDATE
                    access_token = VALUES(access_token),
                    refresh_token = VALUES(refresh_token),
                    expires_at = VALUES(expires_at)";

        if (mysqli_query($conn, $galdera)) {
            header('Location: index.php?user_id=' . urlencode($user_id));
            exit();
        } else {
            $error_message = "Error al insertar o actualizar el registro: " . mysqli_error($conn);
        }
    } else {
        echo "Error: No se pudo obtener el token de acceso";
    }
    

    echo '<a href="index.php"> inicio </a>';



} else {
    echo "algo ha ido mal";
    echo "<br><br>";
    echo '<a href="index.php"> inicio </a>';


}

