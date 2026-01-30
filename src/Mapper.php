<?php declare(strict_types=1);
namespace Belin\Sql;

use Belin\Sql\Reflection\{ColumnInfo, TableInfo};

/**
 * Maps data records to entity objects.
 */
final class Mapper {

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
		static $instance = new self;
		return $instance;
	}

	/**
	 * Converts the specified object into an equivalent value of the specified type.
	 * @param mixed $value The object to convert.
	 * @param ColumnInfo $column The column providing the type of object to return.
	 * @return mixed The value of the given type corresponding to the specified object.
	 * @throws \UnexpectedValueException TODO
	 * @internal
	 */
	public function changeType(mixed $value, ColumnInfo $column): mixed {
		if ($value === null && $column->isNullable) return null;

		switch ($column->type) {
			case "bool":
				if (is_string($value)) {
					$exception = new \UnexpectedValueException("String '$value' was not recognized as a valid boolean.");
					$stringValue = mb_strtolower($value);
					return $stringValue == "false" ? false : ($stringValue == "true" ? true : throw $exception);
				}

				return (bool) $value;

			case "float": return (float) $value;
			case "int": return (int) $value;
			case "string": return (string) $value;
			default: throw new \UnexpectedValueException("TODO");
		}

		return $value;
	}

	/**
	 * Creates a new object of a given type from the specified dictionary.
	 * @template T The object type.
	 * @param array<string, mixed> $properties A dictionary providing the properties to be set on the created object.
	 * @param class-string<T> $class The type to create.
	 * @return T The newly created object.
	 */
	public function createInstance(array $properties, string $class = \stdClass::class): object {
		if ($class == \stdClass::class) return (object) $properties;

		$table = $this->getTable($class);
		$instance = $table->type->newInstance();
		foreach ($properties as $name => $value) if (isset($table->columns[$name])) {
			$column = $table->columns[$name];
			if ($column->canWrite) $column->setValue($instance, $this->changeType($value, $column));
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
