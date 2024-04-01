<?php

declare(strict_types=1);

namespace Shapecode\Doctrine\DBAL\Tests\Types;

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shapecode\Doctrine\DBAL\Types\DateTimeUTCType;

use function assert;

class DateTimeUTCTypeTest extends TestCase
{
    protected AbstractPlatform & MockObject $abstractPlatform;

    protected DateTimeUTCType $dateTimeUTCType;

    public function setUp(): void
    {
        $this->abstractPlatform = $this->createMock(AbstractPlatform::class);
        $this->abstractPlatform->method('getDateTimeFormatString')->willReturn('Y-m-d H:i:s');

        if (! Type::hasType(DateTimeUTCType::DATETIMEUTC)) {
            Type::addType(DateTimeUTCType::DATETIMEUTC, DateTimeUTCType::class);
        }

        $type = Type::getType(DateTimeUTCType::DATETIMEUTC);
        assert($type instanceof DateTimeUTCType);

        $this->dateTimeUTCType = $type;
    }

    public function testConvertToDatabaseValueReturnsUTCString(): void
    {
        $dateTime = DateTime::createFromFormat(
            $this->abstractPlatform->getDateTimeFormatString(),
            '2014-11-27 13:16:31',
            new DateTimeZone('America/New_York'),
        );

        self::assertEquals(
            '2014-11-27 18:16:31',
            $this->dateTimeUTCType->convertToDatabaseValue($dateTime, $this->abstractPlatform),
        );
    }

    public function testConvertToDatabaseValueWrongType(): void
    {
        $this->expectException(InvalidType::class);

        $this->dateTimeUTCType->convertToDatabaseValue(1234, $this->abstractPlatform);
    }

    public function testConvertToPHPValueReturnsUTCDateTime(): void
    {
        self::assertEquals(
            'UTC',
            $this->dateTimeUTCType
                ->convertToPHPValue('2014-11-27 18:16:31', $this->abstractPlatform)
                ->getTimezone()
                ->getName(),
        );

        self::assertEquals(
            '2014-11-27 13:16:31',
            $this->dateTimeUTCType
                ->convertToPHPValue('2014-11-27 18:16:31', $this->abstractPlatform)
                ->setTimezone(new DateTimeZone('America/New_York'))
                ->format($this->abstractPlatform->getDateTimeFormatString()),
        );
    }

    public function testConvertToPHPValueReturnsUTCDateTimeFromDateCreate(): void
    {
        self::assertEquals(
            'UTC',
            $this->dateTimeUTCType
                ->convertToPHPValue('Thursday, 27-Nov-2014 18:16:31', $this->abstractPlatform)
                ->getTimezone()
                ->getName(),
        );

        self::assertEquals(
            '2014-11-27 13:16:31',
            $this->dateTimeUTCType
                ->convertToPHPValue('Thursday, 27-Nov-2014 18:16:31', $this->abstractPlatform)
                ->setTimezone(new DateTimeZone('America/New_York'))
                ->format($this->abstractPlatform->getDateTimeFormatString()),
        );
    }

    public function testConvertToPHPValueReturnsUTCDateTimeForDateTimeValue(): void
    {
        $dateTime = DateTime::createFromFormat(
            $this->abstractPlatform->getDateTimeFormatString(),
            '2014-11-27 13:16:31',
            new DateTimeZone('America/New_York'),
        );

        self::assertEquals(
            'UTC',
            $this->dateTimeUTCType->convertToPHPValue($dateTime, $this->abstractPlatform)->getTimezone()->getName(),
        );

        self::assertEquals(
            '2014-11-27 18:16:31',
            $this->dateTimeUTCType->convertToPHPValue($dateTime, $this->abstractPlatform)
                ->format($this->abstractPlatform->getDateTimeFormatString()),
        );
    }

    public function testConvertToPHPValueReturnsNullForNullValue(): void
    {
        /** @phpstan-ignore-next-line */
        self::assertNull($this->dateTimeUTCType->convertToPHPValue(null, $this->abstractPlatform));
    }

    public function testConvertToPHPValueThrowsConversionException(): void
    {
        $this->expectException(InvalidFormat::class);

        $this->dateTimeUTCType->convertToPHPValue('foo', $this->abstractPlatform);
    }

    public function testConvertToPHPValueThrowsWrongType(): void
    {
        $this->expectException(ValueNotConvertible::class);

        $this->dateTimeUTCType->convertToPHPValue(123, $this->abstractPlatform);
    }
}
