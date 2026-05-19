<?php declare(strict_types=1);
namespace Belin\Sql\DataAnnotations;

/**
 * Specifies how the database generates values for a property.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class DatabaseGenerated {

	/**
	 * The pattern used to generate values for the property in the database.
	 */
	public readonly DatabaseGeneratedOption $databaseGeneratedOption;

	/**
	 * Creates a new database generated attribute.
	 * @param DatabaseGeneratedOption $databaseGeneratedOption The database generated option.
	 */
	public function __construct(DatabaseGeneratedOption $databaseGeneratedOption) {
		$this->databaseGeneratedOption = $databaseGeneratedOption;
	}
}
