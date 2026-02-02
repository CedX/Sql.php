<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents a transaction to be performed at a data source.
 */
final class Transaction {

	/**
	 * The connection associated with this transaction.
	 */
	public readonly Connection $connection;

	/**
	 * Creates a new transaction.
	 * @param Connection $connection The connection associated with this transaction.
	 */
	public function __construct(Connection $connection) {
		$this->connection = $connection;
	}

	/**
	 * Releases any resources associated with this object.
	 */
	public function __destruct(): void {
		try { $this->rollback(); } catch (\Throwable) {}
	}

	/**
	 * Commits the database transaction.
	 * @throws \PDOException An error occurred while committing the transaction.
	 */
	public function commit(): void {
		if (!$this->connection->pdo?->commit()) throw new \PDOException("An error occurred while committing the transaction.");
	}

	/**
	 * Rolls back the transaction from a pending state.
	 * @throws \PDOException An error occurred while rolling back the transaction.
	 */
	public function rollback(): void {
		if (!$this->connection->pdo?->rollBack()) throw new \PDOException("An error occurred while rolling back the transaction.");
	}
}
