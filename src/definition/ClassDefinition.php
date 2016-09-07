<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\scope\AbstractScope;
use samsonframework\container\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\exception\ScopeAlreadyExistsException;
use samsonframework\container\exception\ScopeNotFoundException;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\metadata\MethodMetadata;

/**
 * Class ClassDefinition
 *
 * @package samsonframework\container\definition
 */
class ClassDefinition extends AbstractDefinition implements ClassBuilderInterface
{
    /** @var string Class name with namespace */
    protected $className;
    /** @var string Class name space */
    protected $nameSpace;
    /** @var string Service name */
    protected $serviceName;
    /** @var array Class container scopes */
    protected $scopes = [];

    /** @var MethodDefinition[] Methods collection */
    protected $methodsCollection = [];
    /** @var PropertyDefinition[] Property collection */
    protected $propertiesCollection = [];

    /** {@inheritdoc} */
    public function defineConstructor(): MethodBuilderInterface
    {
        /** Add constructor method manually */
        return $this->defineMethod('__construct');
    }

    /** {@inheritdoc} */
    public function defineMethod(string $methodName): MethodBuilderInterface
    {
        if (array_key_exists($methodName, $this->methodsCollection)) {
            throw new MethodDefinitionAlreadyExistsException();
        }

        $methodDefinition = new MethodDefinition($this, $methodName);
        $methodDefinition->setMethodName($methodName);

        $this->methodsCollection[$methodName] = $methodDefinition;

        return $methodDefinition;
    }

    /** {@inheritdoc} */
    public function defineProperty(string $propertyName): PropertyBuilderInterface
    {
        if (array_key_exists($propertyName, $this->propertiesCollection)) {
            throw new PropertyDefinitionAlreadyExistsException();
        }

        $propertyDefinition = new PropertyDefinition($this);
        $propertyDefinition->setPropertyName($propertyName);

        $this->propertiesCollection[$propertyName] = $propertyDefinition;

        return $propertyDefinition;
    }

    /** {@inheritdoc} */
    public function toMetadata(): ClassMetadata
    {
        $classMetadata = new ClassMetadata();
        $classMetadata->className = $this->className;
        $classMetadata->name = $this->serviceName ?? $this->className;

        // Resolve methods
        if (count($this->methodsCollection)) {
            foreach ($this->methodsCollection as $methodDefinition) {
                $classMetadata->methodsMetadata[$methodDefinition->getMethodName()] =
                    $methodDefinition->toMethodMetadata($classMetadata);
            }
        }
        // Resolve properties
        if (count($this->propertiesCollection)) {
            foreach ($this->propertiesCollection as $propertyDefinition) {
                $classMetadata->propertiesMetadata[$propertyDefinition->getPropertyName()] =
                    $propertyDefinition->toPropertyMetadata($classMetadata);
            }
        }
        return $classMetadata;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNameSpace(): string
    {
        return $this->nameSpace;
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Add scope to definition
     *
     * @param AbstractScope $scope
     * @return ClassDefinition
     * @throws ScopeAlreadyExistsException
     */
    public function addScope(AbstractScope $scope): ClassDefinition
    {
        if ($this->hasScope($scope::getId())) {
            throw new ScopeAlreadyExistsException();
        }

        $this->scopes[$scope::getId()] = $scope;

        return $this;
    }

    /**
     * Remove scope from definition
     *
     * @param string $id
     * @return ClassDefinition
     * @throws ScopeNotFoundException
     */
    public function removeScope(string $id): ClassDefinition
    {
        if (!$this->hasScope($id)) {
            throw new ScopeNotFoundException();
        }

        unset($this->scopes[$id]);

        return $this;
    }

    /**
     * Check if scope exists in definition
     *
     * @param string $id
     * @return bool
     */
    public function hasScope(string $id): bool
    {
        return array_key_exists($id, $this->scopes);
    }

    /**
     * Get scope from definition
     *
     * @param string $id
     * @return mixed
     * @throws ScopeNotFoundException
     */
    public function getScope(string $id): AbstractScope
    {
        if (!$this->hasScope($id)) {
            throw new ScopeNotFoundException();
        }
        return $this->scopes[$id];
    }

    /**
     * Get all scopes
     *
     * @return AbstractScope[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param string $className
     * @return ClassDefinition
     */
    public function setClassName(string $className): ClassDefinition
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return ClassDefinition
     */
    public function setServiceName(string $serviceName): ClassDefinition
    {
        $this->serviceName = $serviceName;

        return $this;
    }
}
