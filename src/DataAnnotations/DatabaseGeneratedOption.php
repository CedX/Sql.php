<?php declare(strict_types=1);
namespace Belin\Sql\DataAnnotations;

/**
 * Represents the pattern used to generate values for a property in the database.
 */
enum DatabaseGeneratedOption {

	/**
	 * The database does not generate values.
	 */
	case None;

	/**
	 * The database generates a value when a row is inserted.
	 */
	case Identity;

	/**
	 * The database generates a value when a row is inserted or updated.
	 */
	case Computed;
}
