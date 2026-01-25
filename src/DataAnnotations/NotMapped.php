<?php declare(strict_types=1);
namespace Belin\Sql\DataAnnotations;

/**
 * Denotes that a property should be excluded from database mapping.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class NotMapped {}
