<?php

namespace FacultyServiceReports\Backend;

class Database {

    public static function create() {
        // return $_ENV['DB_CONN'];
        // return $_ENV['TNS_ADMIN'] . '/tnsnames.ora';
        return oci_connect(
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_CONN']
        );
    }

}