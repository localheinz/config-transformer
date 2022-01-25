<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer2022012510\Symfony\Component\Cache\Adapter;

use ConfigTransformer2022012510\Doctrine\DBAL\Connection;
use ConfigTransformer2022012510\Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use ConfigTransformer2022012510\Doctrine\DBAL\DriverManager;
use ConfigTransformer2022012510\Doctrine\DBAL\Exception as DBALException;
use ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException;
use ConfigTransformer2022012510\Doctrine\DBAL\ParameterType;
use ConfigTransformer2022012510\Doctrine\DBAL\Schema\Schema;
use ConfigTransformer2022012510\Symfony\Component\Cache\Exception\InvalidArgumentException;
use ConfigTransformer2022012510\Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use ConfigTransformer2022012510\Symfony\Component\Cache\Marshaller\MarshallerInterface;
use ConfigTransformer2022012510\Symfony\Component\Cache\PruneableInterface;
class DoctrineDbalAdapter extends \ConfigTransformer2022012510\Symfony\Component\Cache\Adapter\AbstractAdapter implements \ConfigTransformer2022012510\Symfony\Component\Cache\PruneableInterface
{
    protected $maxIdLength = 255;
    private $marshaller;
    private $conn;
    private string $platformName;
    private string $serverVersion;
    private string $table = 'cache_items';
    private string $idCol = 'item_id';
    private string $dataCol = 'item_data';
    private string $lifetimeCol = 'item_lifetime';
    private string $timeCol = 'item_time';
    private string $namespace;
    /**
     * You can either pass an existing database Doctrine DBAL Connection or
     * a DSN string that will be used to connect to the database.
     *
     * The cache table is created automatically when possible.
     * Otherwise, use the createTable() method.
     *
     * List of available options:
     *  * db_table: The name of the table [default: cache_items]
     *  * db_id_col: The column where to store the cache id [default: item_id]
     *  * db_data_col: The column where to store the cache data [default: item_data]
     *  * db_lifetime_col: The column where to store the lifetime [default: item_lifetime]
     *  * db_time_col: The column where to store the timestamp [default: item_time]
     *
     * @throws InvalidArgumentException When namespace contains invalid characters
     */
    public function __construct(Connection|string $connOrDsn, string $namespace = '', int $defaultLifetime = 0, array $options = [], \ConfigTransformer2022012510\Symfony\Component\Cache\Marshaller\MarshallerInterface $marshaller = null)
    {
        if (isset($namespace[0]) && \preg_match('#[^-+.A-Za-z0-9]#', $namespace, $match)) {
            throw new \ConfigTransformer2022012510\Symfony\Component\Cache\Exception\InvalidArgumentException(\sprintf('Namespace contains "%s" but only characters in [-+.A-Za-z0-9] are allowed.', $match[0]));
        }
        if ($connOrDsn instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Connection) {
            $this->conn = $connOrDsn;
        } else {
            if (!\class_exists(\ConfigTransformer2022012510\Doctrine\DBAL\DriverManager::class)) {
                throw new \ConfigTransformer2022012510\Symfony\Component\Cache\Exception\InvalidArgumentException(\sprintf('Failed to parse the DSN "%s". Try running "composer require doctrine/dbal".', $connOrDsn));
            }
            $this->conn = \ConfigTransformer2022012510\Doctrine\DBAL\DriverManager::getConnection(['url' => $connOrDsn]);
        }
        $this->table = $options['db_table'] ?? $this->table;
        $this->idCol = $options['db_id_col'] ?? $this->idCol;
        $this->dataCol = $options['db_data_col'] ?? $this->dataCol;
        $this->lifetimeCol = $options['db_lifetime_col'] ?? $this->lifetimeCol;
        $this->timeCol = $options['db_time_col'] ?? $this->timeCol;
        $this->namespace = $namespace;
        $this->marshaller = $marshaller ?? new \ConfigTransformer2022012510\Symfony\Component\Cache\Marshaller\DefaultMarshaller();
        parent::__construct($namespace, $defaultLifetime);
    }
    /**
     * Creates the table to store cache items which can be called once for setup.
     *
     * Cache ID are saved in a column of maximum length 255. Cache data is
     * saved in a BLOB.
     *
     * @throws DBALException When the table already exists
     */
    public function createTable() : void
    {
        $schema = new \ConfigTransformer2022012510\Doctrine\DBAL\Schema\Schema();
        $this->addTableToSchema($schema);
        foreach ($schema->toSql($this->conn->getDatabasePlatform()) as $sql) {
            $this->conn->executeStatement($sql);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureSchema(\ConfigTransformer2022012510\Doctrine\DBAL\Schema\Schema $schema, \ConfigTransformer2022012510\Doctrine\DBAL\Connection $forConnection) : void
    {
        // only update the schema for this connection
        if ($forConnection !== $this->conn) {
            return;
        }
        if ($schema->hasTable($this->table)) {
            return;
        }
        $this->addTableToSchema($schema);
    }
    /**
     * {@inheritdoc}
     */
    public function prune() : bool
    {
        $deleteSql = "DELETE FROM {$this->table} WHERE {$this->lifetimeCol} + {$this->timeCol} <= ?";
        $params = [\time()];
        $paramTypes = [\ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER];
        if ('' !== $this->namespace) {
            $deleteSql .= " AND {$this->idCol} LIKE ?";
            $params[] = \sprintf('%s%%', $this->namespace);
            $paramTypes[] = \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::STRING;
        }
        try {
            $this->conn->executeStatement($deleteSql, $params, $paramTypes);
        } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException $e) {
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    protected function doFetch(array $ids) : iterable
    {
        $now = \time();
        $expired = [];
        $sql = "SELECT {$this->idCol}, CASE WHEN {$this->lifetimeCol} IS NULL OR {$this->lifetimeCol} + {$this->timeCol} > ? THEN {$this->dataCol} ELSE NULL END FROM {$this->table} WHERE {$this->idCol} IN (?)";
        $result = $this->conn->executeQuery($sql, [$now, $ids], [\ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER, \ConfigTransformer2022012510\Doctrine\DBAL\Connection::PARAM_STR_ARRAY])->iterateNumeric();
        foreach ($result as $row) {
            if (null === $row[1]) {
                $expired[] = $row[0];
            } else {
                (yield $row[0] => $this->marshaller->unmarshall(\is_resource($row[1]) ? \stream_get_contents($row[1]) : $row[1]));
            }
        }
        if ($expired) {
            $sql = "DELETE FROM {$this->table} WHERE {$this->lifetimeCol} + {$this->timeCol} <= ? AND {$this->idCol} IN (?)";
            $this->conn->executeStatement($sql, [$now, $expired], [\ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER, \ConfigTransformer2022012510\Doctrine\DBAL\Connection::PARAM_STR_ARRAY]);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function doHave(string $id) : bool
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE {$this->idCol} = ? AND ({$this->lifetimeCol} IS NULL OR {$this->lifetimeCol} + {$this->timeCol} > ?)";
        $result = $this->conn->executeQuery($sql, [$id, \time()], [\ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::STRING, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER]);
        return (bool) $result->fetchOne();
    }
    /**
     * {@inheritdoc}
     */
    protected function doClear(string $namespace) : bool
    {
        if ('' === $namespace) {
            if ('sqlite' === $this->getPlatformName()) {
                $sql = "DELETE FROM {$this->table}";
            } else {
                $sql = "TRUNCATE TABLE {$this->table}";
            }
        } else {
            $sql = "DELETE FROM {$this->table} WHERE {$this->idCol} LIKE '{$namespace}%'";
        }
        try {
            $this->conn->executeStatement($sql);
        } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException $e) {
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    protected function doDelete(array $ids) : bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->idCol} IN (?)";
        try {
            $this->conn->executeStatement($sql, [\array_values($ids)], [\ConfigTransformer2022012510\Doctrine\DBAL\Connection::PARAM_STR_ARRAY]);
        } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException $e) {
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    protected function doSave(array $values, int $lifetime) : array|bool
    {
        if (!($values = $this->marshaller->marshall($values, $failed))) {
            return $failed;
        }
        $platformName = $this->getPlatformName();
        $insertSql = "INSERT INTO {$this->table} ({$this->idCol}, {$this->dataCol}, {$this->lifetimeCol}, {$this->timeCol}) VALUES (?, ?, ?, ?)";
        switch (\true) {
            case 'mysql' === $platformName:
                $sql = $insertSql . " ON DUPLICATE KEY UPDATE {$this->dataCol} = VALUES({$this->dataCol}), {$this->lifetimeCol} = VALUES({$this->lifetimeCol}), {$this->timeCol} = VALUES({$this->timeCol})";
                break;
            case 'oci' === $platformName:
                // DUAL is Oracle specific dummy table
                $sql = "MERGE INTO {$this->table} USING DUAL ON ({$this->idCol} = ?) " . "WHEN NOT MATCHED THEN INSERT ({$this->idCol}, {$this->dataCol}, {$this->lifetimeCol}, {$this->timeCol}) VALUES (?, ?, ?, ?) " . "WHEN MATCHED THEN UPDATE SET {$this->dataCol} = ?, {$this->lifetimeCol} = ?, {$this->timeCol} = ?";
                break;
            case 'sqlsrv' === $platformName && \version_compare($this->getServerVersion(), '10', '>='):
                // MERGE is only available since SQL Server 2008 and must be terminated by semicolon
                // It also requires HOLDLOCK according to http://weblogs.sqlteam.com/dang/archive/2009/01/31/UPSERT-Race-Condition-With-MERGE.aspx
                $sql = "MERGE INTO {$this->table} WITH (HOLDLOCK) USING (SELECT 1 AS dummy) AS src ON ({$this->idCol} = ?) " . "WHEN NOT MATCHED THEN INSERT ({$this->idCol}, {$this->dataCol}, {$this->lifetimeCol}, {$this->timeCol}) VALUES (?, ?, ?, ?) " . "WHEN MATCHED THEN UPDATE SET {$this->dataCol} = ?, {$this->lifetimeCol} = ?, {$this->timeCol} = ?;";
                break;
            case 'sqlite' === $platformName:
                $sql = 'INSERT OR REPLACE' . \substr($insertSql, 6);
                break;
            case 'pgsql' === $platformName && \version_compare($this->getServerVersion(), '9.5', '>='):
                $sql = $insertSql . " ON CONFLICT ({$this->idCol}) DO UPDATE SET ({$this->dataCol}, {$this->lifetimeCol}, {$this->timeCol}) = (EXCLUDED.{$this->dataCol}, EXCLUDED.{$this->lifetimeCol}, EXCLUDED.{$this->timeCol})";
                break;
            default:
                $platformName = null;
                $sql = "UPDATE {$this->table} SET {$this->dataCol} = ?, {$this->lifetimeCol} = ?, {$this->timeCol} = ? WHERE {$this->idCol} = ?";
                break;
        }
        $now = \time();
        $lifetime = $lifetime ?: null;
        try {
            $stmt = $this->conn->prepare($sql);
        } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException $e) {
            if (!$this->conn->isTransactionActive() || \in_array($platformName, ['pgsql', 'sqlite', 'sqlsrv'], \true)) {
                $this->createTable();
            }
            $stmt = $this->conn->prepare($sql);
        }
        // $id and $data are defined later in the loop. Binding is done by reference, values are read on execution.
        if ('sqlsrv' === $platformName || 'oci' === $platformName) {
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $id);
            $stmt->bindParam(3, $data, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::LARGE_OBJECT);
            $stmt->bindValue(4, $lifetime, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindValue(5, $now, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindParam(6, $data, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::LARGE_OBJECT);
            $stmt->bindValue(7, $lifetime, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindValue(8, $now, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
        } elseif (null !== $platformName) {
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $data, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::LARGE_OBJECT);
            $stmt->bindValue(3, $lifetime, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindValue(4, $now, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
        } else {
            $stmt->bindParam(1, $data, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::LARGE_OBJECT);
            $stmt->bindValue(2, $lifetime, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindValue(3, $now, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $stmt->bindParam(4, $id);
            $insertStmt = $this->conn->prepare($insertSql);
            $insertStmt->bindParam(1, $id);
            $insertStmt->bindParam(2, $data, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::LARGE_OBJECT);
            $insertStmt->bindValue(3, $lifetime, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
            $insertStmt->bindValue(4, $now, \ConfigTransformer2022012510\Doctrine\DBAL\ParameterType::INTEGER);
        }
        foreach ($values as $id => $data) {
            try {
                $rowCount = $stmt->executeStatement();
            } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception\TableNotFoundException $e) {
                if (!$this->conn->isTransactionActive() || \in_array($platformName, ['pgsql', 'sqlite', 'sqlsrv'], \true)) {
                    $this->createTable();
                }
                $rowCount = $stmt->executeStatement();
            }
            if (null === $platformName && 0 === $rowCount) {
                try {
                    $insertStmt->executeStatement();
                } catch (\ConfigTransformer2022012510\Doctrine\DBAL\Exception $e) {
                    // A concurrent write won, let it be
                }
            }
        }
        return $failed;
    }
    private function getPlatformName() : string
    {
        if (isset($this->platformName)) {
            return $this->platformName;
        }
        $platform = $this->conn->getDatabasePlatform();
        switch (\true) {
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\MySQLPlatform:
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\MySQL57Platform:
                return $this->platformName = 'mysql';
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\SqlitePlatform:
                return $this->platformName = 'sqlite';
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\PostgreSQLPlatform:
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\PostgreSQL94Platform:
                return $this->platformName = 'pgsql';
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\OraclePlatform:
                return $this->platformName = 'oci';
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\SQLServerPlatform:
            case $platform instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Platforms\SQLServer2012Platform:
                return $this->platformName = 'sqlsrv';
            default:
                return $this->platformName = \get_class($platform);
        }
    }
    private function getServerVersion() : string
    {
        if (isset($this->serverVersion)) {
            return $this->serverVersion;
        }
        $conn = $this->conn->getWrappedConnection();
        if ($conn instanceof \ConfigTransformer2022012510\Doctrine\DBAL\Driver\ServerInfoAwareConnection) {
            return $this->serverVersion = $conn->getServerVersion();
        }
        return $this->serverVersion = '0';
    }
    private function addTableToSchema(\ConfigTransformer2022012510\Doctrine\DBAL\Schema\Schema $schema) : void
    {
        $types = ['mysql' => 'binary', 'sqlite' => 'text'];
        $table = $schema->createTable($this->table);
        $table->addColumn($this->idCol, $types[$this->getPlatformName()] ?? 'string', ['length' => 255]);
        $table->addColumn($this->dataCol, 'blob', ['length' => 16777215]);
        $table->addColumn($this->lifetimeCol, 'integer', ['unsigned' => \true, 'notnull' => \false]);
        $table->addColumn($this->timeCol, 'integer', ['unsigned' => \true]);
        $table->setPrimaryKey([$this->idCol]);
    }
}
