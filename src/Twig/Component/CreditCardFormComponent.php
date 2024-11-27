<?php
namespace App\Twig\Component;

use App\Entity\CreditCard;
use App\Form\CreditCardType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
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
    public array $forms = []; // Collection de formulaires dynamiques


    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public ?CreditCard $initialFormData = null;

    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager,FormFactoryInterface $formFactory, Security $security)
    {

        $this->entityManager = $entityManager;
        $this->security = $security;


    }

    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(CreditCardType::class, $this->initialFormData);
    }


    public function mount(CreditCard $creditCard = null): void
    {
        // Initialise l'entité CreditCard si elle est nulle
        $this->creditCard = $creditCard ?? new CreditCard();
        $this->creditCard->setUser($this->security->getUser());

    }

    #[LiveAction]
    public function addCard(): void
    {
        // Ajoute un nouveau formulaire à la collection
        $this->forms[] = new CreditCard();
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): Response
    {
        $this->submitForm();

        $creditCard = $this->getForm()->getData();
        $creditCard->setUser($this->security->getUser());
        $this->entityManager->persist($creditCard);
            $entityManager->flush();
        $this->addFlash('success', 'Post saved!');

        return $this->redirectToRoute('credit_cards'
        );


    }
}

?>