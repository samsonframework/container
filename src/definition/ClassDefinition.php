<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.08.16
 * Time: 0:46.
 */
namespace samsonframework\container\definition;

use samsonframework\container\definition\exception\MethodDefinitionNotFoundException;
use samsonframework\container\definition\exception\PropertyDefinitionNotFoundException;
use samsonframework\container\definition\reference\ClassReference;
use samsonframework\container\definition\reference\ReferenceInterface;
use samsonframework\container\definition\scope\AbstractScope;
use samsonframework\container\definition\exception\MethodDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\PropertyDefinitionAlreadyExistsException;
use samsonframework\container\definition\exception\ScopeAlreadyExistsException;
use samsonframework\container\definition\exception\ScopeNotFoundException;

/**
 * Class ClassDefinition
 *
 * @author Ruslan Molodyko <molodyko@samsonos.com>
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
    /** @var bool Is singleton */
    protected $isSingleton = false;
    /** @var bool Is class definition was analyzed */
    protected $isAnalyzed = false;

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
    public function defineIsPrototype(): ClassBuilderInterface
    {
        $this->isSingleton = false;

        return $this;
    }

    /** {@inheritdoc} */
    public function defineIsSingleton(): ClassBuilderInterface
    {
        $this->isSingleton = true;

        return $this;
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
     * @param string $nameSpace
     * @return ClassDefinition
     */
    public function setNameSpace(string $nameSpace): ClassDefinition
    {
        $this->nameSpace = $nameSpace;

        return $this;
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
     * Get class name
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string|ClassReference $className
     * @return ClassDefinition
     * @throws \InvalidArgumentException
     */
    public function setClassName($className): ClassDefinition
    {
        if ($className instanceof ClassReference) {
            $this->className = $className->getClassName();
        } elseif (is_string($className)) {
            $this->className = $className;
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getServiceName()
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

    /**
     * @return boolean
     */
    public function isSingleton(): bool
    {
        return $this->isSingleton;
    }

    /**
     * @param boolean $isSingleton
     * @return ClassDefinition
     */
    public function setIsSingleton(bool $isSingleton): ClassDefinition
    {
        $this->isSingleton = $isSingleton;

        return $this;
    }

    /**
     * @return PropertyDefinition[]
     */
    public function getPropertiesCollection(): array
    {
        return $this->propertiesCollection;
    }

    /**
     * @return MethodDefinition[]
     */
    public function getMethodsCollection(): array
    {
        return $this->methodsCollection;
    }

    /**
     * Has property definition
     *
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->propertiesCollection);
    }

    /**
     * Get property definition
     *
     * @param $propertyName
     * @return PropertyDefinition
     * @throws PropertyDefinitionNotFoundException
     */
    public function getProperty($propertyName): PropertyDefinition
    {
        if (!$this->hasProperty($propertyName)) {
            throw new PropertyDefinitionNotFoundException();
        }
        return $this->propertiesCollection[$propertyName];
    }

    /**
     * Has method definition
     *
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName): bool
    {
        return array_key_exists($methodName, $this->methodsCollection);
    }

    /**
     * Get method definition
     *
     * @param $methodName
     * @return MethodDefinition
     * @throws MethodDefinitionNotFoundException
     */
    public function getMethod($methodName): MethodDefinition
    {
        if (!$this->hasMethod($methodName)) {
            throw new MethodDefinitionNotFoundException();
        }
        return $this->methodsCollection[$methodName];
    }

    /**
     * @return boolean
     */
    public function isAnalyzed(): bool
    {
        return $this->isAnalyzed;
    }

    /**
     * @param boolean $isAnalyzed
     * @return ClassDefinition
     */
    public function setIsAnalyzed(bool $isAnalyzed): ClassDefinition
    {
        $this->isAnalyzed = $isAnalyzed;

        return $this;
    }
}
