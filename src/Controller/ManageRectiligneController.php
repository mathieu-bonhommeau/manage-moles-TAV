<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Entity\Position;
use App\Form\MachineType;
use App\Form\PositionType;
use App\Repository\MachineRepository;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MeulesRectiRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ManageRectiligneController extends AbstractController
{
    /**
     * @Route("/manage/rectiligne", name="manage_rectiligne")
     */
    public function manageRectiligne(Request $request, EntityManagerInterface $manager,
        MachineRepository $machineRepository
    ): Response {
        $machine = new Machine();

        $form = $this->createForm(MachineType::class, $machine);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($machine);
            $manager->flush();

            return $this->redirectToRoute('manage_rectiligne');
        }

        $machines = $machineRepository->findAll();

        return $this->render('updateDatabase/manageRectiligne.html.twig', [
            'form' => $form->createView(),
            'machines' => $machines
        ]);
    }

    /**
     * @Route("/manage/{nameMachine}/change-name", name="change_name_rectiligne")
     */
    public function renameRectiligne(MachineRepository $machineRepository,
        EntityManagerInterface $manager, $nameMachine
    ) : Response {
        $machine = $machineRepository->findOneBy(['name' => $nameMachine]);

        if (!$machine) {
            throw new NotFoundHttpException("Cette machine n'existe pas");
        }

        $data = $this->get('request_stack')->getCurrentRequest()->request->all();

        if ($data && $data["newRectiligneName"] != NULL) {
            
            $machine->setName($data["newRectiligneName"]);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($machine);
            $manager->flush();

        }

        return $this->redirectToRoute('edit_rectiligne', [
            'nameMachine' => $data["newRectiligneName"]
        ]);

    }

    /**
     * @Route("/delete/rectiligne/{id}", name="delete_machine")
     */
    public function deleteMachine(MachineRepository $machineRepository,
        EntityManagerInterface $manager, MeulesRectiRepository $meulesRectiRepository, $id
    ) {
        $machine = $machineRepository->findOneBy(['id' => $id]);  
        
        if (!$machine) {
            throw new NotFoundHttpException("Cette machine n'existe pas");
        }

        $meulesRecti = $meulesRectiRepository->findAllOrderByPosition($machine->getName());

        if ($machine && $meulesRecti == NULL) {
            $manager->remove($machine);
            $manager->flush();
        } else {
            $message = $this->addFlash('warning', 'Des meules sont liées à cette machine, la suppression est impossible.'); 
        }

        return $this->redirectToRoute('manage_rectiligne');
    }

    /**
     * @Route("/edit/rectiligne/{nameMachine}", name="edit_rectiligne")
     */
    public function editRectiligne(MachineRepository $machineRepository, 
        $nameMachine, EntityManagerInterface $manager
    ) {
        $request = $this->get('request_stack')->getCurrentRequest();

        $machine = $machineRepository->findOneBy(['name' => $nameMachine]);

        if (!$machine) {
            throw new NotFoundHttpException("Cette machine n'existe pas");
        }
        
        //$formMachine is the form for edit all positions linked
        $formMachine = $this->createForm(MachineType::class, $machine);
        
        $formMachine->handleRequest($request);

        if ($formMachine->isSubmitted() && $formMachine->isValid()) {
            
            $manager->persist($machine);
            $manager->flush();

            return $this->redirectToRoute('edit_rectiligne', [
                'nameMachine' => $nameMachine
            ]);
        }

        $newPosition = new Position();

        //$formPosition is the form for add a new positions in a machine
        $formPosition = $this->createForm(PositionType::class, $newPosition);

        $formPosition = $formPosition->handleRequest($request);

        if ($formPosition->isSubmitted() && $formPosition->isValid()) {
            
            $newPosition->setMachine($machine);
            $manager->persist($newPosition);
            $manager->flush();

            return $this->redirectToRoute('edit_rectiligne', [
                'nameMachine' => $nameMachine
            ]);
        }

        return $this->render('updateDatabase/editRectiligne.html.twig', [
            'formMachine' => $formMachine->createView(),
            'formPosition' => $formPosition->createView(),
            'machine' => $machine
        ]);
    }

    /**
     * @Route("/delete/{nameMachine}/position/{id}", name="delete_position")
     */
    public function deletePosition(PositionRepository $positionRepository,
    EntityManagerInterface $manager, $id, $nameMachine
    ) {
        $position = $positionRepository->findOneBy(['id' => $id]);

        if ($id) {
            $manager->remove($position);
            $manager->flush();
        }

        return $this->redirectToRoute('edit_rectiligne', [
            'nameMachine' => $nameMachine
        ]);
    }

}
