<?php
require_once __DIR__ . '/../../database/databaseFunctions.php';

function getDataSource($data_source_url) {
    try {
        $data = file_get_contents($data_source_url);
    
        if ($data === false) { 
            throw new Exception("Error: 404, Could not retrieve data from the server. ");
        }
    
        $response_data = json_decode($data, true); 
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error: Failed to decode JSON. JSON Error: " . json_last_error_msg());
        }
    
        if ($response_data === null || !isset($response_data['success']) || $response_data['success'] !== true) {
            throw new Exception("Error: Invalid or unsuccessful response from data source. Server Response: " .$response_data['message']);
        }
    
        return $response_data['data'] ?? [];
    
    } catch (Exception $er) {
        containlog('Error', $er->getMessage(), __DIR__, 'reportData.log');
        return ['success' => false, 'message' => $er->getMessage()];
    }
}

