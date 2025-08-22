<?php
require_once 'core/singletons.php';
interface EventListener
{
	public function update(string $data);
	public function saveSubscription(string $eventType);
	public function removeSubscription(string $eventType);
}

class EventManager extends Singleton
{
	private array $listeners;

	protected function __construct()
	{
		$this->listeners = [];
		$users = User::getAll();
		foreach ($users as $user) {
			foreach ($user->subscriptions as $eventType) {
				$this->subscribe($eventType, $user);
			}
		}
	}

	public function subscribe(string $eventType, EventListener $listener)
	{
		if (!isset($this->listeners[$eventType])) {
			$this->listeners[$eventType] = [];
		}
		if (!in_array($listener, $this->listeners[$eventType])) {
			$this->listeners[$eventType][] = $listener;
		}
		$listener->saveSubscription($eventType);
	}

	public function unsubscribe(string $eventType, EventListener $listener)
	{
		if (!isset($this->listeners[$eventType])) {
			return;
		}
		// NOTE: ===?
		$key = array_search($listener, $this->listeners[$eventType]);
		if ($key !== false) {
			unset($this->listeners[$eventType][$key]);
		}
		$listener->removeSubscription($eventType);
	}

	public function notify(string $eventType, string $data)
	{
		if (!isset($this->listeners[$eventType])) {
			return;
		}
		foreach ($this->listeners[$eventType] as $listener) {
			$listener->update($data);
		}
	}
}

class Fundraiser
{
	public int $id;
	public string $title;
	public string $date;

	public function __construct(string $title, string $date)
	{
		$this->title = $title;
		$this->date = $date;
	}

	public function save(): void
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->prepare(
			<<<SQL
			insert into fundraisers (title, date)
			values (?, ?)
			SQL
		);
		$stmt->execute([$this->title, $this->date]);
		$this->id = (int)$dbh->lastInsertId();
		$new_fundraiser = $this;
		require 'components/fundraiser_row.php';
		$eventManager = EventManager::getInstance();
		$eventManager->notify('fundraisers', "{$this->title} on {$this->date}");
	}

	public static function getAll(): array
	{
		$dbh = Database::getHandle();
		$stmt = $dbh->query(
			<<<SQL
			select * from fundraisers
			SQL
		);
		$rows = $stmt->fetchAll();
		$fundraisers = [];
		foreach ($rows as $row) {
			$fundraiser = new Fundraiser($row['title'], $row['date']);
			$fundraiser->id = (int)$row['id'];
			$fundraisers[] = $fundraiser;
		}
		return $fundraisers;
	}
}
