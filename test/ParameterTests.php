<?php declare(strict_types=1);
namespace Belin\Sql;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox, TestWith};
use function PHPUnit\Framework\{assertEquals};

/**
 * Tests the features of the {@see Parameter} class.
 */
#[TestDox("Parameter")]
final class ParameterTests extends TestCase {

	#[Test, TestDox("name")]
	#[TestWith(["", "?"], "Empty string")]
	#[TestWith(["?", "?"], "Question mark")]
	#[TestWith(["?1", "?1"], "Numbered question mark")]
	#[TestWith(["foo", ":foo"], "Name")]
	#[TestWith([":bar", ":bar"], "Prefixed name")]
	public function getName(string $name, string $expected): void {
		assertEquals($expected, new Parameter($name)->name);
	}

	#[Test, TestDox("of()")]
	#[TestWith(["?1", 123, DbType::Integer])]
	#[TestWith(["name" => "?1", "value" => 123, "dbType" => DbType::Integer])]
	public function of(string $name, mixed $value, ?DbType $dbType): void {
		$parameter = Parameter::of([$name, $value, $dbType]);
		assertEquals("?1", $parameter->name);
		assertEquals(123, $parameter->value);
		assertEquals(DbType::Integer, $parameter->dbType);
	}
}
