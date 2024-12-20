<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secretKey = "6LfMqIAqAAAAAJhMLFbuuR9h8pAvdG4CED9VHKCz"; // Clave secreta
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Validar reCAPTCHA con Google
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $responseKey,
        'remoteip' => $userIP
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        echo "Validación exitosa. ¡Formulario enviado!";
    } else {
        echo "Error en la validación de reCAPTCHA. Intenta nuevamente.";
    }
}
?>
