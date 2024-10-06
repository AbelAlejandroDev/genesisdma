<?php
header("Access-Control-Allow-Origin: *");

// Permitir los métodos de petición que quieras permitir.
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Permitir los encabezados que sean necesarios.
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data_file = '../db/inscriptions.json';

function readData($data_file)
{
    if (!file_exists($data_file)) {
        file_put_contents($data_file, json_encode(['count' => 0, 'inscriptions' => []]));
    }
    $data = json_decode(file_get_contents($data_file), true);
    return $data;
}

$data = readData($data_file);
$count = $data['count'];

echo json_encode(['success' => true, 'count' => $count]);
exit;
