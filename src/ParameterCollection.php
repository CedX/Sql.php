<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Collects all parameters relevant to a parameterized SQL statement.
 * @implements \ArrayAccess<int, Parameter>
 * @implements \IteratorAggregate<int, Parameter>
 */
class ParameterCollection implements \ArrayAccess, \Countable, \IteratorAggregate {

	/**
	 * The internal parameter list.
	 * @var Parameter[]
	 */
	private array $parameters = [];

	/**
	 * Creates a new parameter list.
	 * @param Parameter|Parameter[] ...$parameters The collection whose elements are copied to the parameter list.
	 */
	public function __construct(Parameter|array ...$parameters) {
		foreach ($parameters as $parameter) {
			if (is_array($parameter)) array_push($this->parameters, ...$parameter);
			else $this->parameters[] = $parameter;
		}
	}

	/**
	 *
	 * @param array<int, mixed>|array<string, mixed> $parameters
	 * @return self
	 */
	public static function ofArray(array $parameters): self {
		// TODO
	}

	/**
	 * Gets the number of parameters contained in this collection.
	 * @return int The number of parameters contained in this collection.
	 */
	public function count(): int {
		return count($this->parameters);
	}

	/**
	 * Returns a new iterator that allows iterating the parameters of this collection.
	 * @return \ArrayIterator<int, Parameter> An iterator for the parameters of this collection.
	 */
	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->parameters);
	}

	/**
	 * Returns the index of the parameter with the specified name.
	 * @param string $name The parameter name.
	 * @return int The index of the parameter with the specified name, or `-1` if not found.
	 */
	public function indexOf(string $name): int {
		$normalizedName = Parameter::normalizeName($name);
		$index = array_find_key($this->parameters, fn($parameter) => $parameter->name == $normalizedName);
		return $index === null ? -1 : (int) $index;
	}

	/**
	 * Gets a value indicating whether a parameter in this collection has the specified index or name.
	 * @param int|string $index The parameter index or name.
	 * @return bool `true` if a parameter has the specified index or name, otherwise `false`.
	 */
	public function offsetExists(mixed $index): bool {
		if (is_string($index)) $index = $this->indexOf($index);
		return isset($this->parameters[$index]);
	}

	/**
	 * Gets the parameter at the specified index or name.
	 * @param int|string $index The parameter index or name.
	 * @return Parameter|null The parameter at the specified index or name, or `null` if not found.
	 */
	public function offsetGet(mixed $index): mixed {
		if (is_string($index)) $index = $this->indexOf($index);
		return $this->parameters[$index] ?? null;
	}

	/**
	 * Sets the parameter at the specified index or name.
	 * @param int|string $index The parameter index or name.
	 * @param Parameter $parameter The parameter to set.
	 */
	public function offsetSet(mixed $index, mixed $parameter): void {
		if (is_string($index)) $index = $this->indexOf($index);
		$this->parameters[$index] = $parameter;
	}

	/**
	 * Removes a parameter from this collection.
	 * @param int|string $index The parameter index or name.
	 */
	public function offsetUnset(mixed $index): void {
		if (is_string($index)) $index = $this->indexOf($index);
		unset($this->parameters[$index]);
	}
}
