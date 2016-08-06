<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.2016
 * Time: 1:52.
 */

namespace samsonframework\di\util;

use hanneskod\classtools\Iterator\ClassIterator as ClassIteratorModule;
use Symfony\Component\Finder\Finder;

class ClassIterator
{
    /**
     * @var string Where find the classes
     */
    public static $path = [
        '../src',
        '../vendor/samsonframework/',
    ];

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * ClassIterator constructor.
     */
    public function __construct()
    {
        $this->finder = new Finder();
    }

    /**
     * Get class iterator.
     *
     * @return ClassIteratorModule
     */
    public function getIterator()
    {
        $iterator = new ClassIteratorModule($this->finder->ignoreUnreadableDirs()->in(self::$path));
        // Enable reflection by autoloading found classes
        $iterator->enableAutoloading();

        return $iterator;
    }
}
