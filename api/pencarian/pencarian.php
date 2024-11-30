<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../connection/config.php';
require_once 'Search.php'; // Path diubah karena sudah dalam folder yang sama

$search = new Search($conn);

$query = isset($_GET['query']) ? $_GET['query'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$result = $search->searchNews($query, $page);

echo json_encode($result);