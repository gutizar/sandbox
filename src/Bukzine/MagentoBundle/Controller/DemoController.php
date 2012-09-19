<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bukzine\MagentoBundle\Entity\Task;
use Bukzine\MagentoBundle\Entity\Tag;
use Bukzine\MagentoBundle\Form\Type\TaskType;

class DemoController extends Controller
{
    public function collectionAction()
    {
    	$task = new Task();
    	$request = $this->getRequest();

        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $tag1 = new Tag();
        $tag1->name = 'tag1';
        $task->getTags()->add($tag1);
        $tag2 = new Tag();
        $tag2->name = 'tag2';
        $task->getTags()->add($tag2);
        // end dummy code

        $form = $this->createForm(new TaskType(), $task);

        // process the form on POST
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                // maybe do some form processing, like saving the Task and Tag objects
            }
        }

        return $this->render('BukzineMagentoBundle:Demo:collection.html.twig', array(
        	'form' => $form->createView(),
        ));
    }
}

?>