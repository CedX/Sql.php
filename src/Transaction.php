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
	public function __destruct() {
		$this->rollback();
	}

	/**
	 * Commits the database transaction.
	 */
	public function commit(): void {
		$this->connection->pdo->commit();
	}

	/**
	 * Rolls back the transaction from a pending state.
	 */
	public function rollback(): void {
		$this->connection->pdo->rollBack();
	}
}
