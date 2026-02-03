<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Automatically generates single-table commands.
 */
class CommandBuilder {

	/**
	 * The position of the catalog name in a qualified table name.
	 */
	public CatalogLocation $catalogLocation = CatalogLocation::Start;

	/**
	 * The string used as the catalog separator.
	 */
	public string $catalogSeparator = ".";

	/**
	 * The beginning string to use for naming parameters.
	 */
	public string $parameterPrefix = ":";

	/**
	 * The beginning string to use when specifying database objects.
	 */
	public string $quotePrefix = '"';
}
