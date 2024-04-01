<?php

declare(strict_types=1);

namespace Shapecode\Doctrine\DBAL\Types;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;

use function date_create;
use function is_string;

class DateTimeUTCType extends DateTimeType
{
    public const DATETIMEUTC = 'datetimeutc';

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string|null
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof DateTimeInterface) {
            throw InvalidType::new(
                $value,
                self::DATETIMEUTC,
                ['null', DateTimeInterface::class],
            );
        }

        return DateTimeImmutable::createFromInterface($value)
            ->setTimezone(new DateTimeZone('UTC'))
            ->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): DateTime|null
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTime) {
            return $value->setTimezone(new DateTimeZone('UTC'));
        }

        if (! is_string($value)) {
            throw ValueNotConvertible::new(
                $value,
                DateTime::class,
            );
        }

        $dateTime = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            new DateTimeZone('UTC'),
        );

        if ($dateTime === false) {
            $dateTime = date_create($value, new DateTimeZone('UTC'));
        }

        if ($dateTime === false) {
            throw InvalidFormat::new(
                $value,
                self::DATETIMEUTC,
                $platform->getDateTimeFormatString(),
            );
        }

        return $dateTime;
    }
}
