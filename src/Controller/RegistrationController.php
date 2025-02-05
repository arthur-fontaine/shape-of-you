<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $name = $form->get('name')->getData();
            $email = $form->get('email')->getData();
            $weightKg = $form->get('weightKg')->getData();
            $sizeCm = $form->get('sizeCm')->getData();
            $hipMeasurementCm = $form->get('hipMeasurementCm')->getData();
            $chestMeasurementCm = $form->get('chestMeasurementCm')->getData();
            $waistMeasurementCm = $form->get('waistMeasurementCm')->getData();
            $armMeasurementCm = $form->get('armMeasurementCm')->getData();
            $legMeasurementCm = $form->get('legMeasurementCm')->getData();
            $footMeasurementCm = $form->get('footMeasurementCm')->getData();


            $user->setName($name);
            $user->setEmail($email);
            $user->setWeightKg($weightKg);
            $user->setSizeCm($sizeCm);
            $user->setHipMeasurementCm($hipMeasurementCm);
            $user->setChestMeasurementCm($chestMeasurementCm);
            $user->setWaistMeasurementCm($waistMeasurementCm);
            $user->setArmMeasurementCm($armMeasurementCm);
            $user->setLegMeasurementCm($legMeasurementCm);
            $user->setFootMeasurementCm($footMeasurementCm);
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setEnabled(true);
            $user->setFake(false);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);


            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mail@shapeofyou.life', 'Shape of You'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('hello');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('hello');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('hello');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
