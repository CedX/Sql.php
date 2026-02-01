<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents a parameter of a parameterized SQL statement.
 */
final class Parameter {

	/**
	 * The prefixes used for parameter placeholders.
	 * @var string[]
	 */
	private static array $prefixes = ["?", "@", ":", "$"];

	/**
	 * The database type of this parameter.
	 */
	public DbType $dbType = DbType::String;

	/**
	 * Value indicating whether the parameter is input-only or bidirectional.
	 */
	public ParameterDirection $direction = ParameterDirection::Input;

	/**
	 * Creates a new parameter.
	 */
	public function __construct() {
		// TODO
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
		if (!$this->connection->pdo->commit()) throw new \PDOException("An error occurred while committing the transaction.");
	}

	/**
	 * Rolls back the transaction from a pending state.
	 * @throws \PDOException An error occurred while rolling back the transaction.
	 */
	public function rollback(): void {
		if (!$this->connection->pdo->rollBack()) throw new \PDOException("An error occurred while rolling back the transaction.");
	}
}
