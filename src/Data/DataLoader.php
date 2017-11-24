<?php

namespace Webas\Domain\Data;

use Braincrafted\Json\Json;

/**
 * DataLoader
 *
 * @subpackage data
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  (c) 2014 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class DataLoader
{
    /**
     * Loads data from a JSON file and returns data as a Data object.
     *
     * @param string $filename Filename of data file.
     *
     * @return Data Data object.
     */
    public function load($filename)
    {
        if (false === file_exists($filename)) {
            throw new DataLoadException(sprintf('Could not load "%s" because file does not exist.', $filename));
        }

        return new Data(Json::decode(file_get_contents($filename), true));
    }
}
