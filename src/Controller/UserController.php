<?php
namespace App\Controller;

use DateInterval;
use DateTime;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("", name="user_")
 */
class UserController extends AbstractController
{
    private $em;
    private $repository;

    public function __construct( EntityManagerInterface $em )
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(User::class);
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

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if( $this->getUser() ){
            return $this->redirectToRoute('main_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        if( $error ){
            $this->addFlash('danger', 'Erreur lors de la connexion' );
        }

        return $this->render('user/login.html.twig', array(
            'lastUsername' => $authenticationUtils->getLastUsername(),
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

    /**
     * @Route("/reset/ask", name="reset_ask")
     */
    public function resetAsk(Request $request, MailerInterface $mailer): Response
    {
        $email = $request->request->get('email');

        $message = "";
        if( !empty( $email ) ){
            $user = $this->repository->findOneBy( array(
                'email' => $email
            ));

            if( !empty( $user ) ){
                $token = bin2hex(random_bytes(24));
                $user->setToken( $token );
                $now = new DateTime();
                $now->add( new DateInterval('PT2H') );
                $user->setTokenExpiredAt( $now );
                $this->em->flush();

                $mail = new Email();
                $mail->from('hello@rdelbaere.fr');
                $mail->to( $user->getEmail() );
                $mail->subject('Réinitialisation de mot de passe');

                $view = $this->renderView( 'mail/reset-mail.html.twig', array(
                    'user' => $user,
                ));
                $mail->html($view);

                $mailer->send( $mail );
            }

            $message = "Si votre adresse email est présente dans notre base de données, vous allez recevoir un lien de réinitialisation valable 2 heures";
        }


        return $this->render('user/rest-ask.html.twig', array(
            'message' => $message
        ));
    }

    /**
     * @Route("/reset/confirm", name="reset_confirm")
     */
    public function resetConfirm(Request $request): Response
    {
        $token = $request->query->get('token');
        $user = $this->repository->findOneBy(array(
            'token' => $token,
        ));

        $now = new DateTime();
        if( empty($user) || $user->getTokenExpiredAt() < $now ){
            throw new NotFoundHttpException();
        }

        return new Response('Formulaire de changement de mot de passe');
    }
}
