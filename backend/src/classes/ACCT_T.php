<?php
namespace FacultyServiceReports\Backend\Classes;

use FacultyServiceReports\Backend\Database;

class ACCT_T {

    public static function get_all($offset = 0, $limit = 100) {
        $db = Database::create();

        if ( !$db ) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return [];
        }

        $statement = 'SELECT * FROM FIN_REPORTING.ACCT_T';

        $stid = oci_parse($db, $statement);
        oci_execute($stid);

        $nrows = oci_fetch_all($stid, $res, $offset, $limit, OCI_FETCHSTATEMENT_BY_ROW);

        return $res;
    }

    public static function get_by_account_nbr($nbr) {
        $db = Database::create();

        if ( !$db ) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return [];
        }

        $statement = 'SELECT * FROM FIN_REPORTING.ACCT_T WHERE ACCOUNT_NBR = :p1';

        $stid = oci_parse($db, $statement);
        oci_bind_by_name($stid, ':p1', $nbr);
        oci_execute($stid);

        $row = oci_fetch_assoc($stid);

        if ( $row ) {
            return $row;
        }

        return new \stdClass();
    }

}