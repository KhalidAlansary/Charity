<?php
require_once 'core/singletons.php';

class Donation
{
	public int $id;
	public float $amount;
	public Donor $donor;
	public DonationState $state;

	public function __construct(float $amount, Donor $donor)
	{
		$this->id = -1;
		$this->amount = $amount;
		$this->donor = $donor;
		$this->state = new CreatedState($this);
	}

	public function proceed()
	{
		$this->state->proceed();
	}

	public function cancel()
	{
		$this->state->cancel();
	}

	public static function getAllPending()
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			select * from pending_donations join users
			on pending_donations.donor_id = users.id
		SQL
		);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$donor_row = $row;
			$donor_row['id'] = $row['donor_id'];
			$donor = Donor::parse($donor_row);
			$donation = new Donation($row['amount'], $donor);
			$donation->id = $row['id'];
			$donation->state = new PendingState($donation);
			yield $donation;
		}
	}

	public static function getPendingById(int $id)
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			select * from pending_donations join users
			on pending_donations.donor_id = users.id
			where pending_donations.id = ?
		SQL
		);

		$stmt->execute([$id]);
		$row = $stmt->fetch();
		if ($row === false) {
			return null;
		}

		$donor_row = $row;
		$donor_row['id'] = $row['donor_id'];
		$donor = Donor::parse($donor_row);
		$donation = new Donation($row['amount'], $donor);
		$donation->id = $row['id'];
		$donation->state = new PendingState($donation);

		return $donation;
	}
}

abstract class DonationState
{
	protected Donation $donation;

	public function __construct(Donation $donation)
	{
		$this->donation = $donation;
	}

	abstract public function proceed();
	abstract public function cancel();
}

class CreatedState extends DonationState
{
	public function proceed()
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			insert into pending_donations (amount, donor_id)
			values (?, ?)
		SQL
		);
		$stmt->execute([$this->donation->amount, $this->donation->donor->id]);

		$this->donation->state = new PendingState($this->donation);
	}

	public function cancel()
	{
		$this->donation->state = new CancelledState($this->donation);
	}
}

class PendingState extends DonationState
{
	public function proceed()
	{
		$logger = Logger::getInstance();
		$logger->write(
			"Donation of {$this->donation->amount} by {$this->donation->donor->name} has been confirmed."
		);
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			delete from pending_donations
			where id = ?
		SQL
		);
		$stmt->execute([$this->donation->id]);
		$this->donation->state = new AcceptedState($this->donation);
	}

	public function cancel()
	{
		$logger = Logger::getInstance();
		$logger->write(
			"Donation of {$this->donation->amount} by {$this->donation->donor->name} has been cancelled."
		);
		$this->donation->state = new CancelledState($this->donation);
	}
}

/* Terminal states */

class AcceptedState extends DonationState
{
	public function proceed()
	{
		throw new Exception("Donation has already been confirmed and cannot be processed further.");
	}

	public function cancel()
	{
		throw new Exception("Donation has already been confirmed and cannot be cancelled.");
	}
}

class CancelledState extends DonationState
{
	public function proceed()
	{
		throw new Exception("Donation has been cancelled and cannot be processed further.");
	}

	public function cancel()
	{
		throw new Exception("Donation has already been cancelled and cannot be cancelled again.");
	}
}
