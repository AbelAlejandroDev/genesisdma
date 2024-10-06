<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inscriptions.csv');

// Lee el archivo JSON
$data_file = 'inscriptions.json';
$data = json_decode(file_get_contents($data_file), true);

// Abre la salida estándar para escribir
$output = fopen('php://output', 'w');
$delimiter = ';'; // Cambia aquí el delimitador a punto y coma

// Escribe la cabecera del CSV
fputcsv($output, ['Nombre', 'Email', 'Phone'], $delimiter); // Usa el delimitador aquí

// Escribe los datos en el CSV
foreach ($data['inscriptions'] as $inscription) {
    fputcsv($output, [$inscription['name'], $inscription['email'], $inscription['phone']], $delimiter); // Cambia 'nombre' por 'name'
}

// Cierra el archivo
fclose($output);
exit();
