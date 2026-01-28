<?php declare(strict_types=1);
namespace Belin\Sql\Fixtures;

use Belin\Sql\DataAnnotations\NotMapped;

/**
 * Represents a fictional character from a well-known saga.
 */
final class Character {

	/**
	 * TODO
	 */
	public string $titi {
		get => "titi";
	}

	/**
	 * TODO
	 */
	public readonly string $tata;

	/**
	 * TODO
	 */
	public static string $toto ="tata";

	/**
	 * The first name.
	 */
	public string $firstName = "";

	/**
	 * The character's gender.
	 */
	public CharacterGender $gender = CharacterGender::Human;

	/**
	 * The first name.
	 */
	public ?string $lastName;

	/**
	 * TODO
	 */
	#[NotMapped]
	public string $fullName = "";

	/**
	 * TODO
	 */
	public function __construct() {
		$this->tata = "tata";
	}
}
