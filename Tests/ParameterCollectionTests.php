<?php declare(strict_types=1);
namespace Belin\Sql;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertCount, assertEmpty, assertEquals, assertFalse, assertInstanceOf, assertTrue};

/**
 * Tests the features of the {@see ParameterCollection} class.
 */
#[TestDox("ParameterCollection")]
final class ParameterCollectionTests extends TestCase {

	#[Test, TestDox("add()")]
	public function add(): void {
		$parameters = new ParameterCollection;
		assertEmpty($parameters);

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
		assertEmpty($parameters);
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

	#[Test, TestDox("insert()")]
	public function insert(): void {
		$parameters = new ParameterCollection;

		$parameters->insert(0, new Parameter("foo", 123));
		assertCount(1, $parameters);
		assertEquals(":foo", $parameters[0]->name);

		$parameters->insert(1, new Parameter("bar", 456));
		assertCount(2, $parameters);
		assertEquals(":bar", $parameters[1]->name);

		$parameters->insert(0, new Parameter("baz", 789));
		assertCount(3, $parameters);
		assertEquals(":baz", $parameters[0]->name);
		assertEquals(":foo", $parameters[1]->name);
		assertEquals(":bar", $parameters[2]->name);

		$this->expectException(\OutOfRangeException::class);
		$parameters->insert(4, new Parameter("qux"));
	}

	#[Test, TestDox("of()")]
	public function of(): void {
		$parameters = ParameterCollection::of([123, 456]);
		assertCount(2, $parameters);
		assertEquals("?1", $parameters[0]->name);
		assertEquals(123, $parameters[0]->value);
		assertEquals("?2", $parameters[1]->name);
		assertEquals(456, $parameters[1]->value);

		$parameters = ParameterCollection::of(["foo" => 123, "bar" => 456]);
		assertCount(2, $parameters);
		assertEquals(":foo", $parameters[0]->name);
		assertEquals(123, $parameters[0]->value);
		assertEquals(":bar", $parameters[1]->name);
		assertEquals(456, $parameters[1]->value);

		$parameters = ParameterCollection::of([new Parameter("foo", 123), ["bar", 456], "baz" => 789, "qux"]);
		assertCount(4, $parameters);
		assertEquals(":foo", $parameters[0]->name);
		assertEquals(123, $parameters[0]->value);
		assertEquals(":bar", $parameters[1]->name);
		assertEquals(456, $parameters[1]->value);
		assertEquals(":baz", $parameters[2]->name);
		assertEquals(789, $parameters[2]->value);
		assertEquals("?4", $parameters[3]->name);
		assertEquals("qux", $parameters[3]->value);
	}

	#[Test, TestDox("offsetExists()")]
	public function offsetExists(): void {
		$parameters = new ParameterCollection;
		assertFalse(isset($parameters[0]));
		assertFalse(isset($parameters["foo"]));
		assertFalse(isset($parameters[":foo"]));

		$parameters->add(["foo", 123]);
		assertTrue(isset($parameters[0]));
		assertTrue(isset($parameters["foo"]));
		assertTrue(isset($parameters[":foo"]));
	}

	#[Test, TestDox("offsetGet()")]
	public function offsetGet(): void {
		$parameters = new ParameterCollection(["foo", 123]);
		assertEquals(":foo", $parameters[0]->name);
		assertEquals(":foo", $parameters["foo"]->name);

		$this->expectException(\OutOfRangeException::class);
		$parameters[1]; // @phpstan-ignore expr.resultUnused
	}

	#[Test, TestDox("offsetSet()")]
	public function offsetSet(): void {
		$parameters = new ParameterCollection;

		$parameters[] = ["foo", 123];
		assertEquals(":foo", $parameters[":foo"]->name);
		$parameters[0] = new Parameter("bar", 456);
		assertEquals(":bar", $parameters[0]->name);

		$this->expectException(\OutOfRangeException::class);
		$parameters[":foo"]; // @phpstan-ignore expr.resultUnused
	}

	#[Test, TestDox("offsetUnset()")]
	public function offsetUnset(): void {
		$parameters = new ParameterCollection(["foo", 123], ["bar", 456]);
		assertCount(2, $parameters);

		unset($parameters["foo"]);
		assertCount(1, $parameters);
		unset($parameters[0]);
		assertEmpty($parameters);

		$this->expectException(\OutOfRangeException::class);
		unset($parameters["bar"]);
	}

	#[Test, TestDox("remove()")]
	public function remove(): void {
		$parameter = new Parameter("foo", 123);

		$parameters = new ParameterCollection($parameter);
		assertTrue($parameters->contains($parameter));

		$parameters->remove(new Parameter("foo", 123));
		assertTrue($parameters->contains($parameter));

		$parameters->remove($parameter);
		assertFalse($parameters->contains($parameter));
	}

	#[Test, TestDox("removeAt()")]
	public function removeAt(): void {
		$parameters = new ParameterCollection(["foo", 123], ["bar", 456]);
		assertCount(2, $parameters);

		$parameters->removeAt("foo");
		assertCount(1, $parameters);
		$parameters->removeAt(0);
		assertEmpty($parameters);

		$this->expectException(\OutOfRangeException::class);
		$parameters->removeAt("bar");
	}
}
