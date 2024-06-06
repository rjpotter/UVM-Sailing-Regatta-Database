<?php include 'top.php'; ?>

<main>
    <div class="container">
        <h1>Welcome to our Regatta Database</h1>
        <p>Use the buttons below to view regatta results by location:</p>
        <div class="button-container">
            <?php
            // Define the SQL query
            $sql = 'SELECT fldVenue FROM tblEventSubmissions GROUP BY fldVenue';
            $venue = null;

            // Prepare and execute the query
            $statement = $pdo->prepare($sql);
            $statement->execute();

            // Get the records from the result set
            $records = $statement->fetchAll();
            // Sorting records by 'fldVenue'
            usort($records, function($a, $b) {
                return strcmp($a['fldVenue'], $b['fldVenue']);
            });
            
            foreach($records as $record) {
                $venue = $record['fldVenue'];
                print '<a href="location.php?venue=' . $venue . '"><button type="button" class="quizButton primary">' . $venue . '</button></a>';
                print PHP_EOL;
            }
            ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
