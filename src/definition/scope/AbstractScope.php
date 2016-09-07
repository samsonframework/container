<?php
/**
 * Created by Ruslan Molodyko.
 * Date: 07.09.2016
 * Time: 5:30
 */
namespace samsonframework\container\definition\scope;

/**
 * Class ControllerScope
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class AbstractScope
{
    /**
     * Get scope id
     *
     * @return string
     */
    public static function getId(): string
    {
        return str_replace('scope', '', strtolower(static::class));
    }
}
