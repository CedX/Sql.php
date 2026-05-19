<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Indicates the position of the catalog name in a qualified table name in a text command.
 */
enum CatalogLocation {

	/**
	 * Indicates that the position of the catalog name occurs before the schema portion of a fully qualified table name.
	 */
	case Start;

	/**
	 * Indicates that the position of the catalog name occurs after the schema portion of a fully qualified table name.
	 */
	case End;
}
