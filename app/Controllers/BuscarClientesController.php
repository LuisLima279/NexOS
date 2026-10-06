<?php
require_once __DIR__ . '/../Models/Cliente.php';

header('Content-Type: application/json');

$filtros = [
    'nome'     => $_GET['nome'] ?? '',
    'cpf_cnpj' => $_GET['cpf_cnpj'] ?? '',
    'email'    => $_GET['email'] ?? '',
    'telefone' => $_GET['telefone'] ?? ''
];

$clienteModel = new Cliente();
$resultados = $clienteModel->listarFiltrosAvancados($filtros);

echo json_encode($resultados);