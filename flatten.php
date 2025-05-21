<?php
/**
 * Script to merge barangay precinct JSON files into city-level files
 * Structure: 
 * - Input: data/local/REGION_NAME/PROVINCE_NAME/CITY_NAME/BARANGAY_NAME/thejson.json
 * - Output: data/cities/REGION_NAME/PROVINCE_NAME/CITY_NAME.json
 */

// Base directories
$sourceDir = 'data/local';
$targetDir = 'data/cities';

// Create target directory if it doesn't exist
if (!is_dir($targetDir)) {
  mkdir($targetDir, 0755, true);
}

// Set memory limit higher to handle large files
ini_set('memory_limit', '512M');

// Get all regions
$regions = array_filter(scandir($sourceDir), function ($item) use ($sourceDir) {
  return is_dir("$sourceDir/$item") && $item !== '.' && $item !== '..';
});

foreach ($regions as $region) {
  $regionDir = "$sourceDir/$region";
  $targetRegionDir = "$targetDir/$region";

  // Create region directory in target if it doesn't exist
  if (!is_dir($targetRegionDir)) {
    mkdir($targetRegionDir, 0755, true);
  }

  // Get all provinces within the region
  $provinces = array_filter(scandir($regionDir), function ($item) use ($regionDir) {
    return is_dir("$regionDir/$item") && $item !== '.' && $item !== '..';
  });

  foreach ($provinces as $province) {
    $provinceDir = "$regionDir/$province";
    $targetProvinceDir = "$targetRegionDir/$province";

    // Create province directory in target if it doesn't exist
    if (!is_dir($targetProvinceDir)) {
      mkdir($targetProvinceDir, 0755, true);
    }

    // Get all cities within the province
    $cities = array_filter(scandir($provinceDir), function ($item) use ($provinceDir) {
      return is_dir("$provinceDir/$item") && $item !== '.' && $item !== '..';
    });

    foreach ($cities as $city) {
      $cityDir = "$provinceDir/$city";
      $cityData = [
        'cityName' => $city,
        'data' => []
      ];

      // Get all barangays within the city
      $barangays = array_filter(scandir($cityDir), function ($item) use ($cityDir) {
        return is_dir("$cityDir/$item") && $item !== '.' && $item !== '..';
      });

      foreach ($barangays as $barangay) {
        $barangayDir = "$cityDir/$barangay";
        $barangayData = [
          'barangayName' => $barangay,
          'data' => []
        ];

        // Get all JSON files in the barangay directory
        $jsonFiles = array_filter(scandir($barangayDir), function ($item) {
          return pathinfo($item, PATHINFO_EXTENSION) === 'json' && $item !== 'info.json';
        });

        foreach ($jsonFiles as $jsonFile) {
          $jsonPath = "$barangayDir/$jsonFile";
          $jsonContent = file_get_contents($jsonPath);
          $decodedJson = json_decode($jsonContent, true);

          if ($decodedJson !== null) {
            // Add the JSON content to the barangay data
            $barangayData['data'][] = $decodedJson;
          } else {
            echo "Error parsing JSON file: $jsonPath\n";
          }
        }

        // Only add barangay data if it has content
        if (!empty($barangayData['data'])) {
          $cityData['data'][] = $barangayData;
        }
      }

      // Save each city data to its own JSON file
      if (!empty($cityData['data'])) {
        $targetFile = "$targetProvinceDir/$city.json";
        file_put_contents($targetFile, json_encode($cityData, JSON_PRETTY_PRINT));
        echo "Created merged file: $targetFile\n";

        // Free memory
        unset($cityData);
        gc_collect_cycles();
      }
    }
  }
}

echo "Finished merging all JSON files.\n";