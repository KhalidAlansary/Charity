<?php
require_once 'core/singletons.php';
require_once 'models/payments.php';
require_once 'models/events.php';

abstract class User implements EventListener
{
	public int $id;
	public string $name, $email, $created_at;
	public array $subscriptions;

	public function __construct(array $row)
	{
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->subscriptions = array_keys(json_decode($row['subscriptions'], true));
		$this->created_at = $row['created_at'];
		$this->parse(json_decode($row['data'], true));
	}

	abstract public function parse(array $data);

	public static function login(string $email, string $password)
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

		return new $userClass($row);
	}

	public static function signup(string $name, string $email, string $password, string $type)
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

		return new $userClass($row);
	}

	public static function getById(int $id)
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

		return new $userClass($row);
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

			$users[] = new $userClass($row);
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

class Admin extends User
{
	public function parse($data) {}

	public function update(string $data)
	{
		Logger::log("Admin {$this->name} received update: $data");
	}
}

class Volunteer extends User
{
	public $skills, $availability;

	public function parse($data)
	{
		$this->skills = $data['skills'] ?? [];
		$this->availability = $data['availability'] ?? [];
	}

	public function update(string $data)
	{
		Logger::log("Volunteer {$this->name} received update: $data");
	}
}

class Donor extends User
{
	public PaymentMethod $paymentMethod;

	public function parse(array $data)
	{
		$this->paymentMethod = match ($data['payment_method'] ?? 'credit_card') {
			'credit_card' => new CreditCardPayment,
			'paypal' => new PayPalPayment,
			'bank_transfer' => new BankTransferPayment,
		};
	}

	public function update(string $data)
	{
		Logger::log("Donor {$this->name} received update: $data");
	}
}

class Beneficiary extends User
{
	public $needs;

	public function parse(array $data)
	{
		$this->needs = $data['needs'] ?? [];
	}

	public function update(string $data)
	{
		Logger::log("Beneficiary {$this->name} received update: $data");
	}
}
