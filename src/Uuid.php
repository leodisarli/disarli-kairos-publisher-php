<?php

namespace KairosPublisher;

use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidFactory;

/**
 * class Uuid
 * generate and validate uuid hashs based on Ramsey/Uuid lib
 */
class Uuid
{
    /**
     * method uuid4
     * generate uuid hash
     * @return string
     */
    public function uuid4() : string
    {
        $factory = $this->newUuidFactory();
        $generator = $this->newCombGenerator($factory);
        $codec = $this->newTimestampFirstCombCodec($factory);
        $factory->setRandomGenerator($generator);
        $factory->setCodec($codec);
        return $this->staticGenerateUuid($factory);
    }

    /**
     * method isValid
     * verify if uuid4 hash is valid
     * @param string $uuid
     * @return bool
     */
    public function isValid($uuid) : bool
    {
        return $this->staticIsValidUuid($uuid);
    }

    /**
     * @codeCoverageIgnore
     * method newUuidFactory
     * create and return new UuidFactory object
     * (should not contain any logic, just instantiate the object and return it)
     * @return \Ramsey\Uuid\UuidFactory
     */
    public function newUuidFactory()
    {
        return new UuidFactory();
    }

    /**
     * @codeCoverageIgnore
     * method newCombGenerator
     * create and return new CombGenerator object
     * (should not contain any logic, just instantiate the object and return it)
     * @return \Ramsey\Uuid\Generator\CombGenerator
     */
    public function newCombGenerator(UuidFactory $factory)
    {
        return new CombGenerator(
            $factory->getRandomGenerator(),
            $factory->getNumberConverter()
        );
    }

    /**
     * @codeCoverageIgnore
     * method newTimestampFirstCombCodec
     * create and return new TimestampFirstCombCodec object
     * (should not contain any logic, just instantiate the object and return it)
     * @return \Ramsey\Uuid\Codec\TimestampFirstCombCodec
     */
    public function newTimestampFirstCombCodec(UuidFactory $factory)
    {
        return new TimestampFirstCombCodec(
            $factory->getUuidBuilder()
        );
    }

    /**
     * @codeCoverageIgnore
     * method staticGenerateUuid
     * generate an uuid
     * (should not contain any logic, just call the static method and return his response)
     * @return string
     */
    public function staticGenerateUuid(UuidFactory $factory)
    {
        RamseyUuid::setFactory($factory);
        return RamseyUuid::uuid4()
            ->toString();
    }

    /**
     * @codeCoverageIgnore
     * method staticIsValidUuid
     * validate an uuid
     * (should not contain any logic, just call the static method and return his response)
     * @return string
     */
    public function staticIsValidUuid(string $uuid) : bool
    {
        return RamseyUuid::isValid($uuid);
    }
}
