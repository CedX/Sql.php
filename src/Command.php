<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents an SQL statement that is executed while connected to a data source.
 */
final class Command {

	/**
	 * The parameters of the SQL statement.
	 * @var array<string, mixed>
	 */
	public readonly array $parameters;

	/**
	 * The text of the SQL statement.
	 */
	public readonly string $text;

	/**
	 * Creates a new command.
	 * @param string $text The text of the SQL statement.
	 * @param array $parameters The parameters of the SQL statement.
	 */
	public function __construct(string $text, array $parameters = []) {
		$this->parameters = $parameters;
		$this->text = $text;
	}
}
