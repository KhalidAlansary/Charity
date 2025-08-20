<?php
require_once 'core/singletons.php';
require_once 'models/users.php';

interface PaymentMethod
{
	public function pay(float $amount): void;
}

class CreditCardPayment implements PaymentMethod
{
	public function __toString()
	{
		return "Credit Card";
	}

	public function pay(float $amount): void
	{
		Logger::log("Processing credit card payment of \${$amount}");
	}
}

class PayPalPayment implements PaymentMethod
{
	public function __toString()
	{
		return "PayPal";
	}

	public function pay(float $amount): void
	{
		Logger::log("Processing PayPal payment of \${$amount}");
	}
}

class BankTransferPayment implements PaymentMethod
{
	public function __toString()
	{
		return "Bank Transfer";
	}

	public function pay(float $amount): void
	{
		Logger::log("Processing bank transfer payment of \${$amount}");
	}
}

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
		$rows = $dbh->query(
			<<<SQL
			select pending_donations.id as donation_id,
				pending_donations.amount as amount,
				users.id as id,
				users.name as name,
				users.email as email,
				users.created_at as created_at,
				users.data as data
			from pending_donations join users
			on pending_donations.donor_id = users.id
		SQL
		);

		foreach ($rows as $row) {
			$donor = Donor::parse($row);
			$donation = new Donation($row['amount'], $donor);
			$donation->id = $row['donation_id'];
			$donation->state = new PendingState($donation);
			yield $donation;
		}
	}

	public static function getPendingById($id)
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			select pending_donations.id as donation_id,
				pending_donations.amount as amount,
				users.id as id,
				users.name as name,
				users.email as email,
				users.created_at as created_at,
				users.data as data
			from pending_donations join users
			on pending_donations.donor_id = users.id
			where pending_donations.id = ?
		SQL
		);

		$stmt->execute([$id]);
		$row = $stmt->fetch();
		if ($row === false) {
			return null;
		}

		$donor = Donor::parse($row);
		$donation = new Donation($row['amount'], $donor);
		$donation->id = $row['donation_id'];
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
		$this->donation->donor->paymentMethod->pay($this->donation->amount);
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

		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			delete from pending_donations
			where id = ?
		SQL
		);
		$stmt->execute([$this->donation->id]);

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
