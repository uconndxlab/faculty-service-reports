<?php

namespace FacultyServiceReports\Backend\Classes;

use FacultyServiceReports\Backend\Database;

class ACCT_CLOSEOUT_STAT_T {

    public static function get_all($offset = 0, $limit = 100) {
        $db = Database::create();

        if ( !$db ) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return [];
        }

        $statement = 'SELECT * FROM FIN_REPORTING.ACCT_CLOSEOUT_STAT_T';

        $stid = oci_parse($db, $statement);
        oci_execute($stid);

        $nrows = oci_fetch_all($stid, $res, $offset, $limit, OCI_FETCHSTATEMENT_BY_ROW);

        return $res;
    }

    public static function get_all_by_closeout_id($nbr, $offset = 0, $limit = 100) {
        $db = Database::create();

        if ( !$db ) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return [];
        }

        $statement = 'SELECT * FROM FIN_REPORTING.ACCT_CLOSEOUT_STAT_T WHERE CLOSEOUT_ID = :p1';

        $stid = oci_parse($db, $statement);
        oci_bind_by_name($stid, ':p1', $nbr);
        oci_execute($stid);

        $nrows = oci_fetch_all($stid, $res, $offset, $limit, OCI_FETCHSTATEMENT_BY_ROW);

        return $res;
    }

}