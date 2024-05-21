<?php

function get_authentication_token() {
    $client_id = 'your_id';
    $client_secret = 'your_secret';
    $url = 'https://accounts.spotify.com/api/token';
    $data = array(
        'grant_type' => 'client_credentials',
        'client_id' => $client_id,
        'client_secret' => $client_secret
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $response_data = json_decode($response, true);
    $access_token = $response_data['access_token'];

    // Añadir registro al log
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_authentication_token. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    return $access_token;
}

function get_refreshed_token($refresh_token) {
    $client_id = 'your_id';
    $client_secret = 'your_secret';
    $auth_string = base64_encode($client_id . ':' . $client_secret);
  
    $url = 'https://accounts.spotify.com/api/token';
  
    $data = array(
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
    );
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . $auth_string
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_refresh_token. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    // Verifica si la solicitud fue exitosa
    if ($http_status == 200) {
        // Decodifica la respuesta JSON
        $response_data = json_decode($response, true);
        return $response_data;

    } else {
        // Manejo de errores si la solicitud no es exitosa
        return false;
    }

}

//mira si el token sirve y sino lo renueva, siempre que no de error devuelve un token valido
function check_token($user_id, $conn) {

    $galdera = "SELECT access_token, refresh_token FROM users WHERE user_id = '$user_id' ";
    $erantzuna = mysqli_query($conn, $galdera);

    if ($ema = mysqli_fetch_assoc($erantzuna)) {
        $access_token = $ema['access_token'];
        $refresh_token = $ema['refresh_token'];
    } else {
        // Tamo jodido
        echo "Error: No se pudo obtener el token de acceso de la base de datos";
        return false;
    }

    // Realizar una solicitud a una URL protegida utilizando el token de acceso
    $url = 'https://api.spotify.com/v1/me';
    $headers = array('Authorization: Bearer ' . $access_token);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: chec_token. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    // Si se recibe un código de estado 401, el token ha expirado
    if ($http_status == 401) {

        $new_token = get_refreshed_token($refresh_token);
        
        if($new_token){
            $new_access_token = $new_token['access_token'];
            
            $consulta = "UPDATE users SET access_token = '$new_access_token' WHERE user_id = '$user_id'";
            $update_token = mysqli_query($conn, $consulta);
            
            if($update_token){
                return $new_access_token;
            }else{
                echo "error al actualizar la base de datos con el nuevo token";
                return false;
            }
        }
    
    } else {
        return $access_token;
    }
}

function get_artist($artist_id, $access_token) {
    
    $url = 'https://api.spotify.com/v1/artists/' . $artist_id;

    // Configura los encabezados de la solicitud
    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );

    $curl = curl_init();

    //url-a konfiguratu
    curl_setopt($curl, CURLOPT_URL, $url);

    // Configura la solicitud HTTP GET
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    // Establece los encabezados de la solicitud
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


    // Curl-ak erantzuna bueltatzeko imprimatu beharrean
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // solizitudea exekutatu
    $response = curl_exec($curl);

    // curl konexioa itxi
    curl_close($curl);

    // JSON erantzuna deskodifikatu
    $response_data = json_decode($response, true);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_artist. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    //acces tokena atera
    $artist_name = $response_data['name'];

    return $response_data;
}

function SearchForItem($busqueda, $tipo, $access_token) {
    
    $url = 'https://api.spotify.com/v1/search?q='.$busqueda.'&type='.$tipo.'&market=ES&limit=30&offset=0';


    // Configura los encabezados de la solicitud
    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );

    $curl = curl_init();

    //url-a konfiguratu
    curl_setopt($curl, CURLOPT_URL, $url);

    // Configura la solicitud HTTP GET
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    // Establece los encabezados de la solicitud
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


    // Curl-ak erantzuna bueltatzeko imprimatu beharrean
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // solizitudea exekutatu
    $response = curl_exec($curl);

    // curl konexioa itxi
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: SearchForItem. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    // JSON erantzuna deskodifikatu
    $item_results = json_decode($response, true);

    return $item_results;
}

function get_user_id($access_token){
    $url = 'https://api.spotify.com/v1/me';

    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );

    $curl = curl_init();

    //url-a konfiguratu
    curl_setopt($curl, CURLOPT_URL, $url);

    // Configura la solicitud HTTP GET
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    // Establece los encabezados de la solicitud
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


    // Curl-ak erantzuna bueltatzeko imprimatu beharrean
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // solizitudea exekutatu
    $response = curl_exec($curl);

    // curl konexioa itxi
    curl_close($curl);

    // JSON erantzuna deskodifikatu
    $response_data = json_decode($response, true);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_user_id. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    //acces tokena atera
    $user_id = $response_data['id'];

    return $user_id;
}

function get_user_queue($user_id, $conn, $retry = true) {

    $access_token = check_token($user_id, $conn);

    $url = 'https://api.spotify.com/v1/me/player/queue';
    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_user_queue. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    $response_data = json_decode($response, true);
    return $response_data;

}

function get_device_id($user_id, $conn){

    $access_token = check_token($user_id, $conn);

    $url = 'https://api.spotify.com/v1/me/player/devices';
    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: get_device_id. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    $response_data = json_decode($response, true);

    foreach ($response_data['devices'] as $device) {
        if ($device['is_active'] == true) { 
            $device_id = $device['id']; 
            break; 
        }
    }
    
    if ($device_id !== false) {
        return $device_id;
    } else {
        echo "no devices conected";
        return false;
    }

}

function add_song_queue($song, $user_id, $conn){

    $access_token = check_token($user_id, $conn);
    $device_id = get_device_id($user_id, $conn);

    $url = 'https://api.spotify.com/v1/me/player/queue?uri=' . $song . '&device_id=' . $device_id;
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtiene el código de respuesta HTTP
    $log_message = "[" . date('Y-m-d H:i:s') . "] - Se obtuvo el token de autenticación: add_song_queue. STATUS: " . $http_status . "\n";
    file_put_contents('/var/log/apache2/log.txt', $log_message, FILE_APPEND);

    // Verifica si la solicitud fue exitosa
    if ($http_status == 200) {
        // Decodifica la respuesta JSON
        $response_data = json_decode($response, true);
        return $response_data;

    } else {
        // Manejo de errores si la solicitud no es exitosa
        return false;
    }

}