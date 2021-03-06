<?php

namespace AskLucy;

/**
 * A logical operator
 */
class RangeType
{
    /**
     * Inclusive
     *
     * @var string
     */
    public const INCLUSIVE = 'inclusive';

    /**
     * Exclusive
     *
     * @var string
     */
    public const EXCLUSIVE = 'exclusive';

    /**
     * Opening bracket for inclusive range
     *
     * @var string
     */
    private const BRACKET_OPENING_INCLUSIVE = '[';

    /**
     * Closing bracket for inclusive range
     *
     * @var string
     */
    private const BRACKET_CLOSING_INCLUSIVE = ']';

    /**
     * Opening bracket for exclusive range
     *
     * @var string
     */
    private const BRACKET_OPENING_EXCLUSIVE = '{';

    /**
     * Closing bracket for exclusive range
     *
     * @var string
     */
    private const BRACKET_CLOSING_EXCLUSIVE = '}';

    /**
     * A list of valid range types
     *
     * @var array
     */
    private const RANGE_TYPE_CODES = [
        self::INCLUSIVE,
        self::EXCLUSIVE
    ];

    /**
     * The code of the range type
     *
     * @var string
     */
    private $rangeTypeCode;

    /**
     * Constructs a range type.
     *
     * @param string $rangeTypeCode A range type code
     *
     * @throws \Exception Exception for an invalid range type.
     */
    public function __construct(string $rangeTypeCode = self::INCLUSIVE)
    {
        if (in_array($rangeTypeCode, self::RANGE_TYPE_CODES)) {
            $this->rangeTypeCode = $rangeTypeCode;
        } else {
            throw new \Exception('Invalid range type "' . $rangeTypeCode . '"!');
        }
    }

    /**
     * Returns the opening bracket.
     *
     * @return string
     */
    public function getOpeningBracket(): string
    {
        return (self::INCLUSIVE === $this->rangeTypeCode)
            ? self::BRACKET_OPENING_INCLUSIVE
            : self::BRACKET_OPENING_EXCLUSIVE;
    }

    /**
     * Returns the closing bracket.
     *
     * @return string
     */
    public function getClosingBracket(): string
    {
        return (self::INCLUSIVE === $this->rangeTypeCode)
            ? self::BRACKET_CLOSING_INCLUSIVE
            : self::BRACKET_CLOSING_EXCLUSIVE;
    }
}
