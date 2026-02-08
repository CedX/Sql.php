<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Commits the specified database transaction.
 * @param Transaction $transaction The transaction to commit.
 */
function sql_approve_transaction(Transaction $transaction): void {
	$transaction->commit();
}

/**
 * Closes the specified database connection.
 * @param Connection $connection The connection to the data source.
 */
function sql_close_connection(Connection $connection): void {
	$connection->close();
}

/**
 * Rolls back the specified database transaction.
 * @param Transaction $transaction The transaction to roll back.
 */
function sql_deny_transaction(Transaction $transaction): void {
	$transaction->rollback();
}

/**
 * Gets the singleton instance of the data mapper.
 * @return Mapper The singleton instance of the data mapper.
 */
function sql_get_mapper(): Mapper {
	return Mapper::instance();
}

/**
 * Creates a new command.
 * @param string $command The SQL query to be executed.
 * @param ParameterCollection|array $parameters The parameters of the SQL query.
 * @return Command The newly created command.
 */
function sql_new_command(string $command, ParameterCollection|array $parameters = []): Command {
	return new Command($command, $parameters);
}
