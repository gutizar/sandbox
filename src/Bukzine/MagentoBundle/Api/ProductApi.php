<?php

namespace Bukzine\MagentoBundle\Api;

use Bukzine\MagentoBundle\Api\ApiResponse;
use Bukzine\MagentoBundle\Entity\Book;

class ProductApi
{
	protected $client;

	public function __construct($endpoint)
	{
		$this->client = new \SoapClient($endpoint);
	}

	public function createBook($book, $content, $url)
	{
		$session = $this->client->login('danielg', 'admin123');

		// TODO: Book specific data has to be updated once the document is created.
		$productData = array(
			'categories' => array('36', '38'),
			'websites' => array('1'),
			'name' => $book->getName(),
			'status' => '1',
			'tax_class_id' => '4',
			'price' => $book->getPrice(),
			'description' => $book->getDescription(),
			'short_description' => $book->getShortDescription(),
			'number_pages' => $book->getNumPages(),
			'book_author_id' => $book->getAuthorId(),
			'book_author' => $book->getAuthorName(),
			);

		$response = new ApiResponse();

		try 
		{
			$productId = $this->client->call($session, 'catalog_product.create', array(
				'downloadable', 
				'64', 
				$book->getSku(), 
				$productData));

			$response->setResponse($productId);

			$linkData = array(
				'title' => $book->getName(),
				'is_unlimited' => '1',
				'type' => 'url',
				'link_url' => $url,
				);

			// Create download link
			$this->client->call($session, 'product_downloadable_link.add', array(
				$productId,
				$linkData,
				'link',
				null,
				'id'
				));

			$productDataUpdate = array(
				'links_purchased_separately' => '0',
				'links_title' => 'eBook',
			);

			// Update the product object so that the link cannot be download separately
			$this->client->call($session, 'catalog_product.update', array(
				$productId,
				$productDataUpdate,
				'1',
				'id'
				));

			$response->setSuccess(true);
		}
		catch (\Exception $ex)
		{
			$response->setSuccess(false);
			$response->setError($ex->getMessage());
		}
		
		return $response;
	}

	// public function listBooks($id)
	// {
	// 	$session = $this->client->login('danielg', 'admin123');

	// 	$response = new ApiResponse();

	// 	try 
	// 	{
	// 		$filter = array(array('book_author_id'=>array('eq' => $id)));

	// 		$result = $this->client->call($session, 'catalog_product.list', $filter);

	// 		$books = array();

	// 		foreach ($result as $value)
	// 		{
	// 			$currentBook = new Book();
	// 			$currentBook->setId($value['product_id']);
	// 			$currentBook->setSku($value['sku']);
	// 			$currentBook->setName($value['name']);

	// 			array_push($books, $currentBook);
	// 		}

	// 		$response->setResponse($books);
	// 		$response->setSuccess(true);
	// 	} 
	// 	catch (\Exception $ex) 
	// 	{
	// 		$response->setSuccess(false);
	// 		$response->setError($ex->getMessage());
	// 	}

	// 	return $response;
	// }

	// public function listBooks($id, $page, $limit)
	// {
	// 	$response = new ApiResponse();

	// 	try 
	// 	{

	// 		$collection = \Mage::getModel('catalog/product')->getCollection()
	// 			->addAttributeToSelect('name')
 //                ->addFieldToFilter('book_author_id', array('eq' => $id))
 //                ->addAttributeToSort('product_id', 'desc')
 //                ->load();

	// 		$books = array();

	// 		foreach ($collection as $item)
	// 		{
	// 			$currentBook = new Book();
	// 			$currentBook->setId($item->getId());
	// 			$currentBook->setSku($item->getSku());
	// 			$currentBook->setName($item->getName());

	// 			array_push($books, $currentBook);
	// 		}

	// 		$response->setResponse($books);
	// 		$response->setSuccess(true);
	// 	} 
	// 	catch (\Exception $ex) 
	// 	{
	// 		$response->setSuccess(false);
	// 		$response->setError($ex->getMessage());
	// 	}

	// 	return $response;
	// }

	public function listBooks($id, $page, $limit)
	{
		$response = new ApiResponse();

		try 
		{
			$collection = \Mage::getModel('catalog/product')->getCollection()
				->addAttributeToSelect('name')
                ->addFieldToFilter('book_author_id', array('eq' => $id))
                ->addAttributeToSort('name', 'desc')
                ->load();

			$response->setResponse($collection);
			$response->setSuccess(true);
		} 
		catch (\Exception $ex) 
		{
			$response->setSuccess(false);
			$response->setError($ex->getMessage());
		}

		return $response;
	}
}