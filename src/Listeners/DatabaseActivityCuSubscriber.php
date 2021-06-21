<?php

namespace App\Listeners;

use App\Entity\WheelsCu;
use Doctrine\ORM\Events;
use App\Repository\CuRepository;
use Doctrine\Common\EventSubscriber;
use App\Repository\WheelsCuRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WheelsCuTypeRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DatabaseActivityCuSubscriber implements EventSubscriber
{
    const NAME = 'database.cu.subscriber';

    private $cuRepository;

    private $wheelsCuRepository;

    private $wheelsCuTypeRepository;

    private $manager;

    public function __construct(CuRepository $cuRepository, WheelsCuRepository $wheelsCuRepository, 
        WheelsCuTypeRepository $wheelsCuTypeRepository, EntityManagerInterface $manager
    ) {
        $this->cuRepository = $cuRepository;
        $this->wheelsCuRepository = $wheelsCuRepository;
        $this->wheelsCuTypeRepositoryy = $wheelsCuTypeRepository;
        $this->manager = $manager;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }


    public function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WheelsCu) {
            return;
        }

        $cuName = $entity->getWheelsCuType()->getCu()->getName();
        $typeMeule = $entity->getWheelsCuType()->getType();
        $typical = $entity->getTypeMeuleCu()->getTypical();

        $meules = $this->meuleCuRepository->findMolesCuByTypical($cuName, $typeMeule, $typical);

        $stockTotal = 0;

        foreach ($meules as $meule) {
            $stockTotal += $meule->getStock();
        }

        $typeMeuleCu = $entity->getTypeMeuleCu();
        $typeMeuleCu->setStockReel($stockTotal);

        $this->manager->persist($typeMeuleCu);
        $this->manager->flush();
    }
}