<?php
require_once '../utils/database.php';

abstract class Person
{
	public $id, $name, $email;
}

class Volunteer extends Person
{
	public $skills, $availability;

	public static function getById($id)
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			"select skills, availability from volunteers where id = ?"
		);
		$stmt->execute([$id]);
		return $stmt->fetchObject('Volunteer');
	}

	public static function getAll()
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->query("select id, skills, availability from volunteers");
		return $stmt->fetchAll(PDO::FETCH_CLASS, 'Volunteer');
	}

	public function create()
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			"insert into volunteers (name, email, skills, availability) values (?, ?, ?, ?)"
		);

		$stmt->execute([
			$this->name,
			$this->email,
			to_pg_array($this->skills),
			to_pg_array($this->availability)
		]);

		$this->id = $dbh->lastInsertId();
	}

	public function update()
	{
		$dbh = Database::getInstance();

		$stmt = $dbh->prepare(
			"update volunteers set name = ?, email = ?, skills = ?, availability = ? where id = ?"
		);
		$stmt->execute([
			$this->name,
			$this->email,
			to_pg_array($this->skills),
			to_pg_array($this->availability),
			$this->id
		]);
	}
}
