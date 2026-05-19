<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Specifies the type of a parameter within a query.
 */
enum ParameterDirection: int {

	/**
	 * The parameter is an input parameter.
	 */
	case Input = 0;

	/**
	 * The parameter is capable of both input and output.
	 */
	case InputOutput = \PDO::PARAM_INPUT_OUTPUT;
}
