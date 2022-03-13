<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use App\Entity\Catalogue\Article;
use App\Entity\Panier\Panier;
use App\Entity\Panier\LignePanier;

use Doctrine\ORM\EntityManagerInterface;

class PanierController extends AbstractController
{
	private $entityManager;
	private $logger;
	
	private $panier;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}
	
    /**
     * @Route("/ajouterLigne", name="ajouterLigne")
     */
    public function ajouterLigneAction(Request $request)
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$article = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("refArticle"));
		$this->panier->ajouterLigne($article) ;
		$session->set("panier", $this->panier) ;
		return $this->render('panier.html.twig', [
            'panier' => $this->panier,
        ]);
    }
	
    /**
     * @Route("/supprimerLigne", name="supprimerLigne")
     */
    public function supprimerLigneAction(Request $request)
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$this->panier->supprimerLigne($request->query->get("refArticle")) ;
		$session->set("panier", $this->panier) ;
		if (sizeof($this->panier->getLignesPanier()) === 0)
			return $this->render('panier.vide.html.twig');
		else
			return $this->render('panier.html.twig', [
				'panier' => $this->panier,
			]);
    }
	
    /**
     * @Route("/recalculerPanier", name="recalculerPanier", methods={"GET", "POST"})
     */
    public function recalculerPanierAction(Request $request)
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$it = $this->panier->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			$article = $ligne->getArticle() ;
			$ligne->setQuantite($request->request->get("cart")[$article->getRefArticle()]["qty"]);
			$ligne->recalculer() ;
			$it->next();
		}
		$this->panier->recalculer() ;
		$session->set("panier", $this->panier) ;
		return $this->render('panier.html.twig', [
            'panier' => $this->panier,
        ]);
    }
	
    /**
     * @Route("/accederAuPanier", name="accederAuPanier")
     */
    public function accederAuPanierAction(Request $request)
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		if (sizeof($this->panier->getLignesPanier()) === 0)
			return $this->render('panier.vide.html.twig');
		else
			return $this->render('panier.html.twig', [
				'panier' => $this->panier,
			]);
    }
	
    /**
     * @Route("/commanderPanier", name="commanderPanier")
     */
    public function commanderPanierAction(Request $request)
    {
		return $this->render('commande.html.twig');
    }
}
