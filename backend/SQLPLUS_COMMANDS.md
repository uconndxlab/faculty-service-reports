# SQL*PLUS Commands

SQL*Plus is the CLI that comes installed on the built oracle database image.

To see information about this tool:

```
docker-compose exec db bash
#/ sqlplus --version
```

## Example Commands

Login as sysdba:

```
sqlplus sys/oracledb@//localhost:1521/XE as sysdba
```


See Installed Version of Oracle Database:

```
COL PRODUCT FORMAT A38
COL VERSION FORMAT A10
COL VERSION_FULL FORMAT A12
COL STATUS FORMAT A12
SELECT * FROM PRODUCT_COMPONENT_VERSION;


OUTPUT:

PRODUCT                                VERSION    VERSION_FULL STATUS
-------------------------------------- ---------- ------------ ------------
Oracle Database 18c Express Edition    18.0.0.0.0 18.4.0.0.0   Production
```