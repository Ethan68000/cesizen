<?php

namespace App\Tests\Unit;

use App\Entity\ExerciceRespiration;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ExerciceRespirationTest extends TestCase
{
    public function testGetSetNameSeries(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setNameSeries('Cohérence cardiaque');

        $this->assertSame('Cohérence cardiaque', $exercice->getNameSeries());
    }

    public function testGetSetTimeInspiration(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setTimeInspiration(4);

        $this->assertSame(4, $exercice->getTimeInspiration());
    }

    public function testGetSetTimeApnea(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setTimeApnea(2);

        $this->assertSame(2, $exercice->getTimeApnea());
    }

    public function testGetSetTimeExpiration(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setTimeExpiration(6);

        $this->assertSame(6, $exercice->getTimeExpiration());
    }

    public function testIdEstNullParDefaut(): void
    {
        $exercice = new ExerciceRespiration();

        $this->assertNull($exercice->getId());
    }

    public function testGetSetIsPredefini(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setIsPredefini(true);

        $this->assertTrue($exercice->isPredefini());
    }

    public function testIsPredefiniDefautFalse(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setIsPredefini(false);

        $this->assertFalse($exercice->isPredefini());
    }

    public function testGetSetUser(): void
    {
        $exercice = new ExerciceRespiration();
        $user     = new User();

        $exercice->setUser($user);

        $this->assertSame($user, $exercice->getUser());
    }

    public function testUserNullParDefaut(): void
    {
        $exercice = new ExerciceRespiration();

        $this->assertNull($exercice->getUser());
    }

    public function testSetUserNull(): void
    {
        $exercice = new ExerciceRespiration();
        $user     = new User();

        $exercice->setUser($user);
        $exercice->setUser(null);

        $this->assertNull($exercice->getUser());
    }

    public function testTempsPositifs(): void
    {
        $exercice = new ExerciceRespiration();
        $exercice->setTimeInspiration(4);
        $exercice->setTimeApnea(0);
        $exercice->setTimeExpiration(6);

        $this->assertGreaterThan(0, $exercice->getTimeInspiration());
        $this->assertGreaterThanOrEqual(0, $exercice->getTimeApnea());
        $this->assertGreaterThan(0, $exercice->getTimeExpiration());
    }
}