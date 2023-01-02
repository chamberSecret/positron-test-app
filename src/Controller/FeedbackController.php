<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Settings;
use App\Form\FeedbackForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Andante\ReCaptchaBundle\Form\ReCaptchaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class FeedbackController extends AbstractController
{
    private Mailer $mailer;

    public function __construct()
    {
        $transport = Transport::fromDsn($_ENV["MAILER_DSN"]);
        $this->mailer = new Mailer($transport);
    }

    #[Route('/feedback', name: 'feedback/index')]
    public function feedback(): Response
    {
        $feedbackModel = new FeedbackForm();
        $feedbackForm = $this->createFormBuilder($feedbackModel)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('message', TextareaType::class)
            ->add('recaptcha', ReCaptchaType::class)
            ->add('submit', SubmitType::class)
            ->getForm()->createView();

        return $this->render('feedback.html.twig', [
            'feedbackForm' => $feedbackForm,
        ]);
    }

    #[Route('/api/feedback/send', name: 'feedback/send')]
    public function sendFeedback(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $feedbackModel = new FeedbackForm();
        $feedbackForm = $this->createFormBuilder($feedbackModel)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('message', TextareaType::class)
            ->add('recaptcha', ReCaptchaType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $feedbackForm->handleRequest($request);


        if ($feedbackForm ->isSubmitted() && $feedbackForm->isValid()) {
            $feedback = new Feedback();
            $feedback->setEmail($feedbackModel->getEmail());
            $feedback->setName($feedbackModel->getName());
            $feedback->setPhone($feedbackModel->getPhone());
            $feedback->setMessage($feedbackModel->getMessage());

            $entityManager->persist($feedback);
            $entityManager->flush();

            try {
                $mail = $entityManager->getRepository(Settings::class)->find(1);
                $email = (new Email())
                    ->from($feedbackModel->getEmail())
                    ->to($mail->getEmail())
                    ->subject("Book feedback")
                    ->html(`<p>` . $feedbackModel->getMessage() . `</p>`);

                $this->mailer->send($email);

            } catch (\Exception $exception) {
                dd($exception->getMessage());
            } catch (TransportExceptionInterface $e) {
                dd($e->getMessage());
            }
        }

        return $this->redirectToRoute('feedback/index', [], 301);
    }
}