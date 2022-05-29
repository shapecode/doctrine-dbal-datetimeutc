<?php

declare(strict_types=1);

namespace Shapecode\Doctrine\DBAL\Types;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

use function date_create;
use function get_debug_type;
use function is_string;
use function sprintf;

class DateTimeUTCType extends DateTimeType
{
    public const DATETIMEUTC = 'datetimeutc';

    public function getName(): string
    {
        return self::DATETIMEUTC;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof DateTimeInterface) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', DateTimeInterface::class]
            );
        }

        return DateTimeImmutable::createFromInterface($value)
            ->setTimezone(new DateTimeZone('UTC'))
            ->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?DateTime
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTime) {
            return $value->setTimezone(new DateTimeZone('UTC'));
        }

        if (! is_string($value)) {
            throw ConversionException::conversionFailedUnserialization(
                DateTime::class,
                sprintf('wrong type "%s"', get_debug_type($value))
            );
        }

        $dateTime = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            new DateTimeZone('UTC')
        );

        if ($dateTime === false) {
            $dateTime = date_create($value, new DateTimeZone('UTC'));
        }

        if ($dateTime === false) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
