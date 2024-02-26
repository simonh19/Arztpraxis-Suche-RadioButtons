<?php
if (session_id() == '') {
    session_start();
}
include 'helper/suche.php';
include 'helper/form_functions.php';
include 'helper/database_functions.php';

$anzahl = 0;
$hasValidationError = false;
$gefundenePatienten = null;
$svnVorhanden = isset($_GET['suchbegriff']) && !empty($_GET['suchbegriff']);
if ($svnVorhanden) {
    $svn = $_GET['suchbegriff'];
    $pattern = '/^\d{4}\/\d{4}-\d{2}-\d{2}$/';
    if(preg_match($pattern,$svn)){
        $gefundenePatienten = processForm($_GET);
        $anzahl = $gefundenePatienten -> rowCount();
    }else{
        $hasValidationError = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">BS Linz 2</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="index.php">⦁	Startseite</a>
        </div>
    </div>
</nav>
<div class="card border-0 shadow p-4 container d-flex align-items-center flex-column mt-4 gap-4">
    <h2>Patienten - Diagnosen</h2>
    <form action="index.php" method="get">
        <div class="row ">
            <label class="col-md-3" for="suchbegriff">SV-Nr.</label>
            <input class="col-md-9 rounded p-1" type="text" class="form-control" id="suchbegriff" name="suchbegriff" placeholder="Format:xxxx/JJJJ-MM-TT z.B. 1234/1980-10-05" required>
        </div>
        <h3 class="mt-3">Behandlungszeitraum:</h3>
        <div class="mt-3">
        <div class="row">
            <label class="col-md-3" for="suchbegriff">Start:</label>
            <input type="date" class="col-md-9 p-1 rounded" id="date-start" name="date-start">
        </div>
        <div class="row mt-3">
            <label class="col-md-3" for="suchbegriff">Ende:</label>
            <input type="date" class="col-md-9 p-1 rounded" id="date-end" name="date-end">
        </div>
        
        <div>
            <h3 class="mt-5">Behandlungsbeginn:</h3>
            <div class="form-group mb-3 mt-3">
                <?php
                $behandlungsmonate = [
                    'keineAngabe' => 'keine Angabe',
                    'letzterMonat' => 'letzter Monat',
                    'laufenderMonat' => 'laufender Monat',
                   
                ];
                $buttonGruppenName = 'suche-in';
                $defaultButton = $_GET['suche-in'] ?? 'keineAngabe';
                echo createRadioButtons($buttonGruppenName, $behandlungsmonate, $defaultButton); ?>
                    <div class='form-check'>
                    <input class='form-check-input' type='radio' name='suche-in' value='jahresMonat' id='suche-in'>
                    <input class='form-input' type='number' name='wunschMonatNummer' id='wunschMonatNummer'>
                    <label class='form-check-label' for='wunschmonat'>Monat des laufenden Jahres angeben</label>
                    </div>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Anzeigen</button>
        </div>
        </div>
    </form>
    
    <div>
        <?php if ($gefundenePatienten!=null && $anzahl > 0): ?>
        <?php
            $auswahlVorhanden = isset($_GET['auswahl']) && !empty($_GET['auswahl']);
            if($auswahlVorhanden){
                $auswahl = $_GET['auswahl'];
                if($auswahl == 'letzterMonat'){
                    $successMessage = "<h3>Suchkriterien:</h3> <br> Letzter Monat:
                    " . $_GET['startdatum'] . " - " . $_GET['enddatum'];     
                }
                if($auswahl == 'laufenderMonat'){
                    $successMessage = "<h3>Suchkriterien:</h3> <br> Laufender Monat:
                    " . $_GET['startdatum'] . " - " . $_GET['enddatum'];                }
                if($auswahl == 'jahresMonat'){
                    $successMessage = "<h3>Suchkriterien:</h3> <br> Eingegebener Monat:
                    " . $_GET['startdatum'] . " - " . $_GET['enddatum'];                }
                echo showSuccess($successMessage); 
            }
           
            
            echo generateTableFromQuery($gefundenePatienten);?>
        <?php else: ?>
            <?php if ($svnVorhanden): ?>
                <?php           
                    if($hasValidationError){
                        showAlertWarning("Bitte geben Sie die Sozialversicherung in einem korrekten Format an.");
                    }
                    else{
                        $warningText="Keine Ergebnisse für ". $_GET['suchbegriff'] ." gefunden.";
                        showAlertWarning($warningText); 
                    }
                    ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>