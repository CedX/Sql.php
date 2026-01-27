<?php declare(strict_types=1);
namespace Belin\Sql;

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
	 * Gets the table information associated with the specified type.
	 * @param class-string $className The type to inspect.
	 * @return TableInfo The table information associated with the specified type.
	 */
	public function getTable(string $className): TableInfo {
		if (!isset(self::$mapping[$className])) self::$mapping[$className] = new TableInfo(new \ReflectionClass($className));
		return self::$mapping[$className];
	}
}
