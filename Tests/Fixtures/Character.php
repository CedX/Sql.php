<?php declare(strict_types=1);
namespace Belin\Sql\Fixtures;

use Belin\Sql\DataAnnotations\{Column, DatabaseGenerated, DatabaseGeneratedOption, Table};

/**
 * Represents a fictional character from a well-known saga.
 */
#[Table("Characters", schema: "main")]
final class Character {

	/**
	 * The first name.
	 */
	#[Column("FirstName")]
	public string $firstName = "";

	/**
	 * The full name.
	 */
	#[Column("FullName"), DatabaseGenerated(DatabaseGeneratedOption::Computed)]
	public string $fullName = "";

	/**
	 * The character's gender.
	 */
	#[Column("FirstName")]
	public CharacterGender $gender = CharacterGender::Human;

	/**
	 * The character's identifier.
	 */
	#[Column("ID"), DatabaseGenerated(DatabaseGeneratedOption::Identity)]
	public int $id = 0;

	/**
	 * The first name.
	 */
	#[Column("LastName")]
	public string $lastName = "";
}
