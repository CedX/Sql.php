<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Describes the current state of the connection to a data source.
 */
enum ConnectionState {

	/**
	 * The connection is closed.
	 */
	case Closed;

	/**
	 * The connection is open.
	 */
	case Open;
}
