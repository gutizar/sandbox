<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bukzine\MagentoBundle\Form\Type\UploadFileType;
use Bukzine\MagentoBundle\Form\Type\BookType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Bukzine\MagentoBundle\Entity\Document;
use Bukzine\MagentoBundle\Entity\Book;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BukzineMagentoBundle:Default:index.html.twig', array('name' => $name));
    }

    public function homeAction()
    {
        return $this->render('BukzineMagentoBundle:Default:home.html.twig');
    }

    public function uploadAction()
    {
    	$form = $this->createForm(new UploadFileType());

    	return $this->render('BukzineMagentoBundle:Default:upload.html.twig', array(
    		'form' => $form->createView()
    	));
    }

    public function handleUploadAction()
    {
    	$document = new Document();

    	$request = $this->getRequest();

    	$loggedInUser = $this->get('security.context')->getToken()->getUser();

    	if ($this->getRequest()->getMethod() === 'POST')
    	{
            if ($request->request->has('name'))
            {
                $document->setName($request->request->get('name', null, false));
            }
            else
            {
               $document->setName($loggedInUser->getId() . '_' .$loggedInUser->getFirstname() . '_' . $loggedInUser->getLastname() . '_' . 'eBook'); 
            }

	    	$document->file = new File($request->files->get('file'));
	    	$document->setPath($document->file->getFilename());
	    	$document->setMimeType($document->file->getMimeType());

	    	$em = $this->getDoctrine()->getEntityManager();
            $em->persist($document);
            $em->flush();

            $publishUrl = $this->get('router')->generate('magento_publish_book', array('id' => $document->getId()), true);

	        $content = array(
	        	'jsonrpc' => '2.0', 
	        	'id' => 'id', 
	        	'result' => $document->getName(),
	        	'url' => $publishUrl
	        	);

	        $response = new Response();
	        $response->headers->set('Content-type', 'application/json');
	        $response->setContent(json_encode($content));

	        return $response;
    	}

    	return $this->render('BukzineMagentoBundle:Default:upload.html.twig');
    }

    public function updateAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
	    $document = $em->getRepository('BukzineMagentoBundle:Document')->find($id);

	    if (!$document) {
	        throw $this->createNotFoundException('No document found for id '.$id);
	    }

	    $document->setStatus('UPDATED');
	    $em->flush();

	    return $this->redirect($this->generateUrl('magento_home'));
    }

    public function publishAction($id)
    {
    	$request = $this->getRequest();
    	$error = '';

    	$em = $this->getDoctrine()->getEntityManager();
	    $document = $em->getRepository('BukzineMagentoBundle:Document')->find($id);

	    $pdf = new \Fpdi_Fpdi();
		$pagecount = $pdf->setSourceFile($document->getAbsolutePath());
        $loggedInUser = $this->get('security.context')->getToken()->getUser();

    	$libro = new Book();
    	$libro->setNumPages($pagecount);
        $libro->setAuthorId($loggedInUser->getId());
        $libro->setAuthorName($loggedInUser->getFirstname() . ' ' . $loggedInUser->getLastname());
    	$form = $this->createForm(new BookType(), $libro);

    	if ($request->getMethod() == 'POST')
    	{
    		// Persist book to Magento
    		$form->bindRequest($request);

    		if ($form->isValid())
    		{
    			// Invoke Magento API to create the book in Magento.
    			$productApi = $this->get('magento.api.product');

    			$libro->setSku($this->slugify(
    				$libro->getName()
    				));

    			$result = $productApi->createBook($libro, $document, 
    				$this->get('router')->generate('magento_download_book', array('id' => $id), true));

    			if ($result->getSuccess())
    			{
    				// Use the resulting productId to update the document
    				$document->setStatus('UPDATED');
    				$document->setBookId($result->getResponse());
	    			$em->flush();

    				$this->get('session')->setFlash('info',
    					'Your book was published properly!');

    				return $this->redirect($this->generateUrl('magento_home'));
    			}
    			else
    			{
    				$this->get('session')->setFlash('error',
                        'There was a problem completing your request');

                    $error = 'There was a problem completing your request (' . $result->getError() . ')';
    			}
    		}
    	}

    	return $this->render('BukzineMagentoBundle:Default:publish.html.twig', array(
    		'form' => $form->createView(),
    		'documentId' => $id,
    		'error' => $error,
    		));
    }

    public function downloadAction($id)
    {
    	if (\Mage::getSingleton('customer/session', array('name'=>'frontend'))->isLoggedIn())
        {
            $em = $this->getDoctrine()->getEntityManager();

            $entity = $em->getRepository('BukzineMagentoBundle:Document')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find file.');
            }   

            $headers = array(
                'Content-Type' => $entity->getMimeType(),
                'Content-Disposition' => 'attachment; filename="'.$entity->getName().'"'
            );  

            $filename = $entity->getUploadRootDir().'/'.$entity->getPath();

            return new Response(file_get_contents($filename), 200, $headers);
        }
        else 
        {
            $this->get('session')->setFlash('info',
                        'You can not download this file, you are not logged in!');

            return $this->redirect($this->generateUrl('magento_home'));  
        }
    }

    public function listBooksAction()
    {
        $loggedInUser = $this->get('security.context')->getToken()->getUser();

        $productApi = $this->get('magento.api.product');

        if ($this->getRequest()->query->has('page'))
        {
            $page = $this->getRequest()->query->get('page', null, false);
        }
        else
        {
            $page = 1;
        }

        $result = $productApi->listBooks($loggedInUser->getId(), $page, 3);

        if ($result->getSuccess())
        {

            $paginator = $this->get('knp_paginator'); //(1)

            $target = $result->getResponse(); //(2) array('a', 'b', ... 'u');
            // uses event subscribers to paginate $target
            
            $slice = $paginator->paginate($target, $page, 3/*limit*/); //(3)
            // $slice is a pagination view, represents the paginated data

            return $this->render('BukzineMagentoBundle:Default:list.html.twig', array(
                'books' => $slice,
                'error' => '',
            ));
        } 
        else
        {
            return $this->render('BukzineMagentoBundle:Default:list.html.twig', array(
                'error' => $result->getError(),
            ));
        }   
    }

    /**
     * @param string text Text to slugify
     * @return string
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
