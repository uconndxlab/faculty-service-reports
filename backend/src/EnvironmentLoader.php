<?php

namespace FacultyServiceReports\Backend;

class EnvironmentLoader {

    public static function loadEnv() {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();
        $dotenv->required(['DB_USER', 'DB_PASS', 'DB_CONN'])->notEmpty();

        return true;
    }

}