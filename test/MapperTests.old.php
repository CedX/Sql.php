<?php declare(strict_types=1);
namespace Belin\Sql;

use Belin\Sql\Fixtures\{Character, CharacterGender};
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertEquals, assertNull};

/**
 * Tests the features of the {@see Mapper} class.
 */
#[TestDox("Mapper")]
final class MapperTests extends TestCase {

	/**
	 * The test data used by the `changeType()` method.
	 * @return array<TODO>
	 */
	// public static function changeTypeData(): array {
	// 	return [
	// 		// TODO
	// 	];
	// }

	// #[Test, TestDox("changeType()"), DataProvider("changeTypeData")]
	// public function changeType(): void {
	// 	// TODO
	// }

	// #[Test, TestDox("createInstance()")]
	// public function createInstance(): void {
	// 	$properties = [
	// 		"CLASS" => "Bard/minstrel",
	// 		"FirstName" => "Cédric",
	// 		"Gender" => CharacterGender::Balrog->value,
	// 		"LastName" => null
	// 	];

	// 	$instance = Mapper::instance()->createInstance($properties);
	// 	assertEquals("Bard/minstrel", $instance->{"CLASS"});
	// 	assertEquals("Cédric", $instance->FirstName);
	// 	assertEquals(CharacterGender::Balrog->value, $instance->Gender);
	// 	assertNull($instance->LastName);

	// 	$character = Mapper::instance()->createInstance($properties, Character::class);
	// 	assertEquals("Cédric", $character->firstName);
	// 	assertEquals(CharacterGender::Balrog, $character->gender);
	// 	assertEquals("", $character->lastName);
	// }
}
