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
try{
    if (myArrayContainsWord($idCats, $sheet_title)) {
        // echo "Already exist";
        $pheetsu = \Ttskch\Pheetsu\Factory\PheetsuFactory::createServiceAccount( __DIR__ . '/rismi_credential.json', '1xi50RsQW8XKusQ1-zIXiblJl3LVbEAwwlOSJZklW9Xw', $sheet_title );
        $pheetsu->authenticate();
            
        $site_id = '';
        $the_month = 0;

        $jan = 0;
        $feb = 0;
        $mar = 0;
        $apr = 0;
        $may = 0;
        $jun = 0;
        $jul = 0;
        $aug = 0;
        $sep = 0;
        $oct = 0;
        $nov = 0;
        $dec = 0;
        
        
        $fn = fopen('site_ids.txt', 'r');
        
        while ($site_id = fgets($fn)) {
            $energy_period = new SolarEdge();
            $period = json_decode($energy_period->getStartEndDates(trim($site_id)));
            // echo $energy->getStartEndDates(trim($site));
            // $pheetsu->create(['site id'=>trim($site_id)]);
            echo $period->dataPeriod->startDate."<br>";
            echo $site_id;
            echo (int)date("Y", strtotime($period->dataPeriod->startDate));

            if((int)date("Y", strtotime($period->dataPeriod->startDate)) == (int)$_POST['year']){
                echo " exist <br>";
                $start_date = date("Y-m-d", strtotime($period->dataPeriod->startDate));
                $end_date = date("Y-m-d", strtotime("12/31", strtotime($period->dataPeriod->startDate)));
                echo $start_date."---".$end_date."<br>";
                $energy = new SolarEdge();
                $energies_data = json_decode($energy->getEnergy('DAY', $end_date, $start_date, trim($site_id)), true);
                
                $values = $energies_data['energy']['values'];
                foreach($values as $value){
                    // echo $value['value']."<br>";
                    try {
                        // if (substr($value['date'], -11) == "01 00:00:00") {
                        $time = strtotime($value['date']);
            
                        $the_month = date("m", $time);
            
                        switch ($the_month) {
                            case '01':
                                $jan += $value['value'];
                                break;
                            case '02':
                                $feb += $value['value'];
                                break;
                            case '03':
                                $mar += $value['value'];
                                break;
                            case '04':
                                $apr += $value['value'];
                                break;
                            case '05':
                                $may += $value['value'];
                                break;
                            case '06':
                                $jun += $value['value'];
                                break;
                            case '07':
                                $jul += $value['value'];
                                break;
                            case '08':
                                $aug += $value['value'];
                                break;
                            case '09':
                                $sep += $value['value'];
                                break;
                            case '10':
                                $oct += $value['value'];
                                break;
                            case '11':
                                $nov += $value['value'];
                                break;
                            case '12':
                                $dec += $value['value'];
                                break;
                        }
                    } catch (Exception $e) {
            
                    }
                }
                echo "jan".$jan."<br>"."feb".$feb."<br>"."mar".$mar."<br>"."apr".$apr."<br>"."may".$may."<br>"."jun".$jun."<br>"."jul".$jul."<br>"."aug".$aug."<br>"."sep".$sep."<br>"
            ."oct".$oct."<br>"."nov".$nov."<br>"."dec".$dec."<br>";
                
                $pheetsu->update('site id', trim($site_id), ['Jan' => $jan / 1000, 'Feb'=>$feb/1000, 'Mar'=>$mar/1000, 'Apr'=>$apr/1000,
                        'May'=>$may/1000, 'Jun'=>$jun/1000, 'July'=>$jul/1000, 'Aug'=>$aug/1000, 'Sep'=>$sep/1000, 'Oct'=>$oct/1000, 'Nov'=>$nov/1000, 'Dec'=>$dec/1000]);
                $the_month = 0;

                $jan = 0;
                $feb = 0;
                $mar = 0;
                $apr = 0;
                $may = 0;
                $jun = 0;
                $jul = 0;
                $aug = 0;
                $sep = 0;
                $oct = 0;
                $nov = 0;
                $dec = 0;
            }else{
                echo "no data <br>";
                $pheetsu->update('site id', trim($site_id), ['Jan' =>0, 'Feb'=>0, 'Mar'=>0, 'Apr'=>0,
                        'May'=>0, 'Jun'=>0, 'July'=>0, 'Aug'=>0, 'Sep'=>0, 'Oct'=>0, 'Nov'=>0, 'Dec'=>0]);
                $the_month = 0;

                $jan = 0;
                $feb = 0;
                $mar = 0;
                $apr = 0;
                $may = 0;
                $jun = 0;
                $jul = 0;
                $aug = 0;
                $sep = 0;
                $oct = 0;
                $nov = 0;
                $dec = 0;
            }
            
        }

    }else{
        //$requests = json_decode($json_request_string);
        // TODO: Assign values to desired properties of `requestBody`:
        $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array('requests' => array('addSheet' => array('properties' => array('title' => $sheet_title, 'index' => 1)))));
        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $body);
        
            $newRow = ['site id','Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sep','Oct','Nov','Dec'];
            $rows = [$newRow]; // you can append several rows at once
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = $sheet_title; // the service will detect the last row of this sheet
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);

            $pheetsu = \Ttskch\Pheetsu\Factory\PheetsuFactory::createServiceAccount( __DIR__ . '/rismi_credential.json', '1xi50RsQW8XKusQ1-zIXiblJl3LVbEAwwlOSJZklW9Xw', $sheet_title );
        $pheetsu->authenticate();
            
        $site_id = '';
        $the_month = 0;

        $jan = 0;
        $feb = 0;
        $mar = 0;
        $apr = 0;
        $may = 0;
        $jun = 0;
        $jul = 0;
        $aug = 0;
        $sep = 0;
        $oct = 0;
        $nov = 0;
        $dec = 0;
        
        
        $fn = fopen('site_ids.txt', 'r');
        
        while ($site_id = fgets($fn)) {
            $energy_period = new SolarEdge();
            $period = json_decode($energy_period->getStartEndDates(trim($site_id)));
            // echo $energy->getStartEndDates(trim($site));
            $pheetsu->create(['site id'=>trim($site_id)]);
            echo $period->dataPeriod->startDate."<br>";
            echo $site_id;
            echo (int)date("Y", strtotime($period->dataPeriod->startDate));
            
            if((int)date("Y", strtotime($period->dataPeriod->startDate)) == (int)$_POST['year']){
                echo " exist <br>";
                $start_date = date("Y-m-d", strtotime($period->dataPeriod->startDate));
                $end_date = date("Y-m-d", strtotime("12/31", strtotime($period->dataPeriod->startDate)));
                echo $start_date."---".$end_date."<br>";
                $energy = new SolarEdge();
                $energies_data = json_decode($energy->getEnergy('DAY', $end_date, $start_date, trim($site_id)), true);
                
                $values = $energies_data['energy']['values'];
                foreach($values as $value){
                    // echo $value['value']."<br>";
                    try {
                        // if (substr($value['date'], -11) == "01 00:00:00") {
                        $time = strtotime($value['date']);
            
                        $the_month = date("m", $time);
            
                        switch ($the_month) {
                            case '01':
                                $jan += $value['value'];
                                break;
                            case '02':
                                $feb += $value['value'];
                                break;
                            case '03':
                                $mar += $value['value'];
                                break;
                            case '04':
                                $apr += $value['value'];
                                break;
                            case '05':
                                $may += $value['value'];
                                break;
                            case '06':
                                $jun += $value['value'];
                                break;
                            case '07':
                                $jul += $value['value'];
                                break;
                            case '08':
                                $aug += $value['value'];
                                break;
                            case '09':
                                $sep += $value['value'];
                                break;
                            case '10':
                                $oct += $value['value'];
                                break;
                            case '11':
                                $nov += $value['value'];
                                break;
                            case '12':
                                $dec += $value['value'];
                                break;
                        }
                    } catch (Exception $e) {
            
                    }
                }
                echo "jan".$jan."<br>"."feb".$feb."<br>"."mar".$mar."<br>"."apr".$apr."<br>"."may".$may."<br>"."jun".$jun."<br>"."jul".$jul."<br>"."aug".$aug."<br>"."sep".$sep."<br>"
            ."oct".$oct."<br>"."nov".$nov."<br>"."dec".$dec."<br>";
                
                $pheetsu->update('site id', trim($site_id), ['Jan' => $jan / 1000, 'Feb'=>$feb/1000, 'Mar'=>$mar/1000, 'Apr'=>$apr/1000,
                        'May'=>$may/1000, 'Jun'=>$jun/1000, 'July'=>$jul/1000, 'Aug'=>$aug/1000, 'Sep'=>$sep/1000, 'Oct'=>$oct/1000, 'Nov'=>$nov/1000, 'Dec'=>$dec/1000]);
                $the_month = 0;

                $jan = 0;
                $feb = 0;
                $mar = 0;
                $apr = 0;
                $may = 0;
                $jun = 0;
                $jul = 0;
                $aug = 0;
                $sep = 0;
                $oct = 0;
                $nov = 0;
                $dec = 0;
            }else{
                echo "no data <br>";
                $pheetsu->update('site id', trim($site_id), ['Jan' =>0, 'Feb'=>0, 'Mar'=>0, 'Apr'=>0,
                        'May'=>0, 'Jun'=>0, 'July'=>0, 'Aug'=>0, 'Sep'=>0, 'Oct'=>0, 'Nov'=>0, 'Dec'=>0]);
                $the_month = 0;

                $jan = 0;
                $feb = 0;
                $mar = 0;
                $apr = 0;
                $may = 0;
                $jun = 0;
                $jul = 0;
                $aug = 0;
                $sep = 0;
                $oct = 0;
                $nov = 0;
                $dec = 0;
            }
            
        }
    } 
}catch (Exception $e){

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