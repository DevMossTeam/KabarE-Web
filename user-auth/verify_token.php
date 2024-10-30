<?php
require 'vendor/autoload.php';

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

$jwt = json_decode(file_get_contents('php://input'), true)['token'] ?? null;

if (!$jwt) {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
    exit;
}

// Fetch Firebase public keys
$publicKeysJson = file_get_contents('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
$publicKeys = json_decode($publicKeysJson, true);

// Decode the JWT header to get the kid
try {
    $header = json_decode(base64_decode(strtr(explode('.', $jwt)[0], '-_', '+_')), true);
    $kid = $header['kid'] ?? null;

    // Check if the public key exists
    if (!$kid || !isset($publicKeys[$kid])) {
        echo json_encode(['success' => false, 'message' => 'Public key not found']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Invalid JWT format: ' . $e->getMessage()]);
    exit;
}

$publicKey = InMemory::plainText($publicKeys[$kid]);

// Set up the configuration for RS256
$config = Configuration::forAsymmetricSigner(
    new Sha256(),
    InMemory::empty(), // No private key needed for verification
    $publicKey
);

// Parse the token
$token = $config->parser()->parse($jwt);

// Set the constraints using SignedWith
$constraints = [
    new SignedWith(new Sha256(), $publicKey),
];

// Validate the token
$validator = new Validator();
$isValid = $validator->validate($token, ...$constraints);

if (!$isValid) {
    echo json_encode(['success' => false, 'message' => 'Token is invalid']);
    exit;
}

// Retrieve claims from the token
$claims = $token->claims();
$name = $claims->get('name');
$email = $claims->get('email');
$picture = $claims->get('picture');

echo json_encode([
    'success' => true,
    'name' => $name,
    'email' => $email,
    'picture' => $picture
]);
