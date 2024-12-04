<?php
namespace App\Twig\Component;

use App\Entity\CreditCard;
use App\Entity\User;
use App\Form\CreditCardType;
use App\Form\UserCreditCardType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

#[AsLiveComponent('credit_card_form')]
class CreditCardFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?CreditCard $creditCard = null;

    #[LiveProp]
    public array $forms = [];


    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public ?CreditCard $initialFormData = null;

    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private Security $security;


    #[LiveProp]
    public ?User $user = null;

    public function __construct(EntityManagerInterface $entityManager,FormFactoryInterface $formFactory, Security $security)
    {
        $this->entityManager = $entityManager;

        $this->security = $security;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(UserCreditCardType::class, $this->user);
    }


    public function mount(CreditCard $creditCard = null): void
    {
        $this->creditCard = $creditCard ?? new CreditCard();
        $this->creditCard->setUser($this->security->getUser());
        $this->user = $this->security->getUser();
        $this->creditCard->setUser($this->user);

    }

    #[LiveAction]
    public function removeCreditCard(#[LiveArg] int $index): void
    {
        unset($this->formValues['creditCards'][$index]);
    }
    #[LiveAction]
    public function addCreditCard(): void
    {
        $newCreditCard = new CreditCard();
        $newCreditCard->setNumber('');
        $newCreditCard->setExpirationDate(new \DateTime());
        $newCreditCard->setCvv('');
        $this->formValues['creditCards'][] = $newCreditCard;
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): Response
    {
        $this->submitForm();

        /** @var User $user */
        $user = $this->getForm()->getData();
        $existingCards = $this->entityManager->getRepository(CreditCard::class)
            ->findBy(['user' => $user]);

        foreach ($existingCards as $existingCard) {
            if (!$user->getCreditCards()->contains($existingCard)) {
                $user->addCreditCard($existingCard);
            }
        }
        foreach ($user->getCreditCards() as $creditCard) {
            if (!$creditCard->getId()) {
                $creditCard->setUser($user);
                $entityManager->persist($creditCard);
            }
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Les cartes ont été enregistrées avec succès !');

        return $this->redirectToRoute('credit_cards');
    }
}

?>