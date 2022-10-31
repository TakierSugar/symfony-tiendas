<?php

namespace App\Entity;

use App\Repository\TiendaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TiendaRepository::class)]
class Tienda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"El nombre es obligatorio")]
    private ?string $nombre = null;

    
    #[ORM\Column(length:15)]
    #[Assert\NotBlank(message:"El teléfono es obligatorio")] 
    private ?string $telefono = null;
    
     #[ORM\Column(length:255)]
     #[Assert\NotBlank(message:"El lugar es obligatorio")]
    private ?string $lugar = null;

    
    #[ORM\ManyToOne(targetEntity:Empresa::class)]
    private ?Empresa $empresa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(?string $lugar): self
    {
        $this->lugar = $lugar;

        return $this;
    }
    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }
}
