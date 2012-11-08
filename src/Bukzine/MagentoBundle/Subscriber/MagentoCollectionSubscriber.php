<?php

namespace Bukzine\MagentoBundle\Subscriber;

use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\ItemsEvent;
use Bukzine\MagentoBundle\Entity\Book;

class MagentoCollectionSubscriber implements EventSubscriberInterface
{
	private $items;

	// public function count(CountEvent $event)
 //    {
 //        $magCollection = $event->getTarget();

 //        if (is_a($magCollection, 'Mage_Catalog_Model_Resource_Product_Collection'))
 //        {
 //        	$this->items = $magCollection;
 //        	$event->setCount($magCollection->count());
 //        	$event->stopPropagation();
 //        }
 //    }

	public function items(ItemsEvent $event)
    {
    	if (is_a($event->target, 'Mage_Catalog_Model_Resource_Product_Collection'))
    	{
    		$books = array();

    		foreach ($event->target as $item)
    		{
    			$currentItem = new Book();
    			$currentItem->setName($item->getName());
    			$currentItem->setId($item->getId());
    			$currentItem->setSku($item->getSku());
    			array_push($books, $currentItem);
    		}

    		$event->count = $event->target->count();
    		$event->items = array_slice(
    			$books,
    			$event->getOffset(),
                $event->getLimit()
            );

    		$event->stopPropagation();
    	}
    }

    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 1 /*increased priority to override any internal*/),
            // 'knp_pager.count' => array('count', 1 /*increased priority*/)
        );
    }

}