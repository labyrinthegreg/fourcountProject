<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Fourcount::class, mappedBy="created_by")
     */
    private $created_fourcounts;

    /**
     * @ORM\ManyToMany(targetEntity=Fourcount::class, mappedBy="participants")
     */
    private $fourcounts;

    /**
     * @ORM\OneToMany(targetEntity=Expense::class, mappedBy="paid_by")
     */
    private $paid_expenses;

    /**
     * @ORM\ManyToMany(targetEntity=Expense::class, inversedBy="users")
     */
    private $expenses;

    public function __construct()
    {
        $this->created_fourcounts = new ArrayCollection();
        $this->fourcounts = new ArrayCollection();
        $this->paid_expenses = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Fourcount[]
     */
    public function getCreatedFourcounts(): Collection
    {
        return $this->created_fourcounts;
    }

    public function addCreatedFourcount(Fourcount $createdFourcount): self
    {
        if (!$this->created_fourcounts->contains($createdFourcount)) {
            $this->created_fourcounts[] = $createdFourcount;
            $createdFourcount->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedFourcount(Fourcount $createdFourcount): self
    {
        if ($this->created_fourcounts->removeElement($createdFourcount)) {
            // set the owning side to null (unless already changed)
            if ($createdFourcount->getCreatedBy() === $this) {
                $createdFourcount->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fourcount[]
     */
    public function getFourcounts(): Collection
    {
        return $this->fourcounts;
    }

    public function addFourcount(Fourcount $fourcount): self
    {
        if (!$this->fourcounts->contains($fourcount)) {
            $this->fourcounts[] = $fourcount;
            $fourcount->addParticipant($this);
        }

        return $this;
    }

    public function removeFourcount(Fourcount $fourcount): self
    {
        if ($this->fourcounts->removeElement($fourcount)) {
            $fourcount->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Expense[]
     */
    public function getPaidExpenses(): Collection
    {
        return $this->paid_expenses;
    }

    public function addPaidExpense(Expense $paidExpense): self
    {
        if (!$this->paid_expenses->contains($paidExpense)) {
            $this->paid_expenses[] = $paidExpense;
            $paidExpense->setPaidBy($this);
        }

        return $this;
    }

    public function removePaidExpense(Expense $paidExpense): self
    {
        if ($this->paid_expenses->removeElement($paidExpense)) {
            // set the owning side to null (unless already changed)
            if ($paidExpense->getPaidBy() === $this) {
                $paidExpense->setPaidBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Expense[]
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        $this->expenses->removeElement($expense);

        return $this;
    }
}
