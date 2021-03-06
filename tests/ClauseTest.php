<?php
namespace AskLucy\Test;

use AskLucy\Clause;
use AskLucy\Phrase;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for all clause implementations.
 *
 * @see Phrase
 */
abstract class ClauseTest extends TestCase
{
    /**
     * Returns a clause for testing.
     *
     * @return Clause
     */
    abstract protected function getTestClause(?string $constructorArgumentField = null): Clause;

    /**
     * Tests, if __toString() renders the field specification set by setField().
     *
     * @return void
     */
    public function test__toStringRendersFieldSpecificationSetBySetField(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');

        $this->assertRegExp(
            '/(field:).?a/',
            (string) $query,
            'Expected setField() to prepend the field specification "field:" to the query.'
        );
    }

    /**
     * Tests, if __toString() renders the field specification set by __construct().
     *
     * @return void
     */
    public function test__toStringRendersFieldSpecificationSetBy__construct(): void
    {
        $query = $this->getTestClause('field');

        $this->assertRegExp(
            '/(field:).?a/',
            (string) $query,
            'Expected __construct() to prepend the field specification "field:" to the query.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the field separator, when the clause was instantiated without constructor
     * argument and setField() was not used.
     *
     * @return void
     */
    public function test__toStringDoesNotRendersFieldSeparatorForQueryInstantiatedWithoutConstructorArgument(): void
    {
        $query = $this->getTestClause();

        $this->assertFalse(
            strstr($query, ':'),
            'Expected that __toString() doesn\'t render the field separator ":", if the clause was instantiated without constructor argument and setField() was never called.'
        );
    }

    /**
     * Tests, if __toString() doesn't render any field specification, when the query was instantiated with an empty
     * string as constructor argument and setField() was never used.
     *
     * @return void
     */
    public function test__toStringDoesNotRendersFieldSpecificationForQueryInstantiatedWithEmptyStringAsConstructorArgument(): void
    {
        $query = $this->getTestClause('');

        $this->assertFalse(
            strstr($query, ':'),
            'Expected that __toString() doesn\'t render the field separator ":", if the clause was instantiated with an empty string as constructor argument.'
        );
    }

    /**
     * Tests, if setField() overwrites the constructor argument and __toString() renders the last set value.
     *
     * @return void
     */
    public function testSetFieldOverwritesConstructorArgument(): void
    {
        $query = $this->getTestClause('field');
        $query->setField('otherField');

        $this->assertRegExp(
            '/(otherField:).?a/',
            (string) $query,
            'Expected field specification (otherField:) prepended to the query, because "otherField" was last set by setField().'
        );
    }

    /**
     * Tests, if setField() overwrites a value set by setField() before and __toString() renders the last set value.
     *
     * @return void
     */
    public function testSetFieldOverwritesFieldSetBefore(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');
        $query->setField('otherField');

        $this->assertRegExp(
            '/(otherField:).?a/',
            (string) $query,
            'Expected field specification (otherField:) prepended to the query, because "otherField" was last set by setField().'
        );
    }

    /**
     * Tests, if setField() unsets a field set by setField() before, if no argument is given.
     *
     * @return void
     */
    public function testSetFieldUnsetsFieldSetBeforeIfNotArgumentIsGiven(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');
        $query->setField();

        $this->assertFalse(
            strstr($query, 'field'),
            'Expected no field specification prepended, because setField() was last called without argument.'
        );
    }

    /**
     * Tests, if optional() doesn't modify the clause.
     *
     * @return void
     */
    public function testOptional(): void
    {
        $clause = $this->getTestClause();
        $originalClause = (string) $clause;

        $clause->optional();

        $this->assertSame(
            $originalClause,
            (string) $clause,
            'Expected the clause to be the same after calling optional() as before.'
        );
    }

    /**
     * Tests, if optional() overwrites an operator set before.
     *
     * @return void
     */
    public function testOptionalOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $originalClause = (string) $clause;

        $clause->required();
        $clause->prohibited();
        $clause->optional();

        $this->assertSame(
            $originalClause,
            (string) $clause,
            'Expected optional() to overwrite operators set before.'
        );
    }

    /**
     * Tests, if required() prepends the operator symbol "+" to the clause.
     *
     * @return void
     */
    public function testRequired(): void
    {
        $clause = $this->getTestClause();
        $clause->required();

        $this->assertRegExp(
            '/\+.?a/',
            (string) $clause,
            'Expected required() to prepend the operator symbol "+" to the clause.'
        );
    }

    /**
     * Tests, if required() overwrites an operator set before.
     *
     * @return void
     */
    public function testRequiredOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $clause->optional();
        $clause->prohibited();
        $clause->required();

        $this->assertRegExp(
            '/\+.?a/',
            (string) $clause,
            'Expected required() to overwrite operators set before.'
        );
    }

    /**
     * Tests, if prohibited() prepends the operator symbol "-" to the clause.
     *
     * @return void
     */
    public function testProhibited(): void
    {
        $clause = $this->getTestClause();
        $clause->prohibited();

        $this->assertRegExp(
            '/\-.?a/',
            (string) $clause,
            'Expected prohibited() to prepend the operator symbol "-" to the clause.'
        );
    }

    /**
     * Tests, if prohibited() overwrites an operator set before.
     *
     * @return void
     */
    public function testProhibitedOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $clause->optional();
        $clause->required();
        $clause->prohibited();

        $this->assertRegExp(
            '/\-.?a/',
            (string) $clause,
            'Expected prohibited() to overwrite operators set before.'
        );
    }

    /**
     * Tests, if the rendered clause contains the boost, if set.
     *
     * @return void
     */
    public function testBoost(): void
    {
        $clause = $this->getTestClause();
        $clause->boost(2.5);

        $this->assertContains(
            '^2.5',
            (string) $clause,
            'Expected that the clause contains the boost "^2.5".'
        );
    }

    /**
     * Tests, if the rendered clause contains the boost, if set with an integer value.
     *
     * @return void
     */
    public function testBoostWithInteger(): void
    {
        $clause = $this->getTestClause();
        $clause->boost(2);

        $this->assertContains(
            '^2',
            (string) $clause,
            'Expected that the clause contains the boost "^2".'
        );
    }

    /**
     * Tests, if the rendered clause contains the boost value with shortened decimal zeros.
     *
     * @return void
     */
    public function testBoostRederedIntegerIfIntegerIsGivenAsFloat(): void
    {
        $clause = $this->getTestClause();
        $clause->boost(2.0);

        $this->assertRegExp(
            '/\^2(?!.|0)/',
            (string) $clause,
            'Expected that the clause contains the boost "^2" as integer, when it was given as "2.0".'
        );
    }

    /**
     * Tests, if the rendered clause contains the boost value with shortened decimal zeros.
     *
     * @return void
     */
    public function testBoostShortensTrailingDecimalZeros(): void
    {
        $clause = $this->getTestClause();
        $clause->boost(2.10);

        $this->assertRegExp(
            '/\^2.1(?!0)/',
            (string) $clause,
            'Expected that the clause contains the boost "^2.1", where the value is rendered without trailing decimal zeros.'
        );
    }

    /**
     * Tests, if the rendered clause doesn't contain boost specification by default.
     *
     * @return void
     */
    public function testBoostDoesNotContainBoostSpecificationByDefault(): void
    {
        $clause = $this->getTestClause();

        $this->assertNotContains(
            '^',
            (string) $clause,
            'Expected that the clause doesn\'t contain the boost operator "^" by default.'
        );
    }

    /**
     * Tests, if the rendered clause doesn't contain boost specification if set to default value 1.0.
     *
     * @return void
     */
    public function testBoostDoesNotContainBoostSpecificationIfSetToDefault(): void
    {
        $clause = $this->getTestClause();
        $clause->boost(1.0);

        $this->assertNotContains(
            '^',
            (string) $clause,
            'Expected that the clause doesn\'t contain the boost operator "^" if set to default 1.0.'
        );
    }
}
