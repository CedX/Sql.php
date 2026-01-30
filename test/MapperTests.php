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

	#[Test, TestDox("createInstance()")]
	public function createInstance(): void {
		$mapper = Mapper::instance();
		$properties = [
			"class" => "Bard/minstrel",
			"firstName" => "Cédric",
			"gender" => CharacterGender::Balrog->name,
			"lastName" => null
		];

		$instance = $mapper->createInstance($properties);
		assertEquals("Bard/minstrel", $instance->{"class"});
		assertEquals("Cédric", $instance->firstName);
		assertEquals(CharacterGender::Balrog->name, $instance->gender);
		assertNull($instance->lastName);

		$character = $mapper->createInstance($properties, Character::class);
		assertEquals("Cédric", $character->firstName);
		assertEquals(CharacterGender::Balrog, $character->gender);
		assertEquals("", $character->lastName);
	}
}
