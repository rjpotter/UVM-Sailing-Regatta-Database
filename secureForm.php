<?php
include 'top.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dataIsGood = false;
$Message = '';

$name = '';
$email = '';

$venue = 'Bowdoin';

$regattaName = getData('regatta_name');
$regattaName = htmlspecialchars($regattaName);

$regattaDate = getData('regatta_date');
$regattaDate = htmlspecialchars($regattaDate);

$regattaConditions = getData('regatta_conditions');
$regattaConditions = htmlspecialchars($regattaConditions);

$boatNotes = getData('boat_notes');
$boatNotes = htmlspecialchars($boatNotes);

$whatWorked = getData('what_worked');
$whatWorked = htmlspecialchars($whatWorked);

$notWork = getData('not_work');
$notWork = htmlspecialchars($notWork);

$regattaProtests = getData('regatta_protests');
$regattaProtests = htmlspecialchars($regattaProtests);

function getData($field) {
    if (!isset($_POST[$field])) {
        return "";
    } else {
        return trim($_POST[$field]);
    }
}

function verifyAlphaNum($testString) {
    return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Form submitted successfully.");
    error_log("POST Data: " . print_r($_POST, true));
}
?>

<main class="sluggersForm">
    <section class="formheader">
        <h2>Input Regatta Information</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = getData('txtName');
            $email = getData('txtEmail');

            $venue = getData('radVenue');

            $dataIsGood = true;

            $venueList = array(
                "Bowdoin", "BC", "BU", "Brown", "Charleston", "CGA", "Conn", 
                "Dartmouth", "Fairfield", "Harvard", "Hobart", "Maine Maritime", 
                "MIT", "Mass Maritime", "Navy", "Roger Williams", "Suny", 
                "Tufts", "URI", "UNH", "UVM", "Yale", "KP", "Salve", 
                "Larchmont yacht club", "Carolina yacht club"
            );
            if (!in_array($venue, $venueList) || $venue == "") {
                print '<p class="mistake">Please select the regatta venue.</p>';
                $dataIsGood = false;
            }

            // Validate Name
            if($name == ""){
                print '<p class="mistake">Please type in your full name.</p>';
                $dataIsGood = false;
            }

            // Validate Email
            if($email == ""){
                print '<p class="mistake">Please type in your email address.</p>';
                $dataIsGood = false;
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                print '<p class="mistake">Your email address contains invalid characters.</p>';
                $dataIsGood = false;
            }

            // Validate Date
            if($regattaDate == ""){
                print '<p class="mistake">Please type in regatta date.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate Regatta Name
            if (empty($regattaName)) {
                print '<p class="mistake">Please provide regatta name.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate Regatta Conditions
            if (empty($regattaConditions)) {
                print '<p class="mistake">Please provide regatta conditions.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate Boat Notes
            if (empty($boatNotes)) {
                print '<p class="mistake">Please provide boat notes.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate What Worked
            if (empty($whatWorked)) {
                print '<p class="mistake">Please provide information for what worked.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate Regatta Name
            if (empty($notWork)) {
                print '<p class="mistake">Please provide information for what did not work.</p>';
                $dataIsGood = false;
            }

            // Sanitize and validate Protest(s)
            if (empty($regattaProtests)) {
                print '<p class="mistake">Please provide information on protests.</p>';
                $dataIsGood = false;
            }

            // save data
            if ($dataIsGood) {
                try {
                    $sql = 'INSERT INTO `tblEventSubmissions` (`pmkTimeStamp`, `fldVenue`, `fldName`, `fldDate`, `fldRegatta`, `fldConditions`, `fldBoatNotes`, `fldWork`, `fldNoWork`, `fldProtest`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ';
                    $statement = $pdo->prepare($sql);
                    $timeStamp = date('M-d-y H:i:s');
                    $data = array($timeStamp, $venue, $name, $regattaDate, $regattaName, $regattaConditions, $boatNotes, $whatWorked, $notWork, $regattaProtests);

                    error_log("Preparing to execute SQL: $sql");
                    error_log("Data bound to parameters: " . print_r($data, true));

                    if ($statement->execute($data)) {
                        error_log("Data successfully inserted into the database.");

                        $to = $email;
                        $from = 'UVM Sailing <catamountsailing@gmail.com>';
                        $subject = 'UVM Sailing Regatta Submissions';
                        $mailMessage = "<p style=\"font: 12pt Arial, sans-serif;\">Thank you for submitting a regatta report.</p><p>Your info will help our team for years to come!<br><span style=\"color: #2f5b32e5;\">UVM Sailing Team</span></p>";
                        $mailMessage .= $Message;

                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=utf-8\r\n";
                        $headers .= "From: " . $from . "\r\n";

                        $mailSent = mail($to, $subject, $mailMessage, $headers);

                        if ($mailSent) {
                            print "<p>A copy of your form has been emailed to you.</p>";
                            error_log("Confirmation email sent to $email.");
                        } else {
                            print "<p>Failed to send the email.</p>";
                            error_log("Failed to send confirmation email to $email.");
                        }
                    } else {
                        $Message = '<p>Record was NOT successfully saved.</p>';
                        $dataIsGood = false;
                        $errorInfo = $statement->errorInfo();
                        error_log("SQL Error: " . print_r($errorInfo, true));
                    }
                } catch (PDOException $e) {
                    $Message = "<p>Couldn't insert the record, please contact someone</p>";
                    $dataIsGood = false;
                    error_log("PDOException: " . $e->getMessage());
                }
            } else {
                error_log("Data validation failed, form not submitted.");
            }
        }
        ?>

        <form action="#" id="frmRegattaSubmissionForm" method="post">
            <fieldset class="txt">
                <legend>Contact Information</legend>
                <p>
                    <label for="txtName">Name:</label>
                    <input type="text" name="txtName" id="txtName" placeholder="Jane Doe" value="<?php echo $name; ?>" required>
                </p>
                <p>
                    <label for="txtEmail">Email:</label>
                    <input type="email" name="txtEmail" id="txtEmail" placeholder="name@email.com" value="<?php echo $email; ?>" required>
                </p>
            </fieldset>

            <fieldset class="radio">
                <legend>Select a Venue</legend>
                <div>
                    <input type="radio" name="radVenue" value="Bowdoin" id="radVenueBowdoin" required <?php if($venue == "Bowdoin") print 'checked'; ?>>
                    <label for="radVenueBowdoin">Bowdoin</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="BC" id="radVenueBC" required <?php if($venue == "BC") print 'checked'; ?>>
                    <label for="radVenueBC">BC</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="BU" id="radVenueBU" required <?php if($venue == "BU") print 'checked'; ?>>
                    <label for="radVenueBU">BU</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Brown" id="radVenueBrown" required <?php if($venue == "Brown") print 'checked'; ?>>
                    <label for="radVenueBrown">Brown</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Charleston" id="radVenueCharleston" required <?php if($venue == "Charleston") print 'checked'; ?>>
                    <label for="radVenueCharleston">Charleston</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="CGA" id="radVenueCGA" required <?php if($venue == "CGA") print 'checked'; ?>>
                    <label for="radVenueCGA">CGA</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Conn" id="radVenueConn" required <?php if($venue == "Conn") print 'checked'; ?>>
                    <label for="radVenueConn">Conn</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Dartmouth" id="radVenueDartmouth" required <?php if($venue == "Dartmouth") print 'checked'; ?>>
                    <label for="radVenueDartmouth">Dartmouth</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Fairfield" id="radVenueFairfield" required <?php if($venue == "Fairfield") print 'checked'; ?>>
                    <label for="radVenueFairfield">Fairfield</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Harvard" id="radVenueHarvard" required <?php if($venue == "Harvard") print 'checked'; ?>>
                    <label for="radVenueHarvard">Harvard</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Hobart" id="radVenueHobart" required <?php if($venue == "Hobart") print 'checked'; ?>>
                    <label for="radVenueHobart">Hobart</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Maine Maritime" id="radVenueMaineMaritime" required <?php if($venue == "Maine Maritime") print 'checked'; ?>>
                    <label for="radVenueMaineMaritime">Maine Maritime</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="MIT" id="radVenueMIT" required <?php if($venue == "MIT") print 'checked'; ?>>
                    <label for="radVenueMIT">MIT</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Mass Maritime" id="radVenueMassMaritime" required <?php if($venue == "Mass Maritime") print 'checked'; ?>>
                    <label for="radVenueMassMaritime">Mass Maritime</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Navy" id="radVenueNavy" required <?php if($venue == "Navy") print 'checked'; ?>>
                    <label for="radVenueNavy">Navy</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Roger Williams" id="radVenueRogerWilliams" required <?php if($venue == "Roger Williams") print 'checked'; ?>>
                    <label for="radVenueRogerWilliams">Roger Williams</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Suny" id="radVenueSuny" required <?php if($venue == "Suny") print 'checked'; ?>>
                    <label for="radVenueSuny">Suny</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Tufts" id="radVenueTufts" required <?php if($venue == "Tufts") print 'checked'; ?>>
                    <label for="radVenueTufts">Tufts</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="URI" id="radVenueURI" required <?php if($venue == "URI") print 'checked'; ?>>
                    <label for="radVenueURI">URI</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="UNH" id="radVenueUNH" required <?php if($venue == "UNH") print 'checked'; ?>>
                    <label for="radVenueUNH">UNH</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="UNH" id="radVenueUVM" required <?php if($venue == "UVM") print 'checked'; ?>>
                    <label for="radVenueUVM">UVM</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Yale" id="radVenueYale" required <?php if($venue == "Yale") print 'checked'; ?>>
                    <label for="radVenueYale">Yale</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="KP" id="radVenueKP" required <?php if($venue == "KP") print 'checked'; ?>>
                    <label for="radVenueKP">KP</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Salve" id="radVenueSalve" required <?php if($venue == "Salve") print 'checked'; ?>>
                    <label for="radVenueSalve">Salve</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Larchmont yacht club" id="radVenueLarchmontYachtClub" required <?php if($venue == "Larchmont yacht club") print 'checked'; ?>>
                    <label for="radVenueLarchmontYachtClub">Larchmont yacht club</label>
                </div>
                <div>
                    <input type="radio" name="radVenue" value="Carolina yacht club" id="radVenueCarolinaYachtClub" required <?php if($venue == "Carolina yacht club") print 'checked'; ?>>
                    <label for="radVenueCarolinaYachtClub">Carolina yacht club</label>
                </div>


            </fieldset>

            <fieldset class="text">
                <label for="regatta-name">Regatta Name:</label>
                <input type="text" id="regatta-name" name="regatta_name" value="<?php echo isset($_POST['regatta_name']) ? htmlspecialchars($_POST['regatta_name']) : ''; ?>" required>
            </fieldset>

            <fieldset class="date">
                <label for="regatta-date">Regatta Date:</label>
                <input type="date" id="regatta-date" name="regatta_date" value="<?php echo isset($_POST['regatta_date']) ? htmlspecialchars($_POST['regatta_date']) : ''; ?>" required>
            </fieldset>

            <fieldset class="text">
                <label for="regatta-conditions">Conditions:</label>
                <input type="text" id="regatta-conditions" name="regatta_conditions" value="<?php echo isset($_POST['regatta_conditions']) ? htmlspecialchars($_POST['regatta_conditions']) : ''; ?>" required>
            </fieldset>

            <fieldset class="text">
                <label for="boat-notes">Boat Notes:</label>
                <input type="text" id="boat-notes" name="boat_notes" value="<?php echo isset($_POST['boat_notes']) ? htmlspecialchars($_POST['boat_notes']) : ''; ?>" required>
            </fieldset>

            <fieldset class="text">
                <label for="what-worked">What Worked:</label>
                <input type="text" id="what-worked" name="what_worked" value="<?php echo isset($_POST['what_worked']) ? htmlspecialchars($_POST['what_worked']) : ''; ?>" required>
            </fieldset>

            <fieldset class="text">
                <label for="not-worked">What Did Not Work:</label>
                <input type="text" id="not-worked" name="not_work" value="<?php echo isset($_POST['not_work']) ? htmlspecialchars($_POST['not_work']) : ''; ?>" required>
            </fieldset>

            <fieldset class="text">
                <label for="protest">Protest(s):</label>
                <input type="text" id="protest" name="regatta_protests" value="<?php echo isset($_POST['regatta_protests']) ? htmlspecialchars($_POST['regatta_protests']) : ''; ?>" required>
            </fieldset>

            <fieldset class="submit">
                <legend>Submit</legend>
                <p>
                    <input type="submit" name="btnSubmit" value="Submit">
                </p>
            </fieldset>
        </form>
    </section>
</main>

<?php
include 'footer.php';
?>