<?php

namespace Tests\AppBundle\CalculPrix;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use LOUVRE\TicketBundle\Entity\Visiteur;
use LOUVRE\TicketBundle\CalculPrix\PrixBillet;

class PrixBilletTest extends WebTestCase
{
    public function test_prix_pour_tarif_enfant()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '2009-02-15');
        $visiteur->setDatedenaissance($date);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(800, $result);
    }

    public function test_prix_pour_tarif_normal()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '2000-02-15');
        $visiteur->setDatedenaissance($date);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(1600, $result);
    }

    public function test_prix_pour_tarif_senior()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '1955-02-15');
        $visiteur->setDatedenaissance($date);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(1200, $result);
    }

    public function test_prix_pour_tarif_gratuit()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '2016-02-15');
        $visiteur->setDatedenaissance($date);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(0, $result);
    }

    public function test_prix_tarif_reduit_normal()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '1994-02-15');//à parir de 12ans
        $visiteur->setDatedenaissance($date);
        $visiteur->setTarifreduit(true);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(1000, $result);
    }

    public function test_prix_tarif_reduit_senior()
    {

        $visiteur = new Visiteur();
        $format = 'Y-m-d';
		$date = \DateTime::createFromFormat($format, '1954-02-15');//à parir de 12ans
        $visiteur->setDatedenaissance($date);
        $visiteur->setTarifreduit(true);

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$visiteur]);

        $this->assertEquals(1000, $result);
    }

    public function test_deux_tarif_normal_et_un_senior()
    {
    	$format = 'Y-m-d';

        $Bob = new Visiteur();
        $Emmanuel= new Visiteur();
        $Arnaud= new Visiteur();

        $datedenaissanceBob = \DateTime::createFromFormat($format, '1954-02-15');
		$Bob->setDatedenaissance($datedenaissanceBob);

		$datedenaissanceEmmanuel = \DateTime::createFromFormat($format, '1994-02-15');
		$Emmanuel->setDatedenaissance($datedenaissanceEmmanuel);

		$datedenaissanceArnaud = \DateTime::createFromFormat($format, '1994-06-15');
		$Arnaud->setDatedenaissance($datedenaissanceArnaud);
     

        $PrixBillet = new PrixBillet();

        $result = $PrixBillet->prixTotal([$Bob, $Emmanuel, $Arnaud]);

        $this->assertEquals(4400, $result);
    }
}