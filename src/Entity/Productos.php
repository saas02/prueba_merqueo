<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Productos
 *
 * @ORM\Table(name="productos")
 * @ORM\Entity
 */
class Productos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="inventario", type="integer", nullable=false)
     */
    private $inventario;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="creado", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $creado = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="actualizado", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $actualizado = 'CURRENT_TIMESTAMP';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getInventario(): ?int
    {
        return $this->inventario;
    }

    public function setInventario(int $inventario): self
    {
        $this->inventario = $inventario;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(?int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getCreado(): ?\DateTimeInterface
    {
        return $this->creado;
    }

    public function setCreado(?\DateTimeInterface $creado): self
    {
        $this->creado = $creado;

        return $this;
    }

    public function getActualizado(): ?\DateTimeInterface
    {
        return $this->actualizado;
    }

    public function setActualizado(?\DateTimeInterface $actualizado): self
    {
        $this->actualizado = $actualizado;

        return $this;
    }


}
