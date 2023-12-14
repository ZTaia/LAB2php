<?php

class MessageGeneratorTest extends KernelTestCase
{
public function testHappyMessage()
{
// (1) запустить ядро Symfony
self::bootKernel();
// (2) использовать self::getContainer(), чтобы получить доступ к сервис-контейнеру
$container = static::getContainer();
// (3) получить сервис из сервис-контейнеров
$messageGenerator = $container->get(MessageGenerator::class);
$this->assertContains($messageGenerator->getHappyMessage(),$messageGenerator::MESSAGES);
}
}
