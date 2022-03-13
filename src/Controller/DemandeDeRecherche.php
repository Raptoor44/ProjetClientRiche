<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\ApaiIO;
use ApaiIO\Operations\Search;
use ApaiIO\Request\GuzzleRequestWithoutKeys;
use GuzzleHttp\Client;

use DeezerAPI\Search as DeezerSearch ;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;

use App\Entity\Panier\Panier;

class DemandeDeRecherche extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        //]);
        return $this->redirectToRoute('afficheRecherche');
    }

    /**
     * @Route("/DemandeDeRecherche", name="afficheRecherche")
     */
    public function test1Action(Request $request)
    {

/*
        $response = new Response();
        $response->headers->set("Content-Type", "text/plain");
        $response->setContent($out);

*/
        return $this->render('DemandeDeRecherche.recherche.vu.html.twig');
    }


}




?>