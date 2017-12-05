<?php

    /**
     * Opens the connection to the database
     * @return MySQLI connection [information to connect to the datbaase]
     */
    function openConnection() {
        $dbHost = 'fvc353_2.encs.concordia.ca';
        $dbUser = 'fvc353_2';
        $dbPass = 'HwsbV9HS';
        $db = ' fvc353_2';

        $connection = new mysqli($dbHost, $dbUser, $dbPass, $db) or die("Connect failed: %s\n" . $connection->error);
        return $connection;
    }

    /**
     * Closes the connection
     * @param MySQLI Connection $connection
     */
    function closeConnection($connection) {
      $connection->close();
    }

    openConnection();
 ?>
