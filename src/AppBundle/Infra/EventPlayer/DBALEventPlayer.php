<?php

namespace AppBundle\Infra\EventPlayer;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\Exception\InvalidIdentifierException;
use Broadway\Serializer\SerializerInterface;
use Doctrine\DBAL\Connection;
use Rhumsaa\Uuid\Uuid;

class DBALEventPlayer
{
    /** @var Connection */
    private $connection;

    /** @var SerializerInterface */
    private $payloadSerializer;

    /** @var SerializerInterface */
    private $metadataSerializer;

    /** @var EventBusInterface */
    private $eventBus;

    /** @var string */
    private $tableName;

    /** @var bool */
    private $useBinary;

    private $types = [];

    /** @var \DateTime|null */
    private $startDate;

    /** @var \DateTime|null */
    private $endDate;

    /**
     * @param Connection             $connection
     * @param SerializerInterface    $payloadSerializer
     * @param SerializerInterface    $metadataSerializer
     * @param string                 $tableName
     * @param bool                   $useBinary
     * @param EventBusInterface|null $eventBus
     */
    public function __construct(
        Connection $connection,
        SerializerInterface $payloadSerializer,
        SerializerInterface $metadataSerializer,
        $tableName,
        $useBinary = false,
        EventBusInterface $eventBus = null
    ) {
        $this->connection = $connection;
        $this->payloadSerializer = $payloadSerializer;
        $this->metadataSerializer = $metadataSerializer;
        $this->eventBus = $eventBus;
        $this->tableName = $tableName;
        $this->useBinary = $useBinary;
    }

    /**
     * @param EventBusInterface $eventBus
     *
     * @return DBALEventPlayer
     */
    public function setEventBus(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;

        return $this;
    }

    /**
     * @return DBALEventPlayer
     */
    public function forAllTypes()
    {
        $this->types = [];

        return $this;
    }

    /**
     * @param string|string[] $types
     *
     * @return DBALEventPlayer
     */
    public function forTypes($types)
    {
        if (false === is_array($types)) {
            $types = [$types];
        }
        foreach ($types as $type) {
            if (false === is_string($type)) {
                throw new \InvalidArgumentException('Type must be string');
            }
            $this->types[] = $type;
        }

        return $this;
    }

    /**
     * @param \DateTime|null $endDate
     *
     * @return DBALEventPlayer
     */
    public function setEndDate(\DateTime $endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @param \DateTime|null $startDate
     *
     * @return DBALEventPlayer
     */
    public function setStartDate(\DateTime $startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @param callable $progressCallback
     */
    public function play($progressCallback = null)
    {
        if (null == $this->eventBus) {
            throw new \LogicException('EventBus not set');
        }

        $statement = $this->getPlayStatement();
        $statement->execute();
        $totalCount = $statement->rowCount();
        $count = 0;
        while ($row = $statement->fetch()) {
            $count++;
            if ($progressCallback) {
                call_user_func($progressCallback, $count, $totalCount);
            }
            if ($this->useBinary) {
                $row['uuid'] = $this->convertStorageValueToIdentifier($row['uuid']);
            }
            $event = $this->deserializeEvent($row);
            $stream = new DomainEventStream([$event]);
            $this->eventBus->publish($stream);
        }
    }

    /**
     * @return \Doctrine\DBAL\Driver\Statement
     */
    private function getPlayStatement()
    {
        $query = 'SELECT * FROM ' . $this->tableName . ' WHERE 1=1 ';
        if ($this->startDate) {
            $query .= ' AND recorded_on >= :startDate ';
        }
        if ($this->endDate) {
            $query .= ' AND recorded_on <= :endDate ';
        }
        if ($this->types) {
            $query .= ' AND `type` IN :types ';
        }
        $query .= 'ORDER BY recorded_on ASC';
        $playStatement = $this->connection->prepare($query);
        if ($this->startDate) {
            $playStatement->bindValue('startDate', $this->startDate->format('Y-m-d H:i:s'));
        }
        if ($this->endDate) {
            $playStatement->bindValue('endDate', $this->endDate->format('Y-m-d H:i:s'));
        }
        if ($this->types) {
            $playStatement->bindValue('types', $this->types, Connection::PARAM_STR_ARRAY);
        }

        return $playStatement;
    }

    /**
     * @param array $row
     *
     * @return DomainMessage
     */
    private function deserializeEvent(array $row)
    {
        return new DomainMessage(
            $row['uuid'],
            $row['playhead'],
            $this->metadataSerializer->deserialize(json_decode($row['metadata'], true)),
            $this->payloadSerializer->deserialize(json_decode($row['payload'], true)),
            DateTime::fromString($row['recorded_on'])
        );
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function convertStorageValueToIdentifier($id)
    {
        if ($this->useBinary) {
            try {
                return Uuid::fromBytes($id)->toString();
            } catch (\Exception $e) {
                throw new InvalidIdentifierException(
                    'Could not convert binary storage value to UUID.'
                );
            }
        }

        return $id;
    }
}
