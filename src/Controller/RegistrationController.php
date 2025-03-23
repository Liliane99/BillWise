<?php
 
namespace App\Controller;
 
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\EmailConfirmationType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface as SymfonyUserAuthenticatorInterface;



 
class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
 
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
 
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $confirmationCode = random_int(100000, 999999);
            $user->setConfirmationCode(strval($confirmationCode)); 
            $user->setIsVerified(false);
            $user->setRoles(['ROLE_ADMIN']); 

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('ContactFacTech@gmail.com', 'Your App Name'))
                ->to($user->getEmail())
                ->subject('Confirm Your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'confirmation_code' => $confirmationCode,
                    'user' => $user
                ]);

            $mailer->send($email);

            $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            return $this->redirectToRoute('app_home_login');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
 
    #[Route('/check-your-email', name: 'check_your_email')]
        public function checkYourEmail(): Response
        {
            return $this->render('registration/check_your_email.html.twig');
        }
    
    #TODO FINISH EMAIL VERIFICATION
    #[Route('/confirm-email', name: 'confirm_email')]
    public function confirmEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmailConfirmationType::class); 
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $confirmationCode = $form->get('confirmationCode')->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationCode' => $confirmationCode]);
 
            if ($user) {
                $user->setIsVerified(true);
                $user->setConfirmationCode(null); 
                $entityManager->flush();
 
                $this->addFlash('success', 'Your email has been successfully verified.');
 
                return $this->redirectToRoute('app_home_login'); 
            } else {
                $this->addFlash('error', 'Invalid confirmation code.');
            }
        }
 
        return $this->render('registration/confirm_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}