<?php

namespace FacultyServiceReports\Backend;

class Database {

    public static function create($username, $password, $connection_string, $encoding = "", $session_mode = OCI_DEFAULT) {
        return oci_connect($username, $password, $connection_string, $encoding, $session_mode);
    }

}