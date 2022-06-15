<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/solaredge.php';
use Ttskch\GoogleSheetsApi\Factory\GoogleClientFactory;
use Google\Service\Sheets\Spreadsheet;
    // echo $_POST['year'];
    

    $sheet_title = $_POST['year'];
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API For SolarEnergy');
    $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
    $client->setAuthConfig('rismi_credential.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    
    $service = new Google_Service_Sheets($client);
    // var_dump($service);
    $spreadsheetId = '1xi50RsQW8XKusQ1-zIXiblJl3LVbEAwwlOSJZklW9Xw';
    // var_dump($service);
    //$requests = json_decode($json_request_string);
    
    // TODO: Assign values to desired properties of `requestBody`:
    $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array('requests' => array('addSheet' => array('properties' => array('title' => $sheet_title, 'index' => 1 )))));
    
    $response = $service->spreadsheets->batchUpdate($spreadsheetId, $body);

// $pheetsu = \Ttskch\Pheetsu\Factory\PheetsuFactory::createServiceAccount(
//     __DIR__ . '/rismi_credential.json',
//     '1xi50RsQW8XKusQ1-zIXiblJl3LVbEAwwlOSJZklW9Xw',
//     // $title
//     'year'
// );
// $pheetsu->authenticate();
// // update
// $pheetsu->update('site id', '1', [
//     'actual1' => 111111 
// ]);
// // write
// $pheetsu->create(['site id'=>1231, 'actual1'=>00000]);

?>