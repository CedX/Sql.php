<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Specifies the type of a parameter within a query.
 */
enum DbType: int {

	/**
	 * A stream of non-Unicode characters.
	 */
	case AnsiString = \PDO::PARAM_STR | \PDO::PARAM_STR_CHAR;

	/**
	 * A stream of binary data.
	 */
	case Binary = \PDO::PARAM_LOB;

	/**
	 * A simple type representing boolean values of `true` or `false`.
	 */
	case Boolean = \PDO::PARAM_BOOL;

	/**
	 * An integral type.
	 */
	case Integer = \PDO::PARAM_INT;

	/**
	 * A stream of Unicode characters.
	 */
	case String = \PDO::PARAM_STR | \PDO::PARAM_STR_NATL;
}
