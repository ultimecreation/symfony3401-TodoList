<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use AppBundle\Form\AppUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccessController extends Controller
{
    /**
     * @Route("/inscription",name="register")
     */
    public function registerAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        ObjectManager $entityManager
        ) {
        // create empty $user
        $user = new AppUser();

        //create empty form
        $form = $this->createForm(AppUserType::class, $user)->handleRequest($request);

        // check if form is ok
        if ($form->isSubmitted() && $form->isValid()) {
            // get and encode password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // save user
            $entityManager->persist($user);
            $entityManager->flush();

            // set success message
            $this->addFlash('success', 'votre compte a été créé avec succès');
            // redirect to homepage
            return $this->redirectToRoute('login');
        }

        return $this->render('access/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/connexion",name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('access/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/deconnexion",name="logout")
     */
    public function logout()
    {
    }
}
