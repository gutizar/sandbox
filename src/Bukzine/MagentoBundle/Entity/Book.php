<?php

namespace Bukzine\MagentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bukzine\MagentoBundle\Entity\Book
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bukzine\MagentoBundle\Entity\BookRepository")
 */
class Book
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $sku
     *
     * @ORM\Column(name="sku", type="string", length=255)
     */
    private $sku;

    /**
     * @var string $attribute_set
     *
     * @ORM\Column(name="attribute_set", type="string", length=10)
     */
    private $attribute_set;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=2)
     */
    private $status;

    /**
     * @var decimal $price
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string $short_description
     *
     * @ORM\Column(name="short_description", type="string", length=255)
     */
    private $short_description;

    /**
     * @var integer $num_pages
     *
     * @ORM\Column(name="num_pages", type="integer")
     */
    private $num_pages;

    /**
     * @var integer $author_id
     *
     */
    private $author_id;

    /**
     * @var string $author_name
     *
     */
    private $author_name;

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sku
     *
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set attribute_set
     *
     * @param string $attributeSet
     */
    public function setAttributeSet($attributeSet)
    {
        $this->attribute_set = $attributeSet;
    }

    /**
     * Get attribute_set
     *
     * @return string 
     */
    public function getAttributeSet()
    {
        return $this->attribute_set;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set short_description
     *
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    }

    /**
     * Get short_description
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set num_pages
     *
     * @param integer $numPages
     */
    public function setNumPages($numPages)
    {
        $this->num_pages = $numPages;
    }

    /**
     * Get num_pages
     *
     * @return integer 
     */
    public function getNumPages()
    {
        return $this->num_pages;
    }

    /**
     * Set author_id
     *
     * @param integer $author_id
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;
    }

    /**
     * Get author_id
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set author_name
     *
     * @param integer $author_name
     */
    public function setAuthorName($author_name)
    {
        $this->author_name = $author_name;
    }

    /**
     * Get author_name
     *
     * @return integer 
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }
}