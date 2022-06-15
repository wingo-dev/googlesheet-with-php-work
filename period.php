<?php
require __DIR__ . '/solaredge.php';

$site_id = $_POST['site_id'];
$energy = new SolarEdge();
$period = $energy->getStartEndDates($site_id);
echo $period;
