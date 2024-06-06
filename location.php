<?php include 'top.php';
// Convert special characters to HTML entities except for single quotes
$venue = htmlspecialchars($_GET['venue']);
?>

<main>
    <div class="container">
        <?php
        // Define the SQL query
        $sql = 'SELECT fldVenue, fldName, fldDate, fldRegatta, fldConditions, fldBoatNotes, fldWork, fldNoWork, fldProtest FROM tblEventSubmissions WHERE fldVenue LIKE :venue ORDER BY fldRegatta';
        $statement = $pdo->prepare($sql);
        $statement->execute([':venue' => "%" . $venue . "%"]);


        // Get the records from the result set
        $records = $statement->fetchAll();
        print '<h1>' . html_entity_decode($venue) . '</h1>';
        foreach($records as $record) {
            print '<div class="location">';
            print '<h2>' . html_entity_decode($record['fldRegatta']) . '</h2>';
            print '<p>' . html_entity_decode($record['fldName']) . '</p>';
            print '<p>' . html_entity_decode($record['fldDate']) . '</p>';
            print '<h3>Conditions</h3>';
            print '<p>' . html_entity_decode($record['fldConditions']) . '</p>';
            print '<h3>Boat Notes</h3>';
            print '<p>' . html_entity_decode($record['fldBoatNotes']) . '</p>';
            print '<h3>What Worked</h3>';
            print '<p>' . html_entity_decode($record['fldWork']) . '</p>';
            print '<h3>What Did Not Work</h3>';
            print '<p>' . html_entity_decode($record['fldNoWork']) . '</p>';
            print '<h3>Protest(s)</h3>';
            print '<p>' . html_entity_decode($record['fldProtest']) . '</p>';
            print '</div>';
        }

        ?>
    </div>
</main>

<?php include 'footer.php'; ?>
