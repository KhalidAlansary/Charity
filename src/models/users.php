<?php
require_once 'models/database.php';

interface ILogin
{
	public static function parse($row);
}

abstract class User
{
	public $id, $name, $email, $created_at;

	public static function login($email, $password)
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			<<<SQL
			select id, name, email, type, data, created_at
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
		$dbh = Database::getInstance();

		// WARNING: In real applications, passwords should be hashed and salted.
		$stmt = $dbh->prepare(
			<<<SQL
			insert into users (name, email, password, type)
			values (?, ?, ?, ?)
			returning id, name, email, data, created_at
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
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			<<<SQL
			select id, name, email, type, data, created_at
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
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			<<<SQL
			select id, name, email, type, data, created_at
			from users
		SQL
		);
		$stmt->execute();
		$users = [];
		while ($row = $stmt->fetch()) {
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
}

class Admin extends User implements ILogin
{
	public static function parse($row)
	{
		$admin = new Admin();
		$admin->id = $row['id'];
		$admin->name = $row['name'];
		$admin->email = $row['email'];
		$admin->created_at = $row['created_at'];
		return $admin;
	}
}

class Volunteer extends User implements ILogin
{
	public $skills, $availability;

	public static function parse($row)
	{
		$volunteer = new Volunteer();
		$volunteer->id = $row['id'];
		$volunteer->name = $row['name'];
		$volunteer->email = $row['email'];
		$volunteer->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$volunteer->skills = $data['skills'] ?? [];
		$volunteer->availability = $data['availability'] ?? [];
		return $volunteer;
	}
}

class Donor extends User implements ILogin
{
	public $donationMethod;

	public static function parse($row)
	{
		$donor = new Donor();
		$donor->id = $row['id'];
		$donor->name = $row['name'];
		$donor->email = $row['email'];
		$donor->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$donor->donationMethod = $data['donationMethod'] ?? 'cash';
		return $donor;
	}
}

class Beneficiary extends User implements ILogin
{
	public $needs;

	public static function parse($row)
	{
		$beneficiary = new Beneficiary();
		$beneficiary->id = $row['id'];
		$beneficiary->name = $row['name'];
		$beneficiary->email = $row['email'];
		$beneficiary->created_at = $row['created_at'];

		$data = json_decode($row['data'], true);
		$beneficiary->needs = $data['needs'];
		return $beneficiary;
	}
}
