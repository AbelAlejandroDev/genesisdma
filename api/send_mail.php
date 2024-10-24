<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php'; // Cargar las dependencias de Composer

header("Access-Control-Allow-Origin: *"); // Permitir todas las solicitudes de cualquier origen
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Permitir solo POST, GET, y OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permitir encabezados específicos

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['email']) || !isset($data['phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$phone = $data['phone'];
$business = isset($data['bussines']) ? $data['bussines'] : 'No especificado';

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV['SMTP_PORT'];

    // Enviar correo al usuario con el archivo adjunto
    $mail->setFrom($_ENV['FROM_EMAIL'], $_ENV['FROM_NAME']);
    $mail->addAddress($email);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Guía Gratis: Optimización de Perfil de Google';
    $mail->addEmbeddedImage('../public/logo.png', 'company_logo');
    $mail->Body = "
        <p><img src='cid:company_logo' alt='Logo de la Empresa' style='width: 100px; height: auto;'></p>
        <p>Estimado/a $name,</p>
        <p>Espero este mensaje te encuentre bien.</p>
        <p>En GenesisDMA, estamos comprometidos con el éxito de tu negocio. Por eso, hemos creado una guía completa sobre cómo optimizar tu perfil de Google, una herramienta esencial para mejorar tu presencia en línea y atraer más clientes. Puedes descargar la guía en el archivo adjunto.</p>
        <p>Además, me gustaría invitarte a agendar una cita conmigo para revisar y mejorar tus procesos de obtención de clientes. Juntos podemos:</p>
        <ul>
            <li>Reducir costos y aumentar ganancias.</li>
            <li>Crear y legalizar tu negocio desde cero.</li>
            <li>Obtener el capital y los permisos necesarios.</li>
        </ul>
        <p>Para agendar una cita, simplemente responde a este correo o visita nuestro sitio web <a href='http://www.genesisdma.com'>www.genesisdma.com</a>.</p>
        <p>Estoy aquí para ayudarte a alcanzar el éxito que tu negocio merece.</p>
        <p>Saludos cordiales,</p>
        <p>{$_ENV['FROM_NAME']}</p>
        <p>GenesisDMA</p>
        <p>{$_ENV['FROM_PHONE']}</p>
        <p>{$_ENV['FROM_EMAIL']}</p>
    ";
    $mail->addAttachment('../public/Google Bussiness.pdf', 'guide.pdf');
    $mail->send();

    // Enviar correo con los detalles del formulario a FROM_EMAIL
    $mail->clearAddresses();
    $mail->addAddress($_ENV['FROM_EMAIL']);
    $mail->Subject = 'Nuevo Formulario de Contacto Recibido';
    $mail->Body = "
        <p>Se ha recibido un nuevo formulario de contacto con los siguientes detalles:</p>
        <p><strong>Nombre:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Teléfono:</strong> $phone</p>
        <p><strong>Negocio:</strong> $business</p>
        <p>Por favor, revisa y responde a la consulta según sea necesario.</p>
    ";
    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Emails sent']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
} catch (Throwable $t) {
    echo json_encode(['status' => 'error', 'message' => "An unexpected error occurred: {$t->getMessage()}"]);
}
