<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Order;
use SimpleXMLElement;

class OrderChangedStatusesOperation extends AbstractOperation
{
    /** @var string $streamId Идентификатор потока, если несколько интеграций */
    private $streamId;


    /**
     * @param string $streamId
     * @return OrderChangedStatusesOperation
     */
    public function streamId(string $streamId): self
    {
        $this->streamId = $streamId;

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml = $this->createXml('statusreq');
        $xml->addChild('streamid', $this->streamId);
        $xml->addChild('changes', 'ONLY_LAST');

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


    /**
     * Подтверждение получения статусов заказов
     * @throws MeasoftException
     */
    public function commit()
    {
        $operation = new OrderChangedStatusesConfirmOperation($this->api);
        $operation->streamId($this->streamId);
        $operation->search();
    }
}