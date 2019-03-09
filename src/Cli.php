<?php

namespace Kritik94\Weather;

use \Docopt;

class Cli
{
    const DOC = <<<DOC
Find out your weather!

Usage:
  weather <city>

Options:
  -h --help     Show this screen.

DOC;

    public static function main()
    {
        $cliResponse = Docopt::handle(static::DOC);

        $city = $cliResponse->args['<city>'];

        $weatherApp = new Weather();
        try {
            $result = $weatherApp->getInfoByCity($city);

            $formatRows = array_map(function ($key, $value) {
                return "$key: $value";
            }, array_keys($result), $result);

            echo "City: $city" . PHP_EOL . implode(PHP_EOL, $formatRows) . PHP_EOL;
        } catch (CityNotFoundException $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}
