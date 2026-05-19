<?php declare(strict_types=1);
namespace Belin\Sql\DataAnnotations;

/**
 * Specifies the database table that a class is mapped to.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Table {

	/**
	 * The name of the table the class is mapped to.
	 */
	public readonly string $name;

	/**
	 * The schema of the table the class is mapped to.
	 */
	public readonly ?string $schema;

	/**
	 * Creates a new table attribute.
	 * @param string $name The name of the table the class is mapped to.
	 * @param string|null $schema The schema of the table the class is mapped to.
	 */
	public function __construct(string $name, ?string $schema = null) {
		$this->name = $name;
		$this->schema = $schema;
	}
}
