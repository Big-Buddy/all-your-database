<?php

    /**
     * Opens the connection to the database
     * @return MySQLI connection [information to connect to the datbaase]
     */
    function openConnection() {
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPass = '';
        $db = 'comp353';

        $connection = new mysqli($dbHost, $dbUser, $dbPass, $db) or die("Connect failed: %s\n" . $connection->error);
        return $connection;
    }

    /**
     * Clses the connection
     * @param MySQLI Connection $connection
     */
    function closeConnection($connection) {
      $connection->close();
    }
 ?>
