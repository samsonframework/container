<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 07.08.16 at 13:04
 */
namespace samsonframework\container\resolver;

use Doctrine\Common\Annotations\Reader;
use samsonframework\container\annotation\MethodInterface;
use samsonframework\container\annotation\PropertyInterface;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;
use samsonframework\container\metadata\PropertyMetadata;

/**
 * Class properties annotation resolver.
 */
class AnnotationPropertyResolver implements Resolver
{
    /** Property typeHint hint pattern */
    const P_PROPERTY_TYPE_HINT = '/@var\s+(?<class>[^\s]+)/';

    /** @var Reader */
    protected $reader;

    /** @var ClassMetadata */
    protected $classMetadata;

    /**
     * AnnotationPropertyResolver constructor.
     *
     * @param mixed         $reader
     * @param ClassMetadata $classMetadata
     */
    public function __construct(Reader $reader, ClassMetadata $classMetadata)
    {
        $this->reader = $reader;
        $this->classMetadata = $classMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($classData, $identifier = null)
    {
        /** @var \ReflectionClass $classData */

        /** @var \ReflectionMethod $method */
        foreach ($classData->getMethods() as $method) {
            $this->resolveMethodAnnotation($method, $classMetadata);
        }

        return $this->classMetadata;
    }

    /**
     * Resolve all method annotations.
     *
     * @param \ReflectionMethod $method
     * @param ClassMetadata     $metadata
     */
    protected function resolveMethodAnnotation(\ReflectionMethod $method, ClassMetadata $metadata)
    {
        // Create method metadata instance
        $methodMetadata = new MethodMetadata();
        $methodMetadata->name = $method->getName();
        $methodMetadata->modifiers = $method->getModifiers();
        $methodMetadata->parameters = $method->getParameters();

        /** @var MethodInterface $annotation */
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof MethodInterface) {
                $methodMetadata->options[] = $annotation->toMethodMetadata($methodMetadata);
            }
        }

        $metadata->methodsMetadata[$method->getName()] = $methodMetadata;
    }

    /**
     * Resolve all class property annotations.
     *
     * @param \ReflectionProperty $property
     * @param ClassMetadata       $classMetadata
     */
    protected function resolveClassPropertyAnnotations(\ReflectionProperty $property, ClassMetadata $classMetadata)
    {
        // Create method metadata instance
        $propertyMetadata = new PropertyMetadata($classMetadata);
        $propertyMetadata->name = $property->getName();
        $propertyMetadata->modifiers = $property->getModifiers();

        // Parse property type hint if present
        if (preg_match('/@var\s+(?<class>[^\s]+)/', $property->getDocComment(), $matches)) {
            list(, $propertyMetadata->typeHint) = $matches;
        }

        /** @var PropertyInterface $annotation Read class annotations */
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof PropertyInterface) {
                $annotation->toPropertyMetadata($propertyMetadata);
            }
        }

        $classMetadata->propertyMetadata[$propertyMetadata->name] = $propertyMetadata;
    }
}
