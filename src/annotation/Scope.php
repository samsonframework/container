<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.2016
 * Time: 1:55.
 */
namespace samsonframework\container\annotation;
use samsonframework\container\metadata\ClassMetadata;

/**
 * Class Scope.
 *
 * @Annotation
 */
class Scope implements MetadataInterface
{
    /** @var array Collection of class scopes */
    public $scopes = [];

    /**
     * Scope constructor.
     *
     * @param array $scopeOrScopes Class scopes
     *
     * @throws \Exception Thrown when neither string nor string[] is passed
     */
    public function __construct($scopeOrScopes)
    {
        if (is_array($scopeOrScopes) && array_key_exists('value', $scopeOrScopes)) {
            $value = $scopeOrScopes['value'];

            if (!is_array($value) && !is_string($value)) {
                throw new \Exception('Wrong type of alias');
            }

            // Always store array
            $this->scopes = is_array($value) ?: [$value];
        }
    }

    /** {@inheritdoc} */
    public function toMetadata(ClassMetadata &$metadata)
    {
        // Add all found annotation scopes to metadata collection
        $metadata->scopes = array_merge($metadata->scopes, $this->scopes);
    }
}
