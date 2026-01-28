<?php declare(strict_types=1);
namespace Belin\Sql\Reflection;

use Belin\Sql\DataAnnotations\{Column, NotMapped, Table};

/**
 * Provides information about a database table.
 */
final class TableInfo {

	/**
	 * The table columns.
	 * @var array<string, ColumnInfo>
	 */
	public readonly array $columns;

	/**
	 * The single identity column, if applicable.
	 */
	public readonly ?ColumnInfo $identityColumn;

	/**
	 * The table name.
	 */
	public readonly string $name;

	/**
	 * The table schema.
	 */
	public readonly ?string $schema;

	/**
	 * Creates new table information.
	 * @param \ReflectionClass $type The type information providing the table metadata.
	 */
	public function __construct(\ReflectionClass $type) {
		$table = array_first($type->getAttributes(Table::class))?->newInstance() ?? new Table($type->getShortName());
		$this->name = $table->name;
		$this->schema = $table->schema;

		$this->columns = $type->getProperties()
			|> (fn($list) => array_filter($list, self::isColumn(...)))
			|> (fn($list) => array_map(fn($item) => new ColumnInfo($item), $list))
			|> (fn($list) => array_combine(array_column($list, "name"), $list));

		$identityColumns = array_filter($this->columns, fn($column) => $column->isIdentity);
		if (count($identityColumns) == 1) $this->identityColumn = array_first($identityColumns);
		else if (isset($this->columns["id"])) $this->identityColumn = $this->columns["id"];
	}

	/**
	 * Returns a value indicating whether the specified property should be included in database mapping.
	 * @return `true` if the specified property should be included in database mapping, otherwise `false`.
	 */
	private static function isColumn(\ReflectionProperty $property): bool {
		if ($property->isAbstract() || $property->isStatic() || $property->getAttributes(NotMapped::class)) return false;
		return !$property->isReadOnly() || $property->getAttributes(Column::class);
	}
}
