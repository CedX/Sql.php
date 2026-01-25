<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents an open connection to a data source.
 */
final class Connection {

	/**
	 * The default connection options.
	 */
	public const array DefaultOptions = [
		\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		\PDO::ATTR_STRINGIFY_FETCHES => false
	];

	/**
	 * The string used to open a database.
	 */
	public readonly string $connectionString;

	/**
	 * The underlying PDO connection.
	 */
	public private(set) ?\PDO $pdo = null;

	/**
	 * The current state of this connection.
	 */
	public ConnectionState $state {
		get => $this->pdo ? ConnectionState::Open : ConnectionState::Closed;
	}

	/**
	 * The database provider specific options.
	 */
	private readonly ?array $options;

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
	public function __construct(string $connectionString, ?string $userId = null, #[\SensitiveParameter] ?string $password = null, ?array $options = null) {
		$this->connectionString = $connectionString;
		$this->options = $options ?: self::DefaultOptions;
		$this->password = $password;
		$this->userId = $userId;
	}

	/**
	 * Releases any resources associated with this object.
	 */
	public function __destruct(): void {
		$this->close();
	}

	/**
	 * TODO
	 * @return Transaction
	 */
	public function beginTransaction(): Transaction {
		$transaction = new Transaction($this);
		$this->pdo->beginTransaction(); // TODO check result
		return $transaction;
	}

	/**
	 * Closes the connection to the database.
	 */
	public function close(): void {
		$this->pdo = null;
	}

	/**
	 * Opens a connection to the database.
	 */
	public function open(): void {
		$this->pdo = \PDO::connect($this->connectionString, $this->userId, $this->password, $this->options);
	}
}
