<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\TopicRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class PublishController {
    public function __invoke(Request $request, TopicRepository $topicRepository, ManagerRegistry $registry) {
        parse_str($request->getContent(), $data);

        if (!isset($data['topic'])) {
            return new Response('Topic is mandatory', 400);
        }

        $manager = $registry->getManagerForClass(Message::class);
        $update = new Message();

        if (!is_array($data['topic'])) {
            $data['topic'] = [$data['topic']];
        }

        foreach ($data['topic'] as $topicName) {
            $update->addTopic($topicRepository->findOneOrCreate($topicName));
        }

        $update->setData($data['data'] ?? '');
        $update->setPrivate($data['private'] ?? false);
        $update->setType($data['type'] ?? 'message');
        $update->setRetry($data['retry'] ?? null);
        $update->setCreatedAt(new \DateTime('now'));

        $manager->persist($update);
        $manager->flush();

        return new Response($update->getId(), 200);
    }
}
