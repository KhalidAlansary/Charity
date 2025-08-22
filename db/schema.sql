create type userType as enum (
	'admin',
	'volunteer',
	'donor',
	'beneficiary'
);

create table users (
	id integer generated always as identity primary key,
	name text,
	email text unique not null,
	password text not null,
	type userType not null,
	data jsonb not null default '{}',
	subscriptions jsonb not null default '{}',
	created_at timestamp default current_timestamp
);

create table pending_donations (
	id integer generated always as identity primary key,
	amount numeric not null,
	donor_id integer references users (id)
);

create table fundraisers (
	id integer generated always as identity primary key,
	title text not null,
	date timestamp not null
);
