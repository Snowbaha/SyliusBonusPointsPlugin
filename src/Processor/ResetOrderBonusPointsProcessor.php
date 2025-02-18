<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Processor;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResetOrderBonusPointsProcessor implements ResetOrderBonusPointsProcessorInterface
{
    /** @var OrderBonusPointsPurifierInterface */
    private $orderBonusPointsPurifier;

    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier,
        RepositoryInterface $bonusPointsRepository
    ) {
        $this->orderBonusPointsPurifier = $orderBonusPointsPurifier;
        $this->bonusPointsRepository = $bonusPointsRepository;
    }

    public function process(OrderInterface $order): void
    {
        /** @var BonusPointsInterface|null $bonusPoints */
        $bonusPoints = $this->bonusPointsRepository->findOneBy(['order' => $order, 'isUsed' => true]);

        if (null !== $bonusPoints) {
            $this->orderBonusPointsPurifier->purify($bonusPoints);

            $this->bonusPointsRepository->add($bonusPoints);
        }
    }
}
