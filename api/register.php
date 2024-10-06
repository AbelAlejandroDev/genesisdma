<?php

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data_file = '../db/inscriptions.json';
$max_count = 100;

function readData($data_file)
{
    if (!file_exists($data_file)) {
        file_put_contents($data_file, json_encode(['count' => 0, 'inscriptions' => []]));
    }
    $data = json_decode(file_get_contents($data_file), true);
    return $data;
}

function writeData($data_file, $data)
{
    file_put_contents($data_file, json_encode($data));
}

$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;

if (!$email || !$phone | !$name) {
    echo json_encode(['success' => false, 'message' => 'Nombre, Correo y teléfono son requeridos']);
    exit;
}

$data = readData($data_file);
$count = $data['count'];
$inscriptions = $data['inscriptions'];


foreach ($inscriptions as $inscription) {
    if ($inscription['email'] === $email || $inscription['phone'] === $phone) {
        echo json_encode(['success' => false, 'message' => 'No puedes volverte a inscribir.']);
        exit;
    }
}

if ($count >= $max_count) {
    echo json_encode(['success' => false, 'message' => 'Límite de inscripciones alcanzado']);
    exit;
}

$data['inscriptions'][] = ['name' => $name, 'email' => $email, 'phone' => $phone];
$data['count']++;
writeData($data_file, $data);

echo json_encode(['success' => true, 'count' => $data['count']]);
exit();
