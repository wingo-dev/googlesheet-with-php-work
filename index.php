<?php
$fn = fopen('years.txt', 'r');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <button type="button" id="button1" class="btn btn-success btn-block">Click Here To Start Progress
                </button>
                    <form action="each_year_energy.php" method="post" onsubmit="return validate();">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" name="year" id="year-selected">
                                    <option selected>Select Year</option>
                                    <?php
                                        while($each_year = fgets($fn)){
                                            echo "<option value='".$each_year."'>".trim($each_year)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-info btn-block" name="submit_form" value="Submit">Click Each Year Progress</button>
                            </div>
                        </div>
                    </form>
                
            </div>
            <div class="col-md-12">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <div id="progressbar" style="border:1px solid #ccc; border-radius: 5px; "></div>

                <!-- Progress information -->
                <br>
                <div id="information"></div>
            </div>
            <iframe id="loadarea"></iframe><br />
        </div>
    </div>
</body>
<script>
$("#button1").click(function() {
    document.getElementById('loadarea').src = 'energy.php';
});

// form validation
function validate(){
    var error = "";
    var year = document.getElementById('year-selected');
    // alert(year.value);
    if(!Number(year.value)){
        alert('select year');
        return false;
    }
}
</script>

</html>