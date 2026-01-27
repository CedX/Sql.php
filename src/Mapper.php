<?php declare(strict_types=1);
namespace Belin\Sql;

use Belin\Sql\Reflection\ColumnInfo;
use Belin\Sql\Reflection\TableInfo;

/**
 * Maps data records to entity objects.
 */
final class Mapper {

	/**
	 * The singleton instance of the data mapper.
	 */
	private static self $instance = new self;

	/**
	 * The mapping between the entity types and their associated database tables.
	 * @var array<string, TableInfo>
	 */
	private static array $mapping = [];

	/**
	 * Creates a new data mapper.
	 */
	private function __construct() {}

	/**
	 * Returns the singleton instance of the data mapper.
	 * @return self The singleton instance of the data mapper.
	 */
	public static function instance(): self {
		return self::$instance;
	}

	/**
	 * Converts the specified object into an equivalent value of the specified type.
	 * @param mixed $value The object to convert.
	 * @param ColumnInfo $column The column providing the type of object to return.
	 * @return mixed The value of the given type corresponding to the specified object.
	 * @internal
	 */
	public function changeType(mixed $value, ColumnInfo $column): mixed {
		return "TODO";
	}

	/**
	 * Creates a new object of a given type from the specified dictionary.
	 * @template T The object type.
	 * @param array<string, mixed> $properties A dictionary providing the properties to be set on the created object.
	 * @param class-string<T> $className The type to create.
	 * @return T The newly created object.
	 */
	public function createInstance(array $properties, string $className = \stdClass::class): object {
		if ($className == \stdClass::class) return (object) $properties;

		$instance = new $className;
		$table = $this->getTable($className);
		foreach (array_keys($properties) as $name) {
			$key = mb_strtolower($name);
			if (isset($table->columns[$key])) {
				$column = $table->columns[$key];
				if ($column->canWrite) $column->setValue($instance, $this->changeType($properties[$name], $column));
			}
		}

		return $instance;
	}

	/**
	 * Gets the table information associated with the specified type.
	 * @param class-string $className The type to inspect.
	 * @return TableInfo The table information associated with the specified type.
	 */
	public function getTable(string $className): TableInfo {
		if (!isset(self::$mapping[$className])) self::$mapping[$className] = new TableInfo(new \ReflectionClass($className));
		return self::$mapping[$className];
	}
}
