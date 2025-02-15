<html>
    <head></head>
    <body>

    <?php
    if(DB::Connection()->getPdo()){
        echo "Successfully connected to DB and DB name is " . DB::connection()->getDatabaseName();

    }
    
    ?>
</body>
    </html>