<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Positive;

class FormController extends AbstractController
{   
    public function getForm($currencies, $request, $apiResponse)
    {
        $routeName = $request->attributes->get('_route');
        $options = [];
        for ($i = 2; $i <= 31; $i++)
        {
            $idApi = $currencies[$i]->getIdApi();
            $keyApi = array_search($idApi, array_column($apiResponse['data'], 'id'));
            if ($keyApi !== false) {
                $options[$currencies[$i]->getName() . ' (' . $currencies[$i]->getSymbol() . ')'] = $idApi;
                $euroConversion[$currencies[$i]->getName() . ' (' . $currencies[$i]->getSymbol() . ')'] = 
                    ['data-amount' => round($apiResponse['data'][$keyApi]['quote']['EUR']['price'], 3)];
            }
        }
        $formRequest = $this->createFormBuilder([])
            ->add('currency', ChoiceType::class, [
                'placeholder' => 'Sélectionner une crypto',
                'choices' => $options,
                'choice_attr' => $euroConversion,
            ])
            ->add('quantity', NumberType::class, [
                'attr' => ['placeholder' => 'Quantité'],
                'invalid_message' => 'La quantité doit être un nombre.',
                'constraints' => new Positive(['message' => 'La quantité doit être positive.'])
            ])
            ->add('amount', NumberType::class, [
                'attr' => ['placeholder' => 'Prix d\'achat'],
                'invalid_message' => 'Le montant doit être un nombre.',
                'constraints' => new Positive(['message' => 'Le montant doit être positif.'])
            ])
            ->add('submit', SubmitType::class, ['label' => 'VALIDER'])
            ->getForm();
        if ($routeName == 'remove')
        {
            $formRequest->remove('amount');
        }
        $formRequest->handleRequest($request);
        return $formRequest;
    }

    public function flushForm($request, $formRequest, $currencyRepo, $em, $apiResponse)
    {
        $routeName = $request->attributes->get('_route');
        $data = $formRequest->getData();
        /* $selectedCurrency equals id_api */
        $selectedCurrency = $data['currency'];
        $selectedQuantity = $data['quantity'];
        $key = array_search($selectedCurrency, array_column($apiResponse['data'], 'id'));
        $euroConversion = $apiResponse['data'][$key]['quote']['EUR']['price'];
        $selectedAmount = $selectedQuantity * $euroConversion;
        $totalRepo = $currencyRepo->findBy(['name' => 'Total'])[0]->getAmount();
        $gainRepo = $currencyRepo->findBy(['name' => 'Gain'])[0]->getAmount();
        $selectedCurrencyRepo = $currencyRepo->findBy(['idApi' => $selectedCurrency]);
        $nameRepo = $selectedCurrencyRepo[0]->getName();
        $quantityRepo = $selectedCurrencyRepo[0]->getQuantity();
        $amountRepo = $selectedCurrencyRepo[0]->getAmount();
        if ($routeName === 'add')
        {
            $newQuantity = $quantityRepo + $selectedQuantity;
            $newAmount = $amountRepo + $selectedAmount;
            $newTotal = $totalRepo + $selectedAmount;
        } else if ($routeName === 'remove')
        {
            $newQuantity = $quantityRepo - $selectedQuantity;
            if ($quantityRepo != 0)
            {
                $selectedQuantityWithAmountRepo = $amountRepo / $quantityRepo * $selectedQuantity;
            } else {
                $selectedQuantityWithAmountRepo = 0;
            }
            /* When a currency is sold, the gain or loss is added to $gain and removed from the currency total */
            $gain = $gainRepo + $selectedAmount - $selectedQuantityWithAmountRepo;
            $newAmount = $amountRepo - $selectedQuantityWithAmountRepo;
            $newTotal = $totalRepo - $selectedQuantityWithAmountRepo + $gain;
        }
        if ($newQuantity >= 0)
        {
            $currencyRepo->findBy(['name' => 'Total'])[0]->setAmount($newTotal);
            $selectedCurrencyRepo[0]->setQuantity($newQuantity);
            $selectedCurrencyRepo[0]->setAmount($newAmount);
            if ($routeName === 'remove')
            {
                $currencyRepo->findBy(['name' => 'Gain'])[0]->setAmount($gain);
            }
            $em->flush();
            $this->addFlash('success', 'La quantité de ' . $selectedQuantity . ' ' . $nameRepo . ' a bien été pris en compte.');
        } else if ($newQuantity < 0) 
        {
            $this->addFlash('fail', 'La quantité pour le ' . $nameRepo . ' ne peut pas être négative.');
        } else 
        {
            $this->addFlash('error', 'Le serveur a rencontré une erreur.');
        }
    }
}