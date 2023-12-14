<?php
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class MessageServiceTest extends KernelTestCase {
// Тестируем метод Сервиса sum
public function testgetHappyMessage(): void {
    self::bootKernel();
    // (2) использовать self::getContainer(), чтобы получить доступ к сервис-контейнеру
    $container = static::getContainer();
    // (3) получить сервис из сервис-контейнеров
    $messageGenerator = $container->get(MessageService::class);
    $this->assertContains($messageGenerator->getHappyMessage(),$messageGenerator::MESSAGES);
}
}
