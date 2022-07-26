<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CurrencyDateType;
use App\Service\CurrenciesChangeCalculator;
use App\Service\CurrencyApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main')]
class MainController extends AbstractController
{
    public function __construct(
        private readonly CurrencyApi $api,
        private readonly CurrenciesChangeCalculator $calculator,
        ){
    }

    #[Route('', name: '_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CurrencyDateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $date = $data['date'];
            $today = new DateTime('NOW');

            $todayCurrencies = $this->api->getCurrenciesFromDate($today);
            $dateCurrencies = $this->api->getCurrenciesFromDate($date);

            $currencyChange = $this->calculator->calculate($todayCurrencies, $dateCurrencies);
        }

        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
            'currencyChange' => $currencyChange ?? null,
        ]);
    }
}
