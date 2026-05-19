<?php declare(strict_types=1);
namespace Belin\Sql\DataAnnotations;

/**
 * Represents the database column that a property is mapped to.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Column {

	/**
	 * The name of the column the property is mapped to.
	 */
	public readonly string $name;

	/**
	 * The database provider specific data type of the column the property is mapped to.
	 */
	public readonly ?string $typeName;

	/**
	 * Creates a new column attribute.
	 * @param string $name The name of the column the property is mapped to.
	 * @param string|null $typeName The database provider specific data type of the column the property is mapped to.
	 */
	public function __construct(string $name, ?string $typeName = null) {
		$this->name = $name;
		$this->typeName = $typeName;
	}
}
