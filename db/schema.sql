create type donationMethod as enum ('cash', 'check', 'online');

create table donors (
	id integer generated always as identity primary key,
	donationMethod donationMethod
);

create table volunteers (
	id integer generated always as identity primary key,
	name text,
	email text unique,
	skills text[],
	availability date[]
);

create table assignments (
	id integer generated always as identity primary key,
	volunteer_id integer references volunteers(id)
);

create table beneficiaries (
	id integer generated always as identity primary key,
	name text,
	needs text[]
);

create table events (
	id integer generated always as identity primary key,
	name text,
	date date,
	address text
);
