<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SubscribeController {
    private $shouldStop = false;

    public function __invoke(Request $request, MessageRepository $messageRepository) {
        $topics = $request->query->get('topic');
        if (!is_array($topics)) {
            $topics = [$topics];
        }
        
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Nginx: unbuffered responses suitable for Comet and HTTP streaming applications
        
        $lastUpdated = $messageRepository->getLastUpdated($request->headers->get('last-event-id'));

        $response->setCallback(function () use ($messageRepository, $topics, $lastUpdated) {
            while (false === $this->shouldStop) {
                foreach ($messageRepository->fetch($topics, ['lastUpdated' => $lastUpdated]) as $message) {
                     $message->print();
                     flush();
                     if (connection_aborted()) {
                         $this->shouldStop = true;
                         break 2;
                     }

                     $lastUpdated = $message->getCreatedAt();
                 }
                sleep(2);
            }
        });

        return $response;
    }
}
