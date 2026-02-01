<?php declare(strict_types=1);
namespace Belin\Sql\Fixtures;

/**
 * Defines the gender of a character.
 */
enum CharacterGender: string {
	case Balrog = "Balrog";
	case DarkLord = "DarkLord";
	case Dwarf = "Dwarf";
	case Elf = "Elf";
	case Hobbit = "Hobbit";
	case Human = "Human";
	case Istari = "Istari";
}
