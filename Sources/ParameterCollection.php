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
	 * @param Parameter|array<int|string, mixed> ...$parameters The collection whose elements are copied to the parameter list.
	 */
	public function __construct(Parameter|array ...$parameters) {
		$this->addRange($parameters);
	}

	/**
	 * Creates a new parameter collection from the specified parameters.
	 * @param array<int|string, mixed> $parameters The parameters to add to the created collection.
	 * @return self The parameter collection initialized from the specified parameters.
	 */
	public static function of(array $parameters): self {
		$collection = new self;

		$index = 0;
		foreach ($parameters as $offset => $parameter) {
			$index++;
			if (is_array($parameter) || $parameter instanceof Parameter) $collection->add($parameter);
			elseif (is_string($offset)) $collection->add(new Parameter($offset, $parameter));
			else $collection->add(new Parameter("?$index", $parameter));
		}

		return $collection;
	}

	/**
	 * Adds a parameter to the end of this collection.
	 * @param Parameter|array<int|string, mixed> $parameter The parameter to add.
	 */
	public function add(Parameter|array $parameter): void {
		$this->parameters[] = is_array($parameter) ? Parameter::of($parameter) : $parameter;
	}

	/**
	 * Adds the parameters of the specified collection to the end of this collection.
	 * @param iterable<Parameter|array<int|string, mixed>> $parameters The parameters to add.
	 */
	public function addRange(iterable $parameters): void {
		foreach ($parameters as $parameter) $this->add($parameter);
	}

	/**
	 * Removes all parameters from this collection.
	 */
	public function clear(): void {
		$this->parameters = array();
	}

	/**
	 * Gets a value indicating whether this collection contains the specified parameter.
	 * @param Parameter|string $parameter The parameter to locate.
	 * @return bool `true` if the parameter is found, otherwise `false`.
	 */
	public function contains(Parameter|string $parameter): bool {
		return $this->indexOf($parameter) >= 0;
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
	 * @param string $parameter The parameter to locate.
	 * @return int The index of the parameter, or `-1` if not found.
	 */
	public function indexOf(Parameter|string $parameter): int {
		if (!is_string($parameter)) return (int) (array_find_key($this->parameters, fn($value) => $value === $parameter) ?? -1);
		$normalizedName = Parameter::normalizeName($parameter);
		return (int) (array_find_key($this->parameters, fn($value) => $value->name == $normalizedName) ?? -1);
	}

	/**
	 * Inserts a parameter into this collection at the specified index.
	 * @param int $index The zero-based index at which the parameter should be inserted.
	 * @param Parameter|array<int|string, mixed> $parameter The parameter to insert.
	 * @throws \OutOfRangeException The specified index is invalid.
	 */
	public function insert(int $index, Parameter|array $parameter): void {
		if ($index < 0 || $index > $this->count()) throw new \OutOfRangeException("The specified index is invalid.");
		array_splice($this->parameters, $index, 0, [is_array($parameter) ? Parameter::of($parameter) : $parameter]);
	}

	/**
	 * Gets a value indicating whether a parameter in this collection has the specified index or name.
	 * @param int|string $offset The parameter index or name.
	 * @return bool `true` if a parameter has the specified index or name, otherwise `false`.
	 */
	public function offsetExists(mixed $offset): bool {
		$index = is_int($offset) ? $offset : $this->indexOf($offset);
		return isset($this->parameters[$index]);
	}

	/**
	 * Gets the parameter at the specified offset.
	 * @param int|string $offset The parameter index or name.
	 * @return Parameter The parameter at the specified offset.
	 * @throws \OutOfRangeException The specified offset is invalid.
	 */
	public function offsetGet(mixed $offset): mixed {
		$index = is_int($offset) ? $offset : $this->indexOf($offset);
		return $this->parameters[$index] ?? throw new \OutOfRangeException("The specified offset is invalid.");
	}

	/**
	 * Sets the parameter at the specified offset.
	 * @param int|string|null $offset The parameter index or name.
	 * @param Parameter|array<int|string, mixed> $parameter The parameter to set.
	 * @throws \OutOfRangeException The specified offset is invalid.
	 */
	public function offsetSet(mixed $offset, mixed $parameter): void {
		$index = is_int($offset)
			? ($offset >= 0 && $offset < $this->count() ? $offset : throw new \OutOfRangeException("The specified offset is invalid."))
			: ($offset === null ? -1 : $this->indexOf($offset));

		if ($index < 0) $this->add($parameter);
		else $this->parameters[$index] = is_array($parameter) ? Parameter::of($parameter) : $parameter;
	}

	/**
	 * Removes a parameter from this collection.
	 * @param int|string $offset The parameter index or name.
	 * @throws \OutOfRangeException The specified offset is invalid.
	 */
	public function offsetUnset(mixed $offset): void {
		$this->removeAt($offset);
	}

	/**
	 * Removes the first occurrence of the specified parameter from this collection.
	 * @param Parameter $parameter The parameter to remove.
	 */
	public function remove(Parameter $parameter): void {
		$index = $this->indexOf($parameter);
		if ($index >= 0) $this->removeAt($index);
	}

	/**
	 * Removes a parameter from this collection.
	 * @param int|string $offset The parameter index or name.
	 * @throws \OutOfRangeException The specified offset is invalid.
	 */
	public function removeAt(int|string $offset): void {
		$index = is_int($offset) ? $offset : $this->indexOf($offset);
		if (!isset($this->parameters[$index])) throw new \OutOfRangeException("The specified offset is invalid.");
		unset($this->parameters[$index]);
		$this->parameters = array_values($this->parameters);
	}

	/**
	 * Copies the parameters of this collection to a new array.
	 * @return Parameter[] An array containing the parameters of this collection.
	 */
	public function toArray(): array {
		return $this->parameters;
	}
}
