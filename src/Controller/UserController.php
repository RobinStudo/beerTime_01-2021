<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("", name="user_")
 */
class UserController extends AbstractController
{
    private $em;

    public function __construct( EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $encoded = $encoder->encodePassword( $user, $user->getPlainPassword() );
            $user->setPassword($encoded);

            $this->em->persist( $user );
            $this->em->flush();

            $this->addFlash('success', 'Votre compte à bien été créé');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
