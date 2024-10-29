<?php
require 'vendor/autoload.php'; // Ensure that Composer's autoloader is included

use Kreait\Firebase\Factory; // Import Firebase Factory class
use Kreait\Firebase\Auth;    // Import Firebase Auth class

class FirebaseConfig {
    private $auth; // Declare private variable to hold Auth instance

    public function __construct() {
        // Update the path to the service account JSON file
        $serviceAccountPath = __DIR__ . '/kabare-cf940-firebase-adminsdk-8qu0w-017b632945.json';

        // Check if the service account file exists
        if (!file_exists($serviceAccountPath)) {
            throw new Exception("Service account file not found: $serviceAccountPath");
        }

        // Initialize Firebase with the service account file
        $factory = (new Factory)
            ->withServiceAccount($serviceAccountPath);

        // Get instance of Auth
        $this->auth = $factory->createAuth();
    }

    // Method to return the Auth instance
    public function getAuth() {
        return $this->auth;
    }
}
