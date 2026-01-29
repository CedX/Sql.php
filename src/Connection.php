<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents an open connection to a data source.
 */
final class Connection {

	/**
	 * The string used to open a database.
	 */
	public readonly string $connectionString;

	/**
	 * The underlying PDO connection.
	 * @internal
	 */
	public private(set) ?\PDO $pdo = null;

	/**
	 * The current state of this connection.
	 */
	public ConnectionState $state { get => $this->pdo ? ConnectionState::Open : ConnectionState::Closed; }

	/**
	 * The database provider specific options.
	 */
	private readonly array $options;

	/**
	 * The password for the database user.
	 */
	private readonly ?string $password;

	/**
	 * The identifier for the database user.
	 */
	private readonly ?string $userId;

	/**
	 * Creates a new connection.
	 * @param string $connectionString The string used to open a database.
	 * @param string|null $userId The identifier for the database user.
	 * @param string|null $password The password for the database user.
	 * @param array<int, mixed>|null $options The database provider specific options.
	 */
	public function __construct(string $connectionString, ?string $userId = null, #[\SensitiveParameter] ?string $password = null, array $options = []) {
		$options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
		$options[\PDO::ATTR_STRINGIFY_FETCHES] = false;

		$this->connectionString = $connectionString;
		$this->options = $options;
		$this->password = $password;
		$this->userId = $userId;
	}

	/**
	 * Releases any resources associated with this object.
	 */
	public function __destruct(): void {
		try { $this->close(); } catch (\Throwable) {}
	}

	/**
	 * Begins a database transaction.
	 * @return Transaction An object representing the new transaction.
	 * @throws \PDOException An error occurred while beginning the transaction.
	 */
	public function beginTransaction(): Transaction {
		if (!$this->pdo->beginTransaction()) throw new \PDOException("An error occurred while beginning the transaction.");
		return new Transaction($this);
	}

	/**
	 * Closes the connection to the database.
	 */
	public function close(): void {
		if ($this->pdo->inTransaction()) $this->pdo->rollBack();
		$this->pdo = null;
	}

	/**
	 * Opens a connection to the database.
	 */
	public function open(): void {
		$this->pdo = \PDO::connect($this->connectionString, $this->userId, $this->password, $this->options);
	}
}
