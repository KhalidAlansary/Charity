<?php
require_once 'core/singletons.php';
require_once 'models/payments.php';
require_once 'models/events.php';

interface ILogin
{
	public static function parse($row);
}

abstract class User implements EventListener
{
	public $id, $name, $email, $created_at;
	public array $subscriptions;

	public static function login($email, $password)
	{
		$dbh = Database::getHandle();

		$stmt = $dbh->prepare(
			<<<SQL
			select id, name, email, type, data, subscriptions, created_at
			from users where email = ? and password = ?
		SQL
		);
		$stmt->execute([$email, $password]);
		$row = $stmt->fetch();
		if ($row === false) {
			return false;
		}

		$userClass = match ($row['type']) {
			'admin' => Admin::class,
			'volunteer' => Volunteer::class,
			'donor' => Donor::class,
			'beneficiary' => Beneficiary::class,
		};

		return $userClass::parse($row);
	}

	public static function signup($name, $email, $password, $type)
	{
		$dbh = Database::getHandle();

		// WARNING: In real applications, passwords should be hashed and salted.
		$stmt = $dbh->prepare(
			<<<SQL
			insert into users (name, email, password, type)
			values (?, ?, ?, ?)
			returning id, name, email, data, subscriptions, created_at
		SQL
		);
		try {
			$stmt->execute([$name, $email, $password, $type]);
		} catch (PDOException $e) {
			if ($e->getCode() === '23505') { // Unique violation
				return false; // Email already exists
			}
			throw $e; // Re-throw other exceptions
		}
		$row = $stmt->fetch();

		$userClass = match ($type) {
			'admin' => Admin::class,
			'volunteer' => Volunteer::class,
			'donor' => Donor::class,
			'beneficiary' => Beneficiary::class,
		};

		return $userClass::parse($row);
	}

	public static function getById($id)
	{
		$dbh = Database::getHandle();

		$stmt = $dbh->prepare(
			<<<SQL
			select id, name, email, type, data, subscriptions, created_at
			from users where id = ?
		SQL
		);
		$stmt->execute([$id]);
		$row = $stmt->fetch();
		if ($row === false) {
			return null;
		}
		$userClass = match ($row['type']) {
			'admin' => Admin::class,
			'volunteer' => Volunteer::class,
			'donor' => Donor::class,
			'beneficiary' => Beneficiary::class,
		};

		return $userClass::parse($row);
	}

	public static function getAll()
	{
		$dbh = Database::getHandle();
		$rows = $dbh->query(
			<<<SQL
			select id, name, email, type, data, subscriptions, created_at
			from users
		SQL
		);

		$users = [];
		foreach ($rows as $row) {
			$userClass = match ($row['type']) {
				'admin' => Admin::class,
				'volunteer' => Volunteer::class,
				'donor' => Donor::class,
				'beneficiary' => Beneficiary::class,
			};
			$users[] = $userClass::parse($row);
		}

		return array_filter($users, function ($user) {
			return $user instanceof static;
		});
	}

	public function save()
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			update users
			set name = ?, email = ?, subscriptions = ?
			where id = ?
			SQL
		);
		$stmt->execute([
			$this->name,
			$this->email,
			json_encode(array_fill_keys($this->subscriptions, true)),
			$this->id
		]);
	}

	public function saveSubscription(string $eventType)
	{
		$this->subscriptions[] = $eventType;
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			update users
			set subscriptions = subscriptions || jsonb_build_object(?::text, true)
			where id = ?
			SQL
		);
		$stmt->execute([$eventType, $this->id]);
	}

	public function removeSubscription(string $eventType)
	{
		$this->subscriptions = array_filter(
			$this->subscriptions,
			fn($sub) => $sub !== $eventType
		);
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			update users
			set subscriptions = subscriptions - ?
			where id = ?
			SQL
		);
		$stmt->execute([$eventType, $this->id]);
	}
}

class Admin extends User implements ILogin
{
	public static function parse($row)
	{
		$admin = new self;
		$admin->id = $row['id'];
		$admin->name = $row['name'];
		$admin->email = $row['email'];
		$admin->subscriptions = array_keys(json_decode($row['subscriptions'], true));
		$admin->created_at = $row['created_at'];
		return $admin;
	}

	public function update(string $data)
	{
		Logger::log("Admin {$this->name} received update: $data");
	}
}

class Volunteer extends User implements ILogin
{
	public $skills, $availability;

	public static function parse($row)
	{
		$volunteer = new self;
		$volunteer->id = $row['id'];
		$volunteer->name = $row['name'];
		$volunteer->email = $row['email'];
		$volunteer->subscriptions = array_keys(json_decode($row['subscriptions'], true));
		$volunteer->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$volunteer->skills = $data['skills'] ?? [];
		$volunteer->availability = $data['availability'] ?? [];
		return $volunteer;
	}

	public function update(string $data)
	{
		Logger::log("Volunteer {$this->name} received update: $data");
	}
}

class Donor extends User implements ILogin
{
	public PaymentMethod $paymentMethod;

	public static function parse($row)
	{
		$donor = new self;
		$donor->id = $row['id'];
		$donor->name = $row['name'];
		$donor->email = $row['email'];
		$donor->subscriptions = array_keys(json_decode($row['subscriptions'], true));
		$donor->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$donor->paymentMethod = match ($data['payment_method'] ?? 'credit_card') {
			'credit_card' => new CreditCardPayment,
			'paypal' => new PayPalPayment,
			'bank_transfer' => new BankTransferPayment,
		};
		return $donor;
	}

	public function update(string $data)
	{
		Logger::log("Donor {$this->name} received update: $data");
	}
}

class Beneficiary extends User implements ILogin
{
	public $needs;

	public static function parse($row)
	{
		$beneficiary = new self;
		$beneficiary->id = $row['id'];
		$beneficiary->name = $row['name'];
		$beneficiary->email = $row['email'];
		$beneficiary->subscriptions = array_keys(json_decode($row['subscriptions'], true));
		$beneficiary->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$beneficiary->needs = $data['needs'];
		return $beneficiary;
	}

	public function update(string $data)
	{
		Logger::log("Beneficiary {$this->name} received update: $data");
	}
}
