create type donationMethod as enum (
	'cash',
	'check',
	'online'
);

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
	created_at timestamp default current_timestamp
);

create table assignments (
	id integer primary key generated always as identity,
	volunteer_id integer references users (id)
);

create table events (
	id integer generated always as identity primary key,
	name text,
	date date,
	address text
);
