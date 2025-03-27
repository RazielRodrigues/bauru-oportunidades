<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRecord;
use App\Repository\UserRecordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request, UserRecordRepository $userRecordRepository): Response
    {
        $where = [
            'status' => 2,
        ];

        if ($request->query->get('query')) {
            $where['name'] = $request->query->get('query');
        }

        $jobs = $userRecordRepository->findBy($where);
        return $this->render('home/home.html.twig', ['jobs' => $jobs]);
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

  
/**
 * @Route("/create-record", name="app_create_record", methods={"POST"})
 */
public function create(Request $request): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    $requestBody = $request->request->all();

    // Validação básica dos campos
    if (empty($requestBody['job-name'])) {
        $this->addFlash('error', 'O nome da vaga é obrigatório');
        return $this->redirectToRoute('app_home');
    }

    $userRecord = new UserRecord();
    $userRecord->setName($requestBody['job-name']);
    $userRecord->setRecord($requestBody['job-description']);
    $userRecord->setJobType($requestBody['job-type']);
    $userRecord->setStatus(1); // Status 1 = Em revisão
    $userRecord->setCity($requestBody['job-city']);
    $userRecord->setCreatedAt(new \DateTime('now'));

    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    $user->addUserRecord($userRecord);

    try {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userRecord);
        $entityManager->flush();
        
        $this->addFlash('success', 'Vaga cadastrada com sucesso e está em revisão!');
    } catch (\Exception $e) {
        $this->addFlash('error', 'Ocorreu um erro ao cadastrar a vaga: '.$e->getMessage());
    }

    return $this->redirectToRoute('app_home');
}

/**
 * @Route("/home", name="app_home")
 */
public function read(): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    return $this->render('home/index.html.twig', [
        'jobs' => $user->getUserRecords()->getValues(),
    ]);
}

/**
 * @Route("/update-record/{id}", name="app_update_record", methods={"POST"})
 */
public function update(Request $request, UserRecord $userRecord): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
    // Verifica se o registro pertence ao usuário logado
    if ($userRecord->getFkUser() !== $this->getUser()) {
        $this->addFlash('error', 'Você não tem permissão para editar esta vaga');
        return $this->redirectToRoute('app_home');
    }

    $requestBody = $request->request->all();
    
    try {
        $userRecord->setName($requestBody['job-name']);
        $userRecord->setRecord($requestBody['job-description']);
        $userRecord->setJobType($requestBody['job-type']);
        $userRecord->setCity($requestBody['job-city']);
        
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Vaga atualizada com sucesso!');
    } catch (\Exception $e) {
        $this->addFlash('error', 'Erro ao atualizar a vaga: '.$e->getMessage());
    }

    return $this->redirectToRoute('app_home');
}

/**
 * @Route("/delete-record/{id}", name="app_delete_record", methods={"DELETE"})
 */
public function delete(Request $request, UserRecord $userRecord): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
    // Verifica se o registro pertence ao usuário logado
    if ($userRecord->getFkUser() !== $this->getUser()) {
        $this->addFlash('error', 'Você não tem permissão para excluir esta vaga');
        return $this->redirectToRoute('app_home');
    }

    try {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userRecord);
        $entityManager->flush();
        
        $this->addFlash('success', 'Vaga excluída com sucesso!');
    } catch (\Exception $e) {
        $this->addFlash('error', 'Erro ao excluir a vaga: '.$e->getMessage());
    }

    return $this->redirectToRoute('app_home');
}


    /**
     * @Route("/scrapper", name="app_scrapper")
     */
    public function scrapper(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $response = $this->client->request(
            'GET',
            'https://scrapper-88ry.onrender.com'
        );
        $requestBody = $response->toArray()[0];

        /** @var \App\Entity\User $user */
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(2);

        foreach ($requestBody as $body) {
            foreach ($body as $data) {

                $userRecord = new UserRecord();
                $userRecord->setName($data['title']);
                $userRecord->setRecord($data['description']);
                $userRecord->setJobType($data['type']);
                $userRecord->setCity($data['city']);
                $userRecord->setCreatedAt(new \DateTime('now'));
                $user->addUserRecord($userRecord);
                $this->getDoctrine()->getManager()->persist($userRecord);
            }
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json(['message' => 'saved ' . count($requestBody)], 200);
    }
}
