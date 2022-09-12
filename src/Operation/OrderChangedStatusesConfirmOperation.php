<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Order;
use SimpleXMLElement;

class OrderChangedStatusesConfirmOperation extends AbstractOperation
{
    /** @var string $changes ONLY_LAST */
    private $client;

    /** @var string $streamId Идентификатор потока, если несколько интеграций  */
    private $streamId;


    /**
     * @param string $streamId
     * @return OrderChangedStatusesConfirmOperation
     */
    public function streamId(string $streamId): self
    {
        $this->streamId = $streamId;

        return $this;
    }

    /**
     * @param bool $client
     * @return OrderChangedStatusesConfirmOperation
     */
    public function client(bool $client): self
    {
        $this->client = $client ? 'CLIENT' : 'AGENT';

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml = $this->createXml('commitlaststatus');
        $xml->addChild('streamid', $this->streamId);
        if ($this->client) $xml->addChild('client', $this->client);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Order[]
     * @throws MeasoftException
     */
    public function search(): array
    {
        $response = $this->request($this->buildXml());

        if (!$response->isSuccess()) {
            throw new MeasoftException($response->getError());
        }

        $resultXml = $response->getXml();

        foreach ($resultXml as $item) {
            $result[] = Order::getFromXml($item);
        }

        return $result ?? [];
    }
}