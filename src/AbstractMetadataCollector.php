<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:39
 */
namespace samsonframework\container;

use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\ResolverInterface;

/**
 * Abstract class metadata collector.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
abstract class AbstractMetadataCollector
{
    /**
     * Metadata collector constructor.
     *
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Collect class metadata from source.
     *
     * @param mixed $source Input source for collecting class metadata
     *
     * @return ClassMetadata[] Collected class metadata collection
     */
    abstract public function collect($source) : array;
}
