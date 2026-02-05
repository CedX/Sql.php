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
	 * The current state of this connection.
	 */
	public ConnectionState $state { get => $this->pdo ? ConnectionState::Open : ConnectionState::Closed; }

	/**
	 * The underlying PDO connection.
	 * @internal
	 */
	public private(set) ?\PDO $pdo = null;

	/**
	 * The database provider specific options.
	 * @var array<int, mixed>
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
	 * @param array<int, mixed> $options The database provider specific options.
	 */
	public function __construct(string $connectionString, ?string $userId = null, #[\SensitiveParameter] ?string $password = null, array $options = []) {
		$options[\PDO::ATTR_CASE] = \PDO::CASE_NATURAL;
		$options[\PDO::ATTR_EMULATE_PREPARES] = false;
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
	public function __destruct() {
		try { $this->close(); } catch (\Throwable) {}
	}

	/**
	 * Begins a database transaction.
	 * @return Transaction An object representing the new transaction.
	 * @throws \PDOException An error occurred while beginning the transaction.
	 */
	public function beginTransaction(): Transaction {
		if (!$this->pdo?->beginTransaction()) throw new \PDOException("An error occurred while beginning the transaction.");
		return new Transaction($this);
	}

	/**
	 * Closes the connection to the database.
	 * @phpstan-assert null $this->pdo
	 */
	public function close(): void {
		if ($this->pdo?->inTransaction()) $this->pdo->rollBack();
		$this->pdo = null;
	}

	/**
	 * Executes a parameterized SQL statement.
	 * @param string $text The SQL query to be executed.
	 * @param ParameterCollection|null $parameters The parameters of the SQL query.
	 * @return int The number of rows affected.
	 * @throws \PDOException An error occurred while executing the query.
	 */
	public function execute(string $text, ?ParameterCollection $parameters = null): int {
		if ($this->state == ConnectionState::Closed) $this->open();

		$statement = $this->pdo->prepare($text);
		if (!$statement) throw new \PDOException("An error occurred while executing the query.");

		// TODO bind params
		if (!$statement->execute(/* TODO */)) throw new \PDOException("An error occurred while executing the query.");
		return $statement->rowCount();
	}

	/**
	 * Opens a connection to the database.
	 * @phpstan-assert \PDO $this->pdo
	 */
	public function open(): void {
		$this->pdo = \PDO::connect($this->connectionString, $this->userId, $this->password, $this->options);
	}
}
