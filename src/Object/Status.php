<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Status extends AbstractObject
{
    /** @var string $name Наименование статуса */
    protected $name;

    /** @var string $eventStore Филиал, к которому относится текущий статус */
    protected $eventStore;

    /** @var string $eventTime Время события по часовому поясу места его наступления */
    protected $eventTime;

    /** @var string $createTimeGmt Время по GMT создания записи о смене статуса в БД */
    protected $createTimeGmt;

    /** @var string $message Наименование филиала-получателя, при передаче между филиалами */
    protected $message;

    /** @var string $title Русское наименование статуса */
    protected $title;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'name'          => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
            'eventStore'    => self::extractXmlValue($xml, 'eventstore', $fromNode),
            'eventTime'     => self::extractXmlValue($xml, 'eventtime', $fromNode),
            'createTimeGmt' => self::extractXmlValue($xml, 'createtimegmt', $fromNode),
            'message'       => self::extractXmlValue($xml, 'message', $fromNode),
            'title'         => self::extractXmlValue($xml, 'title', $fromNode),
        ];

        return new Status($params);
    }

    /** @return string|null Наименование статуса */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Филиал, к которому относится текущий статус */
    public function getEventStore(): ?string
    {
        return $this->eventStore;
    }

    /** @return string|null Время события по часовому поясу места его наступления */
    public function getEventTime(): ?string
    {
        return $this->eventTime;
    }

    /** @return string|null Время по GMT создания записи о смене статуса в БД */
    public function getCreateTimeGmt(): ?string
    {
        return $this->createTimeGmt;
    }

    /** @return string|null Наименование филиала-получателя, при передаче между филиалами */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /** @return string|null Русское наименование статуса */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}