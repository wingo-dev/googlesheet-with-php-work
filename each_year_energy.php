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
// checking part sheet or not
$sheetInfo = $service->spreadsheets->get($spreadsheetId);
$allsheet_info = $sheetInfo['sheets'];
$idCats = array_column($allsheet_info, 'properties');
    // var_dump($service);
// checking existing sheet or not function
function myArrayContainsWord(array $myArray, $word)
{
    foreach ($myArray as $element) {
        if ($element->title == $word) {
            return true;
        }
    }
    return false;
}
    
if (myArrayContainsWord($idCats, $sheet_title)) {
    // echo "Already exist";
    $energy = new SolarEdge();
    $fn = fopen('site_ids.txt', 'r');
    while ($site = fgets($fn)) {
        $period = json_decode($energy->getStartEndDates(trim($site)));
        // echo $energy->getStartEndDates(trim($site));
        echo $period->dataPeriod->startDate."<br>";
        if((int)date("Y", strtotime($period->dataPeriod->startDate)) <= (int)$_POST['year']){
            echo " exist <br>";
            $start_date = date("Y-m-d", strtotime($period->dataPeriod->startDate));
            
            $end_date = date("Y-m-d", strtotime("12/31", strtotime($period->dataPeriod->startDate)));
            
            echo $start_date."---".$end_date."<br>";
        }else{
            echo "no data <br>";
        }
    }
}else{
    //$requests = json_decode($json_request_string);
    // TODO: Assign values to desired properties of `requestBody`:
    $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array('requests' => array('addSheet' => array('properties' => array('title' => $sheet_title, 'index' => 1)))));
    $response = $service->spreadsheets->batchUpdate($spreadsheetId, $body);

} 



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