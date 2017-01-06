<?php

use ModbusTcpClient\Network\ModbusConnection;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';

$connection = ModbusConnection::getBuilder()
    ->setPort(5020)
    ->setHost('127.0.0.1')
    ->build();

$startAddress = 12288;
$quantity = 6;
$packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
echo 'Packet to be sent (in hex): ' . $packet->toHex() . PHP_EOL;

try {
    $binaryData = $connection->connect()
        ->send($packet)
        ->receive();
    echo 'Binary received (in hex):   ' . unpack('H*', $binaryData)[1] . PHP_EOL;


    $response = ResponseFactory::parseResponse($binaryData);
    echo 'Parsed packet (in hex):     ' . $response->toHex() . PHP_EOL;
    echo 'Data parsed from packet (bytes):' . PHP_EOL;
    print_r($response->getData());

} catch (Exception $exception) {
    echo 'An exception occurred' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
} finally {
    $connection->close();
}
