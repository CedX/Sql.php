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
	 * @return array
	 */
	// public static function testData(): array {
	// 	return [
	// 		// TODO
	// 	];
	// }

	// #[Test, TestDox("changeType()"), DataProvider("testData")]
	// public function changeType(): void {
	// 	// TODO
	// }

	#[Test, TestDox("createInstance()")]
	public function createInstance(): void {
		$mapper = Mapper::instance();
		$properties = [
			"Class" => "Bard/minstrel",
			"FirstName" => "Cédric",
			"Gender" => CharacterGender::Balrog->name,
			"LastName" => "Belin"
		];

		$instance = $mapper->createInstance($properties);
		assertEquals("Bard/minstrel", $instance->{"Class"});
		assertEquals("Cédric", $instance->FirstName);
		assertEquals(CharacterGender::Balrog->name, $instance->Gender);
		assertNull($instance->LastName);

		$character = $mapper->createInstance($properties, Character::class);
		assertEquals("Cédric", $character->firstName);
		assertEquals(CharacterGender::Balrog, $character->gender);
		assertEquals("", $character->lastName);
	}
}
