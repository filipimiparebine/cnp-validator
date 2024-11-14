<?php

declare(strict_types=1);

namespace WiseTest;

class Cnp
{
    const LENGTH = 13;
    const ELEMENTS = [
        'sex' => ['start' => 0, 'length' => 1, 'max' => 9],
        'year' => ['start' => 1, 'length' => 2, 'max' => 99],
        'month' => ['start' => 3, 'length' => 2, 'max' => 12],
        'day' => ['start' => 5, 'length' => 2, 'max' => 31],
        'county' => ['start' => 7, 'length' => 2, 'max' => 52],
        'number' => ['start' => 9, 'length' => 3, 'max' => 999],
    ];
    private const KEY = '279146358279';


    public static function isValid(string $cnp): bool
    {
        if (strlen($cnp) !== self::LENGTH || !ctype_digit($cnp))
            return false;

        foreach (self::ELEMENTS as $element => $rule)
            if (!self::isValidElement($cnp, $rule))
                return false;

        if (!self::isValidDate($cnp))
            return false;

        return self::isValidControl($cnp);
    }

    private static function isValidElement(string $cnp, array $rule): bool
    {
        $value = (int) substr($cnp, $rule['start'], $rule['length']);
        return $value <= $rule['max'];
    }

    private static function isValidDate(string $cnp): bool
    {
        $day = (int) substr($cnp, self::ELEMENTS['day']['start'], self::ELEMENTS['day']['length']);
        $month = (int) substr($cnp, self::ELEMENTS['month']['start'], self::ELEMENTS['month']['length']);
        $year = self::getFullYear($cnp);

        return $year ? checkdate($month, $day, $year) : true;
    }

    private static function getFullYear(string $cnp): ?int
    {
        $sex = (int) $cnp[0];
        $year = (int) substr($cnp, self::ELEMENTS['year']['start'], self::ELEMENTS['year']['length']);

        return match ($sex) {
            1, 2 => 1900 + $year,
            3, 4 => 1800 + $year,
            5, 6 => 2000 + $year,
            default => null
        };
    }

    private static function isValidControl(string $cnp): bool
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++)
            $sum += (int) $cnp[$i] * (int) self::KEY[$i];

        $remainder = $sum % 11;
        $expectedControl = $remainder === 10 ? 1 : $remainder;

        return (int) $cnp[12] === $expectedControl;
    }
}
