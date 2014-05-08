<?php
namespace App\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\View\RouteRedirectView,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Delete,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Request\ParamFetcherInterface;

use App\ApiBundle\ApiResponse;

class InvoiceItemController extends Controller
{
    /**
     * Get the list of items
     *
     * @Get("/invoices/{invoice}/items")
     *
     * @param ParamFetcher $paramFetcher
     * @param string $page integer with the page number (requires param_fetcher_listener: force)
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     */
    public function getInvoiceItemsAction(ParamFetcherInterface $paramFetcher, $invoice)
    {
        $page = $paramFetcher->get('page');
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select(array('p'))
            ->from('AppClientBundle:ClientInvoiceItem', 'p')
            ->andWhere('p.idInvoice = :invoice')
            ->setParameter('invoice', $invoice)
        ;

        $data = new ApiResponse($this, $query, $page);
        $view = new View($data);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Get the article
     *
     * @Get("/invoices/{invoice}/items/{id}")
     *
     * @param string $article path
     * @return View view instance
     *
     */
    public function getInvoiceItemAction($article, Request $request, $invoice, $id)
    {
        $query = explode('/', $request->getUri());
        $id = end($query);
        $data = $this->getDoctrine()->getRepository('AppClientBundle:ClientInvoiceItem')->findOneBy([
            'id' => $id,
            'idInvoice' => $invoice
        ]);

        // using explicit View creation
        $view = new View($data);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    protected function getForm($article = null)
    {
        return $this->createForm(new \App\ClientBundle\Form\ClientInvoiceItemType(), $article);
    }

    /**
     * Create a new resource
     *
     * @Post("/invoices/{invoice}/item")
     *
     * @param Request $request
     * @return View view instance
     *
     */
    public function postInvoiceItemAction(Request $request, $invoice)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->getForm();

        $data = json_decode($request->getContent());

        // Set invoice
        $data->idInvoice = $invoice;

        // Set all other data as per REST
        foreach($data as $key => $value) $bind[$key] = $value;
        $form->bind($bind);

        if ($form->isValid()) {
            $article = $form->getData();

            $em->persist($article);
            $em->flush();
            // Note: normally one would likely create/update something in the database
            // and/or send an email and finally redirect to the newly created or updated resource url
            $response = ['id' => $article->getId()];
            $view = new View($response);
        } else {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Edit item
     * @Put("/invoices/{invoice}/items/{id}")
     */
    public function PutInvoiceItemAction(Request $request, $invoice, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $data = json_decode($request->getContent());

        // Set initial data as per database
        $initial = $this->getDoctrine()->getRepository('AppClientBundle:ClientInvoiceItem')->findOneBy([
            'id' => $id,
            'idInvoice' => $invoice
        ]);
        if(!is_null($initial))
        {
            $form = $this->getForm($initial);

            // Set all other data as per REST
            foreach($data as $key => $value) $bind[$key] = $value;
            $form->bind($bind);

            if ($form->isValid()) {
                $article = $form->getData();

                $em->persist($article);
                $em->flush();
                // Note: normally one would likely create/update something in the database
                // and/or send an email and finally redirect to the newly created or updated resource url
                $response = ["status" => "Success"];
                $view = new View($response);
            } else {
                $view = View::create($form);
            }
        }
        else
        {
            $response = ["status" => "Not found"];
            $view = new View($response);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Delete item
     * @Delete("/invoices/{invoice}/items/{id}")
     */
    public function deleteInvoiceItemAction($invoice, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $recurring = $this->getDoctrine()->getRepository('AppClientBundle:ClientInvoiceItem')->findOneBy([
            'id' => $id,
            'idInvoice' => $invoice
        ]);

        if(!is_null($recurring))
        {
            $em->remove($recurring);
            $em->flush();
            $response = ["status" => "Success"];
        }
        else
        {
            $response = ["status" => "Not found"];
        }
        $view = new View($response);

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}