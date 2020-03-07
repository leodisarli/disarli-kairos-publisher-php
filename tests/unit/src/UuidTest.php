<?php

use KairosPublisher\Uuid;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\UuidFactory;

class UuidTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KairosPublisher\Uuid::uuid4
     */
    public function testUuid4()
    {
        $factoryMock = Mockery::mock(UuidFactory::class);
        $factoryMock->shouldReceive('setRandomGenerator')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);
        $factoryMock->shouldReceive('setCodec')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);

        $combGeneratorSpy = Mockery::spy(CombGenerator::class);
        $timestampFirstCombCodecSpy = Mockery::spy(TimestampFirstCombCodec::class);

        $uuidPartialMock = Mockery::mock(Uuid::class)->makePartial();
        $uuidPartialMock->shouldReceive('newUuidFactory')
            ->once()
            ->withAnyArgs()
            ->andReturn($factoryMock);
        $uuidPartialMock->shouldReceive('newCombGenerator')
            ->once()
            ->withAnyArgs()
            ->andReturn($combGeneratorSpy);
        $uuidPartialMock->shouldReceive('newTimestampFirstCombCodec')
            ->once()
            ->withAnyArgs()
            ->andReturn($timestampFirstCombCodecSpy);
        $uuidPartialMock->shouldReceive('staticGenerateUuid')
            ->once()
            ->withAnyArgs()
            ->andReturn('1bd0b9d2-cafd-4073-baa5-1dfeff42f7e5');

        $result = $uuidPartialMock->uuid4();
        $this->assertInternalType('string', $result);
        $this->assertEquals('1bd0b9d2-cafd-4073-baa5-1dfeff42f7e5', $result);
    }

    /**
     * @covers \KairosPublisher\Uuid::isValid
     */
    public function testIsValid()
    {
        $uuid = '1bd0b9d2-cafd-4073-baa5-1dfeff42f7e5';
        $uuidPartialMock = Mockery::mock(Uuid::class)->makePartial();
        $uuidPartialMock->shouldReceive('staticIsValidUuid')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);

        $result = $uuidPartialMock->isValid($uuid);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
