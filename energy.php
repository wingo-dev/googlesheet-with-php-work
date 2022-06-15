<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/solaredge.php';
use Ttskch\GoogleSheetsApi\Factory\GoogleClientFactory;
session_start();
ini_set('max_execution_time', '0'); // for infinite time of execution

if (empty($_SESSION['i'])) {
    $_SESSION['i'] = 0;
}
// site ids total numbers
$total = 0;
$handle = fopen("site_ids.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $total++;
    }

    fclose($handle);
}
// end site ids total numbers

$j = 1;
$site_id = '';
$start_date = '';
$end_date = '';

$fn = fopen('site_ids.txt', 'r');
$pheetsu = \Ttskch\Pheetsu\Factory\PheetsuFactory::createServiceAccount(
    __DIR__ . '/solaredge-api-340301-0051cd438455.json',
    '1ia31X2JrS_cGXnPv8K5HRtIUkQyEbMitfbMxtfuKq0I',
    // $title
    'sheet1'
);
$pheetsu->authenticate();

/** @see https: //docs.google.com/spreadsheets/d/1C9hB71erLWgvkc-9PXFNIt2NcHSCUsEubUoNYgmcq5s/edit#gid=0 */

while ($site = fgets($fn)) {
    $_SESSION['i'] = $j;
    $percent = intval($j / $total * 100) . "%";
    echo "<h4>site ID: " . $site . "</h4>";
    // sleep(1);
    echo '<script>
    parent.document.getElementById("progressbar").innerHTML="<div style=\"width:' . $percent .
        ';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
    parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">' . $percent .
        ' is processed.</div>";</script>';
    $j++;
    $site_id = trim($site);
    $energy = new SolarEdge();
    $period = json_decode($energy->getStartEndDates($site_id));
    // $start_date = trim($period->dataPeriod->startDate);
    $end_date = date("Y-m-d", strtotime("-1 months", strtotime($period->dataPeriod->endDate)));
    $start_date = date("Y-m-d", strtotime("-1 years", strtotime($end_date)));

    echo $start_date . "<br>";
    echo $end_date . "<br>";

    $title = $site_id . "_" . $start_date . "_" . $end_date;

    $no = 0;
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
    // if ($data_type == "firstday") {
    $energy_data = new SolarEdge();
    $energies_data = json_decode($energy_data->getEnergy('DAY', $end_date, $start_date, $site_id), true);
    $values = $energies_data['energy']['values'];

    foreach ($values as $value) {
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
            $no++;
            // }
        } catch (Exception $e) {

        }
    }
    foreach ($values as $value) {
        if (substr($value['date'], -11) == "01 00:00:00") {
            try {
                $time = strtotime($value['date']);
                $the_month = date("m", $time);
                switch ($the_month) {
                    case "01":
                        echo "Jan-sum: " . ($jan / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-01' => $jan / 1000,
                        ]);
                        break;
                    case "02":
                        echo "Feb-sum: " . ($feb / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-02' => $feb / 1000,
                        ]);
                        break;
                    case "03":
                        echo "Mar-sum: " . ($mar / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-03' => $mar / 1000,
                        ]);
                        break;
                    case "04":
                        echo "Apr-sum: " . ($apr / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-04' => $apr / 1000,
                        ]);
                        break;
                    case "05":
                        echo "May-sum: " . ($may / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-05' => $may / 1000,
                        ]);
                        break;
                    case "06":
                        echo "Jun-sum: " . ($jun / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-06' => $jun / 1000,
                        ]);

                        break;
                    case "07":
                        echo "Jul-sum: " . ($jul / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-07' => $jul / 1000,
                        ]);
                        break;
                    case "08":
                        echo "Aug-sum: " . ($aug / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-08' => $aug / 1000,
                        ]);
                        break;
                    case "09":
                        echo "Sep-sum: " . ($sep / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-09' => $sep / 1000,
                        ]);
                        break;
                    case "10":
                        echo "Oct-sum: " . ($oct / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-10' => $oct / 1000,
                        ]);
                        break;
                    case "11":
                        echo "Nov-sum: " . ($nov / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-11' => $nov / 1000,
                        ]);
                        break;
                    case "12":
                        echo "Dec-sum: " . ($dec / 1000) . "<br>";
                        $pheetsu->update('Site ID', $site_id, [
                            'Actual-12' => $dec / 1000,
                        ]);

                        break;
                }

            } catch (Exception $e) {}
        }
    }

    // $pheetsu->update('Site ID', '1563621', [
    //     'Actual-01' => 1000,
    // ]);
    // echo $jan . "/" . $jan_num . "<br>" . $feb . "/" . $feb_num . "<br>";
    // echo $mar . "/" . $mar_num . "<br>" . $apr . "/" . $apr_num . "<br>";
    // echo $may . "/" . $may_num . "<br>" . $jun . "/" . $jun_num . "<br>";
    // echo $jul . "/" . $jul_num . "<br>" . $aug . "/" . $aug_num . "<br>";
    $rows = array();

    ob_flush();
    flush();
}
// print_r($energies->energy->values);

// }

fclose($fn);
session_destroy();