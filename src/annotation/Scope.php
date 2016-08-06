<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;

/**
 * Class Scope.
 *
 * @Annotation
 */
class Scope implements ParentInterface
{
    /** @var array Collection of class scopes */
    public $scopes = [];

    /**
     * Scope constructor.
     *
     * @param string|array $scopeOrScopes Class scopes
     *
     * @throws \Exception Thrown when neither string nor string[] is passed
     */
    public function __construct($scopeOrScopes)
    {
        if ($scopeOrScopes) {
            $value = $scopeOrScopes['value'];

            if (!is_array($value) && !is_string($value)) {
                throw new \Exception('Wrong type of alias');
            }

            // Always store array
            $this->scopes = is_array($value) ?: [$value];
        }
    }
}
