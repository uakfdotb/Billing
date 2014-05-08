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

class EstimateItemController extends Controller
{
    /**
     * Get the list of items
     *
     * @Get("/estimates/{estimate}/items")
     *
     * @param ParamFetcher $paramFetcher
     * @param string $page integer with the page number (requires param_fetcher_listener: force)
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     */
    public function getEstimateItemsAction(ParamFetcherInterface $paramFetcher, $estimate)
    {
        $page = $paramFetcher->get('page');
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select(array('p'))
            ->from('AppClientBundle:ClientEstimateItem', 'p')
            ->andWhere('p.idEstimate = :estimate')
            ->setParameter('estimate', $estimate)
        ;

        $data = new ApiResponse($this, $query, $page);
        $view = new View($data);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Get the article
     *
     * @Get("/estimates/{estimate}/items/{id}")
     *
     * @param string $article path
     * @return View view instance
     *
     */
    public function getEstimateItemAction($article, Request $request, $estimate, $id)
    {
        $query = explode('/', $request->getUri());
        $id = end($query);
        $data = $this->getDoctrine()->getRepository('AppClientBundle:ClientEstimateItem')->findOneBy([
            'id' => $id,
            'idEstimate' => $estimate
        ]);

        // using explicit View creation
        $view = new View($data);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    protected function getForm($article = null)
    {
        return $this->createForm(new \App\ClientBundle\Form\ClientEstimateItemType(), $article);
    }

    /**
     * Create a new resource
     *
     * @Post("/estimates/{estimate}/item")
     *
     * @param Request $request
     * @return View view instance
     *
     */
    public function postEstimateItemAction(Request $request, $estimate)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->getForm();

        $data = json_decode($request->getContent());

        // Set estimate
        $data->idEstimate = $estimate;

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
     * @Put("/estimates/{estimate}/items/{id}")
     */
    public function PutEstimateItemAction(Request $request, $estimate, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $data = json_decode($request->getContent());

        // Set initial data as per database
        $initial = $this->getDoctrine()->getRepository('AppClientBundle:ClientEstimateItem')->findOneBy([
            'id' => $id,
            'idEstimate' => $estimate
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
     * @Delete("/estimates/{estimate}/items/{id}")
     */
    public function deleteEstimateItemAction($estimate, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $recurring = $this->getDoctrine()->getRepository('AppClientBundle:ClientEstimateItem')->findOneBy([
            'id' => $id,
            'idEstimate' => $estimate
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