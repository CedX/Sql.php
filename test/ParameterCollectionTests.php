<?php declare(strict_types=1);
namespace Belin\Sql;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertCount, assertEquals, assertFalse, assertInstanceOf, assertTrue};

/**
 * Tests the features of the {@see ParameterCollection} class.
 */
#[TestDox("ParameterCollection")]
final class ParameterCollectionTests extends TestCase {

	#[Test, TestDox("add()")]
	public function add(): void {
		$parameters = new ParameterCollection;
		assertCount(0, $parameters);

		$parameters->add(["?1", 123, DbType::Integer]);
		assertCount(1, $parameters);

		$parameter = $parameters[0];
		assertInstanceOf(Parameter::class, $parameter);
		assertEquals("?1", $parameter->name);
		assertEquals(123, $parameter->value);
		assertEquals(DbType::Integer, $parameter->dbType);
	}

	#[Test, TestDox("clear()")]
	public function clear(): void {
		$parameters = new ParameterCollection(new Parameter("foo"));
		assertCount(1, $parameters);
		$parameters->clear();
		assertCount(0, $parameters);
	}

	#[Test, TestDox("contains()")]
	public function contains(): void {
		$parameter = new Parameter("foo");
		$parameters = new ParameterCollection($parameter);

		assertTrue($parameters->contains($parameter));
		assertTrue($parameters->contains("foo"));
		assertTrue($parameters->contains(":foo"));

		assertFalse($parameters->contains(new Parameter("bar")));
		assertFalse($parameters->contains("bar"));
		assertFalse($parameters->contains(":bar"));
	}

	#[Test, TestDox("indexOf()")]
	public function indexOf(): void {
		$parameter = new Parameter("foo");
		$parameters = new ParameterCollection($parameter);

		assertEquals(0, $parameters->indexOf($parameter));
		assertEquals(0, $parameters->indexOf("foo"));
		assertEquals(0, $parameters->indexOf(":foo"));

		assertEquals(-1, $parameters->indexOf(new Parameter("bar")));
		assertEquals(-1, $parameters->indexOf("bar"));
		assertEquals(-1, $parameters->indexOf(":bar"));

		$parameters->add(new Parameter(":baz"));
		assertEquals(1, $parameters->indexOf("baz"));
		assertEquals(1, $parameters->indexOf(":baz"));
	}
}
