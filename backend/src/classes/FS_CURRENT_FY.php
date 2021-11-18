<?php

namespace FacultyServiceReports\Backend\Classes;

use FacultyServiceReports\Backend\Database;

class FS_CURRENT_FY {

    public static function get() {
        $db = Database::create();

        if ( !$db ) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return [];
        }

        $statement = 'SELECT * FROM FIN_REPORTING.FS_CURRENT_FY';

        $stid = oci_parse($db, $statement);
        oci_execute($stid);

        $row = oci_fetch_assoc($stid);

        if ( $row ) {
            return $row;
        }

        return new \stdClass();
    }

}