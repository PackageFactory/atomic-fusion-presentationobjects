<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Helper;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeTemplate;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Exception\NodeException;

/**
 * The easily accessible dummy node for usage in unit tests, e.g. for presentation object factories
 */
class DummyNode implements NodeInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var array
     */
    public $properties = [];

    /**
     * @var NodeType
     */
    public $nodeType;

    /**
     * @var bool
     */
    public $hidden = false;

    /**
     * @var \DateTime
     */
    public $hiddenBeforeDateTime;

    /**
     * @var \DateTime
     */
    public $hiddenAfterDateTime;

    /**
     * @var bool
     */
    public $hiddenInIndex = false;

    /**
     * @var array
     */
    public $accessRoles = [];

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $contextPath;

    /**
     * @var Workspace
     */
    public $workspace;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var int
     */
    public $index;

    /**
     * @var NodeInterface
     */
    public $parent;

    /**
     * @var array|NodeInterface[]
     */
    public $childNodesByPath = [];

    /**
     * @var bool
     */
    public $removed = false;

    /**
     * @var bool
     */
    public $accessible = true;

    /**
     * @var Context
     */
    public $context;

    /**
     * @var array
     */
    public $dimensions;

    /**
     * @var array|NodeInterface[]
     */
    public $otherVariants;

    /**
     * Set the name of the node to $newName, keeping it's position as it is
     *
     * @param string $newName
     * @return void
     * @throws \InvalidArgumentException if $newName is invalid
     * @api
     */
    public function setName($newName)
    {
        $this->name = $newName;
    }

    /**
     * Returns the name of this node
     *
     * @return string
     * @api
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a full length plain text label of this node
     *
     * @return string
     * @api
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the specified property.
     *
     * If the node has a content object attached, the property will be set there
     * if it is settable.
     *
     * @param string $propertyName Name of the property
     * @param mixed $value Value of the property
     * @return void
     * @api
     */
    public function setProperty($propertyName, $value)
    {
        $this->properties[$propertyName] = $value;
    }

    /**
     * If this node has a property with the given name.
     *
     * If the node has a content object attached, the property will be checked
     * there.
     *
     * @param string $propertyName Name of the property to test for
     * @return boolean
     * @api
     */
    public function hasProperty($propertyName)
    {
        return isset($this->properties[$propertyName]);
    }

    /**
     * Returns the specified property.
     *
     * If the node has a content object attached, the property will be fetched
     * there if it is gettable.
     *
     * @param string $propertyName Name of the property
     * @return mixed value of the property
     * @api
     */
    public function getProperty($propertyName)
    {
        return $this->properties[$propertyName] ?? null;
    }

    /**
     * Removes the specified property.
     *
     * If the node has a content object attached, the property will not be removed on
     * that object if it exists.
     *
     * @param string $propertyName Name of the property
     * @return void
     * @throws NodeException if the node does not contain the specified property
     * @api
     */
    public function removeProperty($propertyName)
    {

        if (!isset($this->properties[$propertyName])) {
            throw new NodeException('Unknown property "' . $propertyName . '".');
        }

        unset($this->properties[$propertyName]);
    }

    /**
     * Returns all properties of this node.
     *
     * If the node has a content object attached, the properties will be fetched
     * there.
     *
     * @return array Property values, indexed by their name
     * @api
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Returns the names of all properties of this node.
     *
     * @return array Property names
     * @api
     */
    public function getPropertyNames()
    {
        return array_keys($this->properties);
    }

    /**
     * Sets a content object for this node.
     *
     * @param object $contentObject The content object
     * @return void
     * @throws \InvalidArgumentException if the given contentObject is no object.
     * @api
     */
    public function setContentObject($contentObject)
    {
        throw new \DomainException('Content objects are not supported.');
    }

    /**
     * Returns the content object of this node (if any).
     *
     * @api
     */
    public function getContentObject()
    {
        throw new \DomainException('Content objects are not supported.');
    }

    /**
     * Unsets the content object of this node.
     *
     * @return void
     * @api
     */
    public function unsetContentObject()
    {
        throw new \DomainException('Content objects are not supported.');
    }

    /**
     * Sets the node type of this node.
     *
     * @param NodeType $nodeType
     * @return void
     * @api
     */
    public function setNodeType(NodeType $nodeType)
    {
        $this->nodeType = $nodeType;
    }

    /**
     * Returns the node type of this node.
     *
     * @return NodeType
     * @api
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * Sets the "hidden" flag for this node.
     *
     * @param boolean $hidden If true, this Node will be hidden
     * @return void
     * @api
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the current state of the hidden flag
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the date and time when this node becomes potentially visible.
     *
     * @param \DateTime $dateTime Date before this node should be hidden
     * @return void
     * @api
     */
    public function setHiddenBeforeDateTime(\DateTime $dateTime = null)
    {
        $this->hiddenBeforeDateTime = $dateTime;
    }

    /**
     * Returns the date and time before which this node will be automatically hidden.
     *
     * @return \DateTime Date before this node will be hidden
     * @api
     */
    public function getHiddenBeforeDateTime()
    {
        return $this->hiddenBeforeDateTime;
    }

    /**
     * Sets the date and time when this node should be automatically hidden
     *
     * @param \DateTime $dateTime Date after which this node should be hidden
     * @return void
     * @api
     */
    public function setHiddenAfterDateTime(\DateTime $dateTime = null)
    {
        $this->hiddenAfterDateTime = $dateTime;
    }

    /**
     * Returns the date and time after which this node will be automatically hidden.
     *
     * @return \DateTime Date after which this node will be hidden
     * @api
     */
    public function getHiddenAfterDateTime()
    {
        return $this->hiddenAfterDateTime;
    }

    /**
     * Sets if this node should be hidden in indexes, such as a site navigation.
     *
     * @param boolean $hidden true if it should be hidden, otherwise false
     * @return void
     * @api
     */
    public function setHiddenInIndex($hidden)
    {
        $this->hiddenInIndex = $hidden;
    }

    /**
     * If this node should be hidden in indexes
     *
     * @return boolean
     * @api
     */
    public function isHiddenInIndex()
    {
        return $this->hiddenInIndex;
    }

    /**
     * Sets the roles which are required to access this node
     *
     * @param array $accessRoles
     * @return void
     * @api
     */
    public function setAccessRoles(array $accessRoles)
    {
        $this->accessRoles = $accessRoles;
    }

    /**
     * Returns the names of defined access roles
     *
     * @return array
     * @api
     */
    public function getAccessRoles()
    {
        return $this->accessRoles;
    }

    /**
     * Returns the path of this node
     *
     * Example: /sites/mysitecom/homepage/about
     *
     * @return string The absolute node path
     * @api
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the absolute path of this node with additional context information (such as the workspace name).
     *
     * Example: /sites/mysitecom/homepage/about@user-admin
     *
     * @return string Node path with context information
     * @api
     */
    public function getContextPath()
    {
        return $this->contextPath;
    }

    /**
     * Returns the level at which this node is located.
     * Counting starts with 0 for "/", 1 for "/foo", 2 for "/foo/bar" etc.
     *
     * @return integer
     * @api
     */
    public function getDepth()
    {
        return $this->path ? \mb_substr_count($this->path, '/') - 1 : 0;
    }

    /**
     * Sets the workspace of this node.
     *
     * This method is only for internal use by the content repository. Changing
     * the workspace of a node manually may lead to unexpected behavior.
     *
     * @param Workspace $workspace
     * @return void
     */
    public function setWorkspace(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     * Returns the workspace this node is contained in
     *
     * @return Workspace
     * @api
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * Returns the identifier of this node.
     *
     * This UUID is not the same as the technical persistence identifier used by
     * Flow's persistence framework. It is an additional identifier which is unique
     * within the same workspace and is used for tracking the same node in across
     * workspaces.
     *
     * It is okay and recommended to use this identifier for synchronisation purposes
     * as it does not change even if all of the nodes content or its path changes.
     *
     * @return string the node's UUID
     * @api
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the index of this node
     *
     * This method is for internal use and must only be used by other nodes!
     *
     * @param integer $index The new index
     * @return void
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Returns the index of this node which determines the order among siblings
     * with the same parent node.
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Returns the parent node of this node
     *
     * @return NodeInterface The parent node or NULL if this is the root node
     * @api
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns the parent node path
     *
     * @return string Absolute node path of the parent node
     * @api
     */
    public function getParentPath()
    {
        return $this->parent->getPath();
    }

    /**
     * Creates, adds and returns a child node of this node. Also sets default
     * properties and creates default subnodes.
     *
     * @param string $name Name of the new node
     * @param NodeType $nodeType Node type of the new node (optional)
     * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
     * @throws \InvalidArgumentException if the node name is not accepted.
     * @api
     */
    public function createNode($name, NodeType $nodeType = null, $identifier = null)
    {
        throw new \DomainException('Creating dummy nodes is not supported.');
    }

    /**
     * Creates, adds and returns a child node of this node, without setting default
     * properties or creating subnodes.
     *
     * For internal use only!
     *
     * @param string $name Name of the new node
     * @param NodeType $nodeType Node type of the new node (optional)
     * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
     */
    public function createSingleNode($name, NodeType $nodeType = null, $identifier = null)
    {
        throw new \DomainException('Creating dummy nodes is not supported.');
    }

    /**
     * Creates and persists a node from the given $nodeTemplate as child node
     *
     * @param \Neos\ContentRepository\Domain\Model\NodeTemplate $nodeTemplate
     * @param string $nodeName name of the new node. If not specified the name of the nodeTemplate will be used.
     * @api
     */
    public function createNodeFromTemplate(NodeTemplate $nodeTemplate, $nodeName = null)
    {
        throw new \DomainException('Creating dummy nodes is not supported.');
    }

    /**
     * Returns a node specified by the given relative path.
     *
     * @param string $path Path specifying the node, relative to this node
     * @return NodeInterface The specified node or NULL if no such node exists
     * @api
     */
    public function getNode($path)
    {
        return $this->childNodesByPath[$path] ?? null;
    }

    /**
     * Returns the primary child node of this node.
     *
     * Which node acts as a primary child node will in the future depend on the
     * node type. For now it is just the first child node.
     *
     * @return NodeInterface The primary child node or NULL if no such node exists
     * @api
     */
    public function getPrimaryChildNode()
    {
        return $this->childNodesByPath['main'] ?? null;
    }

    /**
     * Returns all direct child nodes of this node.
     * If a node type is specified, only nodes of that type are returned.
     *
     * @param string $nodeTypeFilter If specified, only nodes with that node type are considered
     * @param integer $limit An optional limit for the number of nodes to find. Added or removed nodes can still change the number nodes!
     * @param integer $offset An optional offset for the query
     * @return array<\Neos\ContentRepository\Domain\Model\NodeInterface> An array of nodes or an empty array if no child nodes matched
     * @api
     */
    public function getChildNodes($nodeTypeFilter = null, $limit = null, $offset = null)
    {
        return $this->childNodesByPath;
    }

    /**
     * Checks if this node has any child nodes.
     *
     * @param string $nodeTypeFilter If specified, only nodes with that node type are considered
     * @return boolean true if this node has child nodes, otherwise false
     * @api
     */
    public function hasChildNodes($nodeTypeFilter = null)
    {
        return !empty($this->childNodesByPath);
    }

    /**
     * Removes this node and all its child nodes. This is an alias for setRemoved(true)
     *
     * @return void
     * @api
     */
    public function remove()
    {
        $this->setRemoved(true);
    }

    /**
     * Removes this node and all its child nodes or sets ONLY this node to not being removed.
     *
     * @param boolean $removed If true, this node and it's child nodes will be removed or set to be not removed.
     * @return void
     * @api
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;
    }

    /**
     * If this node is a removed node.
     *
     * @return boolean
     * @api
     */
    public function isRemoved()
    {
        return $this->removed;
    }

    /**
     * Tells if this node is "visible".
     * For this the "hidden" flag and the "hiddenBeforeDateTime" and "hiddenAfterDateTime" dates are
     * taken into account.
     *
     * @return boolean
     * @api
     * @throws \Exception
     */
    public function isVisible()
    {
        $now = new \DateTime();

        return !$this->isHidden() && !($this->hiddenBeforeDateTime && $now < $this->hiddenBeforeDateTime) && !($this->hiddenAfterDateTime && $now > $this->hiddenAfterDateTime);
    }

    /**
     * Tells if this node may be accessed according to the current security context.
     *
     * @return boolean
     * @api
     */
    public function isAccessible()
    {
        return $this->accessible;
    }

    /**
     * Tells if a node, in general,  has access restrictions, independent of the
     * current security context.
     *
     * @return boolean
     * @api
     */
    public function hasAccessRestrictions()
    {
        return !empty($this->accessRoles);
    }

    /**
     * Checks if the given $nodeType would be allowed as a child node of this node according to the configured constraints.
     *
     * @param NodeType $nodeType
     * @return boolean true if the passed $nodeType is allowed as child node
     */
    public function isNodeTypeAllowedAsChildNode(NodeType $nodeType)
    {
        return $this->nodeType->allowsChildNodeType($nodeType);
    }

    /**
     * Moves this node before the given node
     *
     * @param NodeInterface $referenceNode
     * @return void
     * @api
     */
    public function moveBefore(NodeInterface $referenceNode)
    {
        throw new \DomainException('Moving dummy nodes is not supported.');
    }

    /**
     * Moves this node after the given node
     *
     * @param NodeInterface $referenceNode
     * @return void
     * @api
     */
    public function moveAfter(NodeInterface $referenceNode)
    {
        throw new \DomainException('Moving dummy nodes is not supported.');
    }

    /**
     * Moves this node into the given node
     *
     * @param NodeInterface $referenceNode
     * @return void
     * @api
     */
    public function moveInto(NodeInterface $referenceNode)
    {
        throw new \DomainException('Moving dummy nodes is not supported.');
    }

    /**
     * Copies this node before the given node
     *
     * @param NodeInterface $referenceNode
     * @param string $nodeName
     * @api
     */
    public function copyBefore(NodeInterface $referenceNode, $nodeName)
    {
        throw new \DomainException('Copying dummy nodes is not supported.');
    }

    /**
     * Copies this node after the given node
     *
     * @param NodeInterface $referenceNode
     * @param string $nodeName
     * @api
     */
    public function copyAfter(NodeInterface $referenceNode, $nodeName)
    {
        throw new \DomainException('Copying dummy nodes is not supported.');
    }

    /**
     * Copies this node to below the given node. The new node will be added behind
     * any existing sub nodes of the given node.
     *
     * @param NodeInterface $referenceNode
     * @param string $nodeName
     * @api
     */
    public function copyInto(NodeInterface $referenceNode, $nodeName)
    {
        throw new \DomainException('Copying dummy nodes is not supported.');
    }

    /**
     * Return the NodeData representation of the node.
     */
    public function getNodeData()
    {
        throw new \DomainException('Dummy nodes do not use node data objects.');
    }

    /**
     * Return the context of the node
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Return the assigned content dimensions of the node.
     *
     * @return array An array of dimensions to array of dimension values
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Given a context a new node is returned that is like this node, but
     * lives in the new context.
     *
     * @param Context $context
     * @return NodeInterface
     */
    public function createVariantForContext($context)
    {
        throw new \DomainException('Creating dummy nodes is not supported.');
    }

    /**
     * Determine if this node is configured as auto-created childNode of the parent node. If that is the case, it
     * should not be deleted.
     *
     * @return boolean true if this node is auto-created by the parent.
     */
    public function isAutoCreated()
    {
        return isset($this->nodeType->getAutoCreatedChildNodes()[$this->name]);
    }

    /**
     * Get other variants of this node (with different dimension values)
     *
     * A variant of a node can have different dimension values and path (for non-aggregate nodes).
     * The resulting node instances might belong to a different context.
     *
     * @return array<NodeInterface> All node variants of this node (excluding the current node)
     */
    public function getOtherNodeVariants()
    {
        return $this->otherVariants;
    }
}
