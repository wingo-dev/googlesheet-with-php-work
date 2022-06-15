<?php
class SolarEdge
{
    const BASE_URL = "https://monitoringapi.solaredge.com/site/";
    const LICENSE_KEY = 'C2YXEMJIS5LQP007EJTI0IM9W2K5TJW3';
    public function getEnergy($time_unit, $end_date, $start_date, $site_id)
    {
        $url = SolarEdge::BASE_URL . $site_id . "/energy?timeUnit=DAY&endDate=" . $end_date . "&startDate=" . $start_date . "&api_key=" . self::LICENSE_KEY;
        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $body = '{}';

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $energy = curl_exec($ch);

        return $energy;
    }
    // site data : start and end dates  /site/{siteId}/dataPeriod
    public function getStartEndDates($site_id)
    {
        $url = SolarEdge::BASE_URL . $site_id . "/dataPeriod?api_key=" . self::LICENSE_KEY;
        $date_period = file_get_contents($url);
        return $date_period;
    }
}