<?php

namespace AskLucy;

/**
 * A field
 */
class Field
{
    /**
     * The default field
     *
     * @var string
     */
    public const DEFAULT = '';

    /**
     * Separator between field name and clause
     *
     * @var string
     */
    public const FIELD_SEPARATOR = ':';

    /**
     * The name of the field
     *
     * @var string
     */
    private $name;

    /**
     * Constructs a field.
     *
     * @param string $name The name of the field
     */
    public function __construct(string $name = self::DEFAULT)
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the field.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (empty((string) $this->name))
            ? self::DEFAULT
            : $this->name . self::FIELD_SEPARATOR;
    }
}
