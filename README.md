# MySQL User Account Management in PHP

Create Mysql users and manage privileges easily.

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/PHP-DI/PHP-DI.svg?style=flat-square)](https://scrutinizer-ci.com/g/SHINBUNTU/DB-USER/?branch=master)

## Why?

This project makes easier the MySQL User Account Management in PHP and it avoids the use of raw SQL queries.

## Installation

```bash
$ composer require shinbuntu/db-user
```

## Usage

#### Instantiate the manager with PDO or Doctrine Connection

```php
$dbUserManager = new DbUser($dbConnection);
```

#### Create a new user

```php
$dbUserManager->createUser('test_username', '!super_secure_password$');
```

#### Drop a user

```php
$dbUserManager->dropUser('test_username');
```

#### Test if user already exist

```php
$dbUserManager->userExist('test_username');
```

#### Grant privileges to mysql user on all databases (don't forget to flush privileges)

```php
$dbUserManager->grantPrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_CREATE_USER,
        DbUser::PRIVILEGE_CREATE,
        DbUser::PRIVILEGE_GRANT_OPTION,
        DbUser::PRIVILEGE_ALTER,
        DbUser::PRIVILEGE_FILE,
        DbUser::PRIVILEGE_RELOAD,
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ]
);
```

#### Grant privileges to mysql user on specific database (don't forget to flush privileges)

```php
$dbUserManager->grantPrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ],
    'test_database_name'
);
```

#### Grant privileges to mysql user on specific table (don't forget to flush privileges)

```php
$dbUserManager->grantPrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ],
    'test_database_name',
    'test_table_name'
);
```

#### Revoke privileges to mysql user on all databases (don't forget to flush privileges)

```php
$dbUserManager->revokePrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_CREATE_USER,
        DbUser::PRIVILEGE_CREATE,
        DbUser::PRIVILEGE_GRANT_OPTION,
        DbUser::PRIVILEGE_ALTER,
        DbUser::PRIVILEGE_FILE,
        DbUser::PRIVILEGE_RELOAD,
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ]
);
```

#### Revoke privileges to mysql user on specific database (don't forget to flush privileges)

```php
$dbUserManager->revokePrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ],
    'test_database_name'
);
```

#### Revoke privileges to mysql user on specific table (don't forget to flush privileges)

```php
$dbUserManager->revokePrivileges(
    'test_username',
    [
        DbUser::PRIVILEGE_SELECT,
        DbUser::PRIVILEGE_INSERT,
        DbUser::PRIVILEGE_UPDATE,
        DbUser::PRIVILEGE_DELETE,
    ],
    'test_database_name',
    'test_table_name'
);
```

#### Flush privileges

```php
$dbUserManager->flushPrivileges();
```

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## License

The project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
