<?php
/**
 * Test class for DbUser.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */
namespace Shinbuntu\DbUser\Tests\Units;

use atoum;
use mock\Doctrine\DBAL\Driver\PDOStatement;
use mock\Doctrine\DBAL\Connection;
use Shinbuntu\DbUser\DbUser as testedClass;

/**
 * Test class for DbUser.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */
class DbUser extends atoum
{
    private $connection = null;

    private function getConnection()
    {
        if ($this->connection !== null) {
            return $this->connection;
        }
        $this->mockGenerator->shuntParentClassCalls();
        $this->mockGenerator->orphanize('__construct');
        $this->mockGenerator->orphanize('__construct');

        $this->connection = new Connection();
        $this->connection->getMockController()->connect = function () {
        };
        $this->connection->getMockController()->fetchColumn = function () {
        };
        $this->connection->getMockController()->quote = function ($input) {
            return '"'.addslashes($input).'"';
        };
        $this->connection->getMockController()->query = function () {
            return new PDOStatement();
        };

        $this->mockGenerator->unshuntParentClassCalls();

        return $this->connection;
    }

    /**
     * Test createUserQuery method.
     *
     * @return void
     */
    public function testCreateUserQuery()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->string($this->testedInstance->createUserQuery('test_username', '!super_secure_password$'))
                ->isEqualTo('CREATE USER test_username@localhost IDENTIFIED BY "!super_secure_password$";');
    }

    /**
     * Test dropUser method.
     *
     * @return void
     */
    public function testDropUserQuery()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->string($this->testedInstance->dropUserQuery('test_username', '!super_secure_password$'))
                ->isEqualTo('DROP USER test_username@localhost;');
    }

    /**
     * Test userExistQuery method.
     *
     * @return void
     */
    public function testUserExistQuery()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->string($this->testedInstance->userExistQuery('test_username'))
                ->isEqualTo('SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = "test_username");');
    }

    /**
     * Test flushPrivilegesQuery method.
     *
     * @return void
     */
    public function testFlushPrivilegesQuery()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->string($this->testedInstance->flushPrivilegesQuery())
                ->isEqualTo('FLUSH PRIVILEGES;');
    }

    /**
     * Test changePrivilegesQuery method.
     *
     * @return void
     */
    public function testChangePrivilegesQuery()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->string(
                $this->testedInstance->changePrivilegesQuery(
                    testedClass::PRIVILEGE_STATEMENT_GRANT,
                    'test_username',
                    [
                        testedClass::PRIVILEGE_CREATE,
                        testedClass::PRIVILEGE_UPDATE,
                        testedClass::PRIVILEGE_DELETE,
                    ],
                    'test_database',
                    'test_table'
            ))
                ->isEqualTo('GRANT CREATE, UPDATE, DELETE ON test_database.test_table TO "test_username"@localhost;')

            ->string(
                $this->testedInstance->changePrivilegesQuery(
                    testedClass::PRIVILEGE_STATEMENT_GRANT,
                    'test_username',
                    testedClass::PRIVILEGE_CREATE,
                    'test_database',
                    'test_table'
                ))
                ->isEqualTo('GRANT CREATE ON test_database.test_table TO "test_username"@localhost;');
    }

    /**
     * Fake Tests for doctrine to ignore in coverage percentage.
     *
     * @return void
     */
    public function testDoctrine()
    {
        $connection = $this->getConnection();

        $this
            ->if($this->newTestedInstance($connection))
            ->variable($this->testedInstance->flushPrivileges())
                ->isEqualTo(true)

            ->variable($this->testedInstance->revokePrivileges('test_username'))
                ->isEqualTo(true)

            ->variable($this->testedInstance->grantPrivileges('test_username'))
                ->isEqualTo(true)

            ->variable($this->testedInstance->userExist('test_username'))
                ->isNull()

            ->variable($this->testedInstance->dropUser('test_username'))
                ->isEqualTo(true)

            ->variable($this->testedInstance->createUser('test_username', 'test_password'))
                ->isEqualTo(true);
    }
}
