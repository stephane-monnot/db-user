<?php

namespace Shinbuntu\DbUser;

use Doctrine\DBAL\Connection as DoctrineConnection;

/**
 * Create sql users.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */
class DbUser
{
    /**
     * Constant for privilege CREATE.
     */
    const PRIVILEGE_CREATE = 'CREATE';

    /**
     * Constant for privilege DROP.
     */
    const PRIVILEGE_DROP = 'DROP';

    /**
     * Constant for privilege GRANT OPTION.
     */
    const PRIVILEGE_GRANT_OPTION = 'GRANT OPTION';

    /**
     * Constant for privilege LOCK TABLES.
     */
    const PRIVILEGE_LOCK_TABLES = 'LOCK TABLES';

    /**
     * Constant for privilege REFERENCES.
     */
    const PRIVILEGE_REFERENCES = 'REFERENCES';

    /**
     * Constant for privilege EVENT.
     */
    const PRIVILEGE_EVENT = 'EVENT';

    /**
     * Constant for privilege ALTER.
     */
    const PRIVILEGE_ALTER = 'ALTER';

    /**
     * Constant for privilege DELETE.
     */
    const PRIVILEGE_DELETE = 'DELETE';

    /**
     * Constant for privilege INDEX.
     */
    const PRIVILEGE_INDEX = 'INDEX';

    /**
     * Constant for privilege INSERT.
     */
    const PRIVILEGE_INSERT = 'INSERT';

    /**
     * Constant for privilege SELECT.
     */
    const PRIVILEGE_SELECT = 'SELECT';

    /**
     * Constant for privilege UPDATE.
     */
    const PRIVILEGE_UPDATE = 'UPDATE';

    /**
     * Constant for privilege CREATE TEMPORARY TABLES.
     */
    const PRIVILEGE_CREATE_TEMPORARY_TABLES = 'CREATE TEMPORARY TABLES';

    /**
     * Constant for privilege TRIGGER.
     */
    const PRIVILEGE_TRIGGER = 'TRIGGER';

    /**
     * Constant for privilege CREATE VIEW.
     */
    const PRIVILEGE_CREATE_VIEW = 'CREATE VIEW';

    /**
     * Constant for privilege SHOW VIEW.
     */
    const PRIVILEGE_SHOW_VIEW = 'SHOW VIEW';

    /**
     * Constant for privilege ALTER ROUTINE.
     */
    const PRIVILEGE_ALTER_ROUTINE = 'ALTER ROUTINE';

    /**
     * Constant for privilege CREATE ROUTINE.
     */
    const PRIVILEGE_CREATE_ROUTINE = 'CREATE ROUTINE';

    /**
     * Constant for privilege EXECUTE.
     */
    const PRIVILEGE_EXECUTE = 'EXECUTE';

    /**
     * Constant for privilege FILE.
     */
    const PRIVILEGE_FILE = 'FILE';

    /**
     * Constant for privilege CREATE USER.
     */
    const PRIVILEGE_CREATE_USER = 'CREATE USER';

    /**
     * Constant for privilege PROCESS.
     */
    const PRIVILEGE_PROCESS = 'PROCESS';

    /**
     * Constant for privilege RELOAD.
     */
    const PRIVILEGE_RELOAD = 'RELOAD';

    /**
     * Constant for privilege REPLICATION CLIENT.
     */
    const PRIVILEGE_REPLICATION_CLIENT = 'REPLICATION CLIENT';

    /**
     * Constant for privilege REPLICATION SLAVE.
     */
    const PRIVILEGE_REPLICATION_SLAVE = 'REPLICATION SLAVE';

    /**
     * Constant for privilege SHOW DATABASES.
     */
    const PRIVILEGE_SHOW_DATABASES = 'SHOW DATABASES';

    /**
     * Constant for privilege SHUTDOWN.
     */
    const PRIVILEGE_SHUTDOWN = 'SHUTDOWN';

    /**
     * Constant for privilege SUPER.
     */
    const PRIVILEGE_SUPER = 'SUPER';

    /**
     * Constant for privilege ALL.
     */
    const PRIVILEGE_ALL = 'ALL';

    /**
     * Constant for privilege USAGE.
     */
    const PRIVILEGE_USAGE = 'USAGE';

    /**
     * Constant for privilege statement GRANT.
     */
    const PRIVILEGE_STATEMENT_GRANT = 'GRANT';

    /**
     * Constant for privilege statement REVOKE.
     */
    const PRIVILEGE_STATEMENT_REVOKE = 'REVOKE';

    /**
     * The connection.
     *
     * @var DoctrineConnection|\PDO
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param DoctrineConnection|\PDO $connection The connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Create MYSQL user.
     *
     * @param string $username Mysql username
     * @param string $password Mysql password
     * @param string $host     Mysql host
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function createUser($username, $password, $host = 'localhost')
    {
        return $this->connection->exec($this->createUserQuery($username, $password, $host)) !== false;
    }

    /**
     * Build query to create MYSQL user.
     *
     * @param string $username Mysql username
     * @param string $password Mysql password
     * @param string $host     Mysql host
     *
     * @return string SQL Query string
     */
    public function createUserQuery($username, $password, $host = 'localhost')
    {
        return 'CREATE USER '.$username.'@'.$host.' IDENTIFIED BY '.$this->connection->quote($password).';';
    }

    /**
     * Delete MYSQL user.
     *
     * @param string $username Mysql username
     * @param string $host Mysql host
     *
     * @return bool TRUE if exist or FALSE if not.
     */
    public function dropUser($username, $host = 'localhost')
    {
        return $this->connection->exec($this->dropUserQuery($username, $host)) !== false;
    }

    /**
     * Build query to drop MYSQL user.
     *
     * @param string $username Mysql username
     * @param string $host Mysql host
     *
     * @return string SQL Query string
     */
    public function dropUserQuery($username, $host = 'localhost')
    {
        return 'DROP USER '.$username.'@'.$host.';';
    }

    /**
     * Test if MYSQL user exist.
     *
     * @param string $username Mysql username
     *
     * @return bool TRUE if exist or FALSE if not.
     */
    public function userExist($username)
    {
        return $this->connection->query($this->userExistQuery($username))->fetchColumn();
    }

    /**
     * Build query to test if MYSQL user exist.
     *
     * @param string $username Mysql username
     *
     * @return string SQL Query string
     */
    public function userExistQuery($username)
    {
        return 'SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = '.$this->connection->quote($username).');';
    }

    /**
     * Grant privileges to mysql user.
     *
     * @param string       $username   Mysql username
     * @param array|string $privileges Mysql privileges
     * @param string       $database   Mysql database name
     * @param string       $table      Mysql $table name
     * @param string       $host       Mysql host
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function grantPrivileges(
        $username,
        $privileges = self::PRIVILEGE_USAGE,
        $database = '*',
        $table = '*',
        $host = 'localhost'
    ) {
        $sqlQuery = $this->changePrivilegesQuery(
            self::PRIVILEGE_STATEMENT_GRANT,
            $username,
            $privileges,
            $database,
            $table,
            $host
        );

        return $this->connection->exec($sqlQuery) !== false;
    }

    /**
     * Revoke privileges to mysql user.
     *
     * @param string       $username   Mysql username
     * @param array|string $privileges Mysql privileges
     * @param string       $database   Mysql database name
     * @param string       $table      Mysql $table name
     * @param string       $host       Mysql host
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function revokePrivileges(
        $username,
        $privileges = self::PRIVILEGE_USAGE,
        $database = '*',
        $table = '*',
        $host = 'localhost'
    ) {
        $sqlQuery = $this->changePrivilegesQuery(
            self::PRIVILEGE_STATEMENT_REVOKE,
            $username,
            $privileges,
            $database,
            $table,
            $host
        );

        return $this->connection->exec($sqlQuery) !== false;
    }

    /**
     * Flush privileges.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function flushPrivileges()
    {
        return $this->connection->exec($this->flushPrivilegesQuery()) !== false;
    }

    /**
     * Build query to flush privileges.
     *
     * @return string SQL Query string
     */
    public function flushPrivilegesQuery()
    {
        return 'FLUSH PRIVILEGES;';
    }

    /**
     * Build query to Grant or Revoke privileges to mysql user.
     *
     * @param string       $privilegeStatement REVOKE or GRANT
     * @param string       $username           Mysql username
     * @param array|string $privileges         Mysql privileges
     * @param string       $database           Mysql database name
     * @param string       $table              Mysql $table name
     * @param string       $host               Mysql host
     *
     * @return string SQL Query string
     */
    public function changePrivilegesQuery(
        $privilegeStatement,
        $username,
        $privileges = self::PRIVILEGE_USAGE,
        $database = '*',
        $table = '*',
        $host = 'localhost'
    ) {
        if (is_string($privileges)) {
            $privileges = [$privileges];
        }

        $usernameQuoted = $this->connection->quote($username);

        $sqlQuery = $privilegeStatement.' '.implode(', ', $privileges)
            .' ON '.$database.'.'.$table.' TO '.$usernameQuoted.'@'.$host.';';

        return $sqlQuery;
    }
}
