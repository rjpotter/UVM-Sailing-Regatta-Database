<nav>
    <a class="<?php
    if ($pathParts['filename'] == "index") {
        print 'active-page';
    }
    ?>" href="../index.php">Home</a>

<a class="<?php
    if ($pathParts['filename'] == "secureForm") {
        print 'active-page';
    }
    ?>" href="secureForm.php">Submit a Regatta</a>

</nav>
