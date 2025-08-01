<?php
require_once '../utils/database.php';

abstract class User
{
	public $id, $name, $email;
	protected $pswhash, $created_at;
	protected static $table;

	public static function login($email, $password)
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			"select * from users natural join " . static::$table . " where email = ? and pswhash = crypt(?, pswhash)"
		);
		$stmt->execute([$email, $password]);
		return $stmt->fetchObject(static::class);
	}

	public static function signup($email, $name, $password)
	{
		$dbh = Database::getInstance();

		try {
			$dbh->beginTransaction();

			$stmt1 = $dbh->prepare(
				"insert into users (email, name, pswhash)
				values (?, ?, crypt(?, gen_salt('bf')))
				returning id, email, name"
			);
			$stmt1->execute([$email, $name, $password]);
			$user = $stmt1->fetchObject(static::class);

			$stmt2 = $dbh->prepare(
				"insert into " . static::$table . " (id) values (?)"
			);
			$stmt2->execute([$user->id]);

			$dbh->commit();
		} catch (Exception $e) {
			$dbh->rollBack();
			throw $e;
		}
		return $user;
	}
}

class Volunteer extends User
{
	protected static $table = 'volunteers';

	public $skills, $availability;
}

class Donor extends User
{
	protected static $table = 'donors';

	public $donationMethod;
}

class Beneficiary extends User
{
	protected static $table = 'beneficiaries';

	public $needs;
}
