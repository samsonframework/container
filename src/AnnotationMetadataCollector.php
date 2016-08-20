<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:39
 */
namespace samsonframework\container;

/**
 * Annotation class metadata collector.
 * Class resolves and collects class metadata from annotations.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class AnnotationMetadataCollector extends AbstractMetadataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect($classes) : array
    {
        /** @var array $classes */
        $classesMetadata = [];

        foreach ($classes as $className) {
            $classesMetadata[] = $this->resolver->resolve(new \ReflectionClass($className));
        }

        return $classesMetadata;
    }
}
