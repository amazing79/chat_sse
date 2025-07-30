<?php

namespace Ignacio\ChatSsr\Domain\Chat;

class Chat
{
    public function __construct(
        private ChatRepository $repository,
        private ?Presenter $presenter = null
    )
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    private function hasPresenter(): bool
    {
        return !is_null($this->presenter);
    }

    public function getAllMessagesEvent(): string
    {
        $messages = $this->repository->getAllMessages();
        $output = "\n";
        foreach ($messages as $message) {
            $text = $this->hasPresenter() ? $this->presenter->render($message) : $message;
            $output .= "event: message\n";
            $output .= "data: " . json_encode(['text' => $text]) . "\n\n";
        }
        return $output;
    }

    public function getLastsMessagesEvents($lastCount): string
    {
        // Hay mensajes nuevos → obtener solo los nuevos
        $lastMessages = $this->repository->getNewMessages($lastCount);
        $output = "\n";
        foreach ($lastMessages as $message) {
            $text = $this->hasPresenter() ? $this->presenter->render($message) : $message;
            $output .= "event: message\n";
            $output .= "data: " . json_encode(['text' => $text]) . "\n\n";
        }

        return $output;
    }

    public function sendGlobalMessage(array $msg): void
    {
        $data['usuario'] = $msg['user'];
        $data['texto'] = $msg['message'];
        $message = Message::fromArray($data);
        $this->repository->saveMessage($message);
    }

    public function getTotalMessages(): int
    {
        return $this->repository->getTotalMessages();
    }
}