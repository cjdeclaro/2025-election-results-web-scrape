<?php

function loadOrDownload($filePath, $url, $downloadDelay)
{
  if (file_exists($filePath)) {
    // echo "Loaded from cache: $filePath\n";
    $data = json_decode(file_get_contents($filePath), true);
  } else {
    // echo "Downloading: $url\n";
    $response = @file_get_contents($url);
    if ($response === FALSE) {
      echo "Failed to fetch: $url\n";
      return null;
    }
    $data = json_decode($response, true);
    usleep($downloadDelay * 1000000); // convert to microseconds

    if (!is_dir(dirname($filePath))) {
      mkdir(dirname($filePath), 0777, true);
    }
    file_put_contents($filePath, $response);
  }
  return $data;
}

function downloadData($code, $baseUrl, $baseDir, $type = 'region', $downloadDelay = 0.5)
{
  if ($type === 'region') {
    $infoPath = "$baseDir/info.json";
    $url = $baseUrl . $code . '.json';
    $data = loadOrDownload($infoPath, $url, $downloadDelay);
    if (!$data)
      return;

    $regions = $data['regions'];
    foreach ($regions as $region) {
      $name = str_replace("/", "_", trim($region['name']));
      $targetDir = "$baseDir/$name";

      if (isset($region['categoryCode'])) {
        // echo "Region: {$region['name']}\n";
        if ($region['categoryCode'] == "5") {
          $newBaseUrl = "https://2025electionresults.comelec.gov.ph/data/regions/precinct/" . substr($region['code'], 0, 2) . "/";
          downloadData($region['code'], $newBaseUrl, $targetDir, 'region', $downloadDelay);
        } else {
          downloadData($region['code'], $baseUrl, $targetDir, 'region', $downloadDelay);
        }
      } else {
        echo "ER: {$region['code']}\n";
        $newErUrl = "https://2025electionresults.comelec.gov.ph/data/er/" . substr($region['code'], 0, 3) . "/";
        downloadData($region['code'], $newErUrl, $baseDir, 'er', $downloadDelay);
      }
    }

    if (isset($regions[0]['categoryCode']) && intval($regions[0]['categoryCode']) < 4) {
      $cocPath = "$baseDir/coc.json";
      $cocUrl = "https://2025electionresults.comelec.gov.ph/data/coc/$code.json";
      loadOrDownload($cocPath, $cocUrl, $downloadDelay);
    }

  } elseif ($type === 'er') {
    $filePath = "$baseDir/$code.json";
    $url = $baseUrl . $code . ".json";
    loadOrDownload($filePath, $url, $downloadDelay);
  }
}

// Main execution
$baseDir = "data";
$downloadDelay = 0.5;

$topRegions = ["local", "overseas"];
foreach ($topRegions as $region) {
  // echo "Top Region: $region\n";
  $baseUrl = "https://2025electionresults.comelec.gov.ph/data/regions/$region/";
  $targetDir = "$baseDir/$region";
  downloadData("0", $baseUrl, $targetDir, 'region', $downloadDelay);
}

?>